<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeDeleteMonthlyStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_stats', function (Blueprint $table) {
            $table->dropForeign(['sensor_id']);
            $table->foreign('sensor_id')->references('id')->on('sensors')->onDelete("cascade")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
