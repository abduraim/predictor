<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Interfaces\PredictorInterface;
use Abduraim\Predictor\Models\Builders\NeuronConnectionBuilder;
use Abduraim\Predictor\Models\Collections\NeuronableCollection;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Abduraim\Predictor\Models\NeuronConnection;
use Abduraim\Predictor\Repositories\NeuronClusterConnectionRepository;
use Abduraim\Predictor\Repositories\NeuronRepository;
use Abduraim\Predictor\Services\HelperService;
use App\Models\Gender;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;

class PredictorService implements PredictorInterface
{
    public function predict(int $predictableNeuronClusterId, array $payload, bool $strictMode = false, int $resultsCount = 50)
    {
        $result = [];

        $count = count($payload);

        foreach ($payload as $key => $item) {
            echo "{$count}/{$key}\n";

            /** @var NeuronCluster $neuronCluster */
            $neuronCluster = NeuronCluster::query()->findOrFail($item['neuron_cluster_id']);

            /** @var Neuron $neuron */
            $neuron = Neuron::query()->findOrFail($item['neuron_id']);

            if ($neuronCluster->neurons->doesntContain($neuron)) {
                throw new \Exception('Нейрон не принадлежит данному кластеру');
            }

            // Собираем данные о весах и кол-ве
            /** @var NeuronClusterConnection $neuronClusterConnection */
            $neuronClusterConnection = NeuronClusterConnection::query()
                ->where('target_cluster_id', $predictableNeuronClusterId)
                ->where('determinant_cluster_id', $neuronCluster->getKey())
                ->first();

            $neuronClusterConnection
                ->neuron_connections()
                ->actual()
                ->where('determinant_neuron_id', $neuron->getKey())
                ->each(function (NeuronConnection $neuronConnection) use (&$result, $neuronClusterConnection) {

                    if (!isset($result[$neuronConnection->target_neuron_id])) {
                        $result[$neuronConnection->target_neuron_id] = [
                            'count' => 0,
                            'weight' => 0,
                        ];
                    }

                    $result[$neuronConnection->target_neuron_id]['count']++;
                    $result[$neuronConnection->target_neuron_id]['weight'] += $neuronConnection->weight * $neuronClusterConnection->weight;
                });
        }

        // Сортируем
        $sortedCollection = collect($result)
            ->when(
                $strictMode,
                function ($collection, $value) {
                    return $collection
                        ->filter(function ($item) {
                            return $item['count'] === $count;
                        })
                        ->sortBy('weight', 'desc');
                },
                function ($collection, $value) {
                    return $collection
                        ->sortBy([
                            ['count', 'desc'],
                            ['weight', 'desc']
                        ]);
                });

        return $sortedCollection->keys()->splice(0, $resultsCount);
    }

    public function sync(): void
    {
        DB::transaction(function () {
            // Существующие файлы neuronable-классов
            $fileNeuronableClassesCollection = $this->getInitedNeuronableClassesCollection();

            // Существующие записи в БД о neuronable-классах
            $dbNeuronableClassesCollection = NeuronCluster::query()->pluck('neuronable_type');

            // Все neuronable-классы
            $commonClassesCollection = $fileNeuronableClassesCollection->merge($dbNeuronableClassesCollection)->unique();

            // Обработка
            foreach ($commonClassesCollection as $neuronableModel) {

                $originClassName = Relation::getMorphedModel($neuronableModel) ?: $neuronableModel;

                // Если такого кластера в БД нет
                if ($dbNeuronableClassesCollection->doesntContain($neuronableModel)) {

                    // Создаем запись о кластере
                    NeuronCluster::query()->create([
                        'neuronable_type' => $neuronableModel,
                        'title' => $neuronableModel,
                    ]);

                    // Синхронизируем нейроны такого класса

                    // Нейроны несуществующих сущностей
                    $missingNeuronsQuery = Neuron::query()->whereDoesntHaveMorph('neuronable', $neuronableModel);

                    // Удаем связи нейронов несуществующих сущностей
                    $this->removeNeuronConnections($missingNeuronsQuery->pluck('id')->toArray());

                    // Удаляем нейроны несуществующих сущностей
                    $missingNeuronsQuery->delete();
                }

                // Создаем отсутствующие нейроны
                $originClassName::query()
                    ->doesntHave('neuron')
                    ->each(function (Neuronable $model) {
                        (new NeuronRepository())->store($model);
                    });

                // Если такого кластера нет среди файлов
                if ($fileNeuronableClassesCollection->doesntContain($neuronableModel)) {
                    // Удаляем записи о связях этого класера
                    NeuronClusterConnection::query()
                        ->whereHasCluster($neuronableModel)
                        ->delete();

                    // Удаляем запись о кластере
                    NeuronCluster::query()
                        ->whereNeuronableType($neuronableModel)
                        ->delete();

                    // Нейроны несуществующего класса
                    $removedNeuronsQuery = Neuron::query()->whereNeuronableType($neuronableModel);

                    // Удаляем связи нейронов
                    $this->removeNeuronConnections($removedNeuronsQuery->pluck('id')->toArray());

                    // Удаляем нейроны
                    $removedNeuronsQuery->delete();
                }
            }

            ini_set('memory_limit', '2048M');

            // Синхронизация связей кластеров нейронов
            NeuronClusterConnection::query()
                ->cursor()
                ->each(function (NeuronClusterConnection $neuronClusterConnection, $key) {
                    echo "{$key}\n";
                    (new NeuronClusterConnectionRepository())->syncNeuronConnections($neuronClusterConnection);
                });
        });
    }


    /**
     * Увеличение веса связи нейронов
     *
     * @param NeuronableCollection $neuronableCollection
     * @return NeuronConnection
     */
    public function touchNeuronConnection(NeuronableCollection $neuronableCollection): NeuronConnection
    {
        $neuronConnection = NeuronConnection::query()
            ->whereNeuronIds($neuronableCollection->getNeuronIds())
            ->first();

        // Изменение веса
        $neuronConnection->increment('weight');

        return $neuronConnection;
    }


    public function getVariants(NeuronableCollection $neuronableCollection)
    {
        dd('asdf');
    }


    /**
     * Возвращает коллекцию определнных neuronable-классов существующих в приложении
     *
     * @return Collection<Neuronable>
     */
    private function getInitedNeuronableClassesCollection(): Collection
    {
        return collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('%s%s',
                    Container::getInstance()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class)
                        && !$reflection->isAbstract()
                        && $reflection->implementsInterface(Neuronable::class);
                }

                return $valid;
            })
            ->map(function ($model) {
                return HelperService::getPolymorphClassAliasIfExist($model);
            });
    }

    /**
     * Замена названий классов, полиморфными алиасами
     *
     * @param string $alias Алиас
     * @param string $model Модель
     * @return void
     */
    private function replaceToPolymorphAlias(string $alias, string $model)
    {
        // Устанавливаем алиас, вместо модели, если такие записи есть
        Neuron::query()
            ->where('neuronable_type', $model)
            ->update(['neuronable_type' => $alias]);

        NeuronCluster::query()
            ->where('neuronable_type', $model)
            ->update(['neuronable_type' => $alias]);
    }

    /**
     * Удаление записей о связях нейронов
     *
     * @param array $neuronIds Массив id'шек нейронов, связи которых необходим удалить
     */
    private function removeNeuronConnections(array $neuronIds): void
    {
        foreach ($neuronIds as $neuronId) {
            NeuronConnection::query()
                ->whereJsonContains('neurons', $neuronId)
                ->delete();
        }
    }
}
