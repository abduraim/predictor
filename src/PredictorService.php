<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Interfaces\PredictorInterface;
use Abduraim\Predictor\Models\Neuron;
use Abduraim\Predictor\Models\NeuronCluster;
use Abduraim\Predictor\Models\NeuronClusterConnection;
use App\Models\Gender;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class PredictorService implements PredictorInterface
{

    public function predict()
    {
        return ['qwer', 'asdf', 'zxcv'];
    }

    public function getNeuronableModels(): Collection
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

    public function syncNeuronableModels(): void
    {
        $neuronClusters = NeuronCluster::all()->pluck('neuronable_type');
        foreach ($this->getNeuronableModels() as $neuronableModel) {
            if (!$neuronClusters->contains($neuronableModel)) {
                $neuronCluster = new NeuronCluster();
                $neuronCluster->neuronable_type = $neuronCluster->title = $neuronableModel;
                $neuronCluster->save();
            }
        }
    }

    public function syncNeurons(): void
    {
        foreach ($this->getNeuronableModels() as $neuronableModel) {
            // remove excess neurons
            Neuron::query()->whereDoesntHaveMorph('neuronable', $neuronableModel)->delete();
            // create missing neurons
            $neuronableModel::query()
                ->doesntHave('neuron')
                ->each(function (Neuronable $model) {
                    $model->neuron()->create(['options' => ['fdsa', 'rewq', 'vcxz']]);
                });
        }

    }

    public function syncNeuronConnections(): void
    {


//        $t[0] = collect([1,2,3]);
//        $t[1] = collect([4,5]);
//        $t[2] = collect([6,7,8]);
//
//        $result = $t[0];
//        array_shift($t);
//        $result = $result->crossJoin(...$t);
//
//        dd($result);



        NeuronClusterConnection::each(function (NeuronClusterConnection $item) {

            $collections = [];
            collect($item->clusters)->each(function ($model) use (&$collections) {
                $collections[] = $this->getLazyCollection($model);
            });

            $result = [];
            foreach ($collections as $collection) {

            }


            dd($collections);
            $model::cursor()->each(function ($item) {
                dd($item);
            });

            $model::query()->each(function ($item) {
                dd($item->id);
            });

            $result = [];


            foreach ($item->clusters as $model) {
                $result[] = $model::query()->pluck('id');
            }
            dd($result);
        });
    }

    private function getLazyCollection($model)
    {
        return $model::cursor();
    }
}