<?php

namespace Abduraim\Predictor;

use Abduraim\Predictor\Interfaces\Neuronable;
use Abduraim\Predictor\Interfaces\PredictorInterface;
use Abduraim\Predictor\Models\NeuronCluster;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
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
}