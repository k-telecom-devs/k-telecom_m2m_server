<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->default('Датчик');
            $table->unsignedBigInteger('sleep')->default(30);

            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('id')->on('versions');

            $table->unsignedBigInteger('sensor_id');
            $table->foreign('sensor_id')->references('id')->on('sensors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensors_settings');
    }
}
