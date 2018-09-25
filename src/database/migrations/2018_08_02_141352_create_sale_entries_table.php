<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id')->unsigned();    
            $table->foreign('sale_id')
                  ->references('id')->on('sales'); 
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')
                  ->references('id')->on('products');
            $table->integer('stock_unit_id')->unsigned();      
            $table->foreign('stock_unit_id')
                  ->references('id')->on('stock_units'); 
            $table->integer('unit_price');
            $table->decimal('quantity', 10,2);
            $table->decimal('discount', 3,2)->nullable();
            $table->integer('balance')->required(); 
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
        Schema::dropIfExists('sale_entries');
    }
}
