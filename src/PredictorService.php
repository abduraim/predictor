<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Interfaces\PredictorInterface;
use Abduraim\Predictor\Models\Collections\NeuronableCollection;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use Abduraim\Predictor\Models\NeuronConnection;
use App\Models\Gender;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;

class PredictorService implements PredictorInterface
{
    public function predict()
    {
        return ['qwer', 'asdf', 'zxcv'];
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
                // Если такого кластера в БД нет
                if (!$dbNeuronableClassesCollection->contains($neuronableModel)) {
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

                    // Создаем отсутствующие нейроны
                    $neuronableModel::query()
                        ->doesntHave('neuron')
                        ->each(function (Neuronable $model) {
                            $model->neuron()->create(['options' => []]);
                        });
                }

                // Если такого кластера нет среди файлов
                if (!$fileNeuronableClassesCollection->contains($neuronableModel)) {
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
        });
    }
    

    /**
     * Создание связи нейронов
     *
     * @param Neuronable[] $neuronables Массив neuronable-объектов
     * @param bool $status Статус
     * @param int $weight Вес связи
     * @return NeuronConnection
     */
    public function makeNeuronConnection(
        NeuronableCollection $neuronableCollection,
        bool $status = true,
        int $weight = 1
    ): NeuronConnection {
        return NeuronConnection::query()->create([
            'neurons' => $neuronableCollection->getNeuronIds(),
            'status' => $status,
            'weight' => $weight
        ]);
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
        $models = collect(File::allFiles(app_path()))
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
            });

        return $models->values();
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
