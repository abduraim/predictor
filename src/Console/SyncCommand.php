<?php

namespace Abduraim\Predictor\Console;

use Abduraim\Predictor\Models\Collections\NeuronableCollection;
use Abduraim\Predictor\PredictorService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'predictor:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all neurons and neuron clusters';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(PredictorService $predictorService)
    {
        $predictorService->sync();
    }
}
