<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAdjustmentHistoryEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_history_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')
                  ->references('id')->on('products');
            $table->integer('stock_unit_id')->unsigned();
            $table->foreign('stock_unit_id')
                  ->references('id')->on('stock_units');
            $table->integer('history_id')->unsigned();
            $table->foreign('history_id')
                  ->references('id')->on('adjustment_histories');
            $table->decimal('old_quantity')->required();
            $table->decimal('new_quantity')->required();            
            $table->decimal('quantity', 10,2)->required();   
            $table->integer('cost_price')->required();
            $table->integer('selling_price')->required();    
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
        Schema::dropIfExists('adjustment_history_entries');
    }
}
