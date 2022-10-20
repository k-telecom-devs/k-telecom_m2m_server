<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddAdditionalColumnsForUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('users', function($table)
            {
                $table->string('phone_number');
                $table->boolean('notifications');
                $table->boolean('auto_update');
                $table->boolean('auto_pay');
            });
        }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_additional_columns_for_user');
    }
}
