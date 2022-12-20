<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupAndSubgroupColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sensor_settings', function (Blueprint $table) {

            $table->BigInteger('min_trigger')->default(-100);
            $table->BigInteger('max_trigger')->default(100);

            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('group');

            $table->unsignedBigInteger('subgroup_id')->nullable();
            $table->foreign('subgroup_id')->references('id')->on('subgroup');
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
