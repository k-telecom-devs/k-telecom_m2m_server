<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubgroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subgroup', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('subgroup_name');

            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('group');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subgroup');
    }
}
