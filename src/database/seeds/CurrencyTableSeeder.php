<?php

use Illuminate\Database\Seeder;
use App\Currency;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cedi = new Currency();
        $cedi->label = 'GHANA CEDIS';
        $cedi->symbol = 'GHC';
        $cedi->rate = 1;
        $cedi->save();
    }
}
