<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');

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
        Schema::dropIfExists('station_settings');
    }
}

