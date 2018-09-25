<?php

use Illuminate\Database\Seeder;
use App\StockUnit;

class StockUnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stock1 = new StockUnit();
        $stock1->label = 'piece';
        $stock1->relative_sku_to_sku = 1;
        $stock1->save();
    }
}
