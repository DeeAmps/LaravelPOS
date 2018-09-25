<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 10,2);
            $table->integer('unit_price');
            $table->decimal('discount', 3,2)->nullable();
            $table->integer('purchase_id')->unsigned();      
            $table->foreign('purchase_id')
                  ->references('id')->on('purchases'); 
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')
                  ->references('id')->on('products');
            $table->integer('stock_unit_id')->unsigned();      
            $table->foreign('stock_unit_id')
                  ->references('id')->on('stock_units'); 
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
        Schema::dropIfExists('purchase_entries');
    }
}
