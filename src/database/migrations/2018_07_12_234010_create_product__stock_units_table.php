<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStockUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_units', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('reoder_level')->unsigned()->nullable()->default(0);
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
            $table->integer('stock_unit_id')->unsigned();
            $table->foreign('stock_unit_id')
                  ->references('id')->on('stock_units')
                  ->onDelete('cascade');
            $table->decimal('stock_quantity', 10,2)->nullable()->default(0.00);
            $table->integer('cost_price')->nullable();
            $table->integer('selling_price')->nullable();
            $table->integer('currency_id')->default(1);          
            $table->timestamps();
            $table->primary(['product_id', 'stock_unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_type_stock_unit');
    }
}
