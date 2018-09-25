<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdjustmentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_code')->unique();
            $table->integer('reason_id')->unsigned();
            $table->foreign('reason_id')
                  ->references('id')->on('stock_adjust_reasons');
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')
                  ->references('id')->on('users');            
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
        Schema::dropIfExists('adjustment_histories');
    }
}
