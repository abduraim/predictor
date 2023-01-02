<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database schema.
     *
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;

    /**
     * Create a new migration instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->schema = Schema::connection($this->getConnection());
    }

    /**
     * Get the migration connection name.
     *
     * @return string|null
     */
    public function getConnection()
    {
        return config('predictor.storage.database.connection');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('neurons', function (Blueprint $table) {
            $table->id();
            $table->morphs('neuronable', 'neuronable_index');
            $table->unique(['neuronable_type', 'neuronable_id'], 'neuronable_unique');
            $table->json('options');
            $table->timestamps();
        });

        $this->schema->create('neuron_connections', function (Blueprint $table) {
            $table->id();
            $table->json('neurons')->comment('Массив нейронов');
            // Todo: mysql 5.7.8
            //            $table->unique('neurons');
            $table->boolean('status')->comment('Статус');
            $table->unsignedBigInteger('weight')->comment('Вес связи');
            $table->timestamps();
        });
        
        $this->schema->create('neuron_clusters', function (Blueprint $table) {
            $table->id();
            $table->string('neuronable_type');
            $table->unique('neuronable_type');
            $table->string('title');
            $table->timestamps();
        });

        $this->schema->create('neuron_cluster_connections', function (Blueprint $table) {
            $table->id();
            $table->json('clusters')->comment('Массив связанных кластеров нейронов');
            // Todo: need mysql 5.7.8 version minimum
//            $table->unique('clusters');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('neurons');
        $this->schema->dropIfExists('neuron_connections');
        $this->schema->dropIfExists('neuron_clusters');
        $this->schema->dropIfExists('neuron_cluster_connections');
    }
};