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
            $table->time('notification_start_at')->default('00:00:00');
            $table->time('notification_end_at')->default('00:00:00');

            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('id')->on('versions');

            $table->unsignedBigInteger('sensor_id');
            $table->foreign('sensor_id')->references('id')->on('sensors');

            $table->unsignedBigInteger('station_id');
            $table->foreign('station_id')->references('id')->on('stations');
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
