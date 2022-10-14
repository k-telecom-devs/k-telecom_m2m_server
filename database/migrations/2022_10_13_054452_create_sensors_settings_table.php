<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors_settings', function (Blueprint $table) {
            Schema::enableForeignKeyConstraints();
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table-> unsignedBigInteger('sleep')->nullable();
            
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
