<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->string('barcode', 100)->unique()->nullable();
            $table->text('description', 1000)->nullable();
            $table->timestamps();
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                  ->references('id')->on('categories');
            $table->integer('manufacturer_id')->unsigned();
            $table->foreign('manufacturer_id')
                  ->references('id')->on('manufacturers');
            $table->integer('default_stock_unit')->unsigned();
            $table->foreign('default_stock_unit')
                  ->references('id')->on('stock_units');      
                  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
