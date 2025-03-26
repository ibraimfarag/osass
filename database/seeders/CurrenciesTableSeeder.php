<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::create(['code' => 'USD', 'name' => 'US Dollar']);
        Currency::create(['code' => 'CAD', 'name' => 'Canadian Dollar']);
        Currency::create(['code' => 'EUR', 'name' => 'Euro']);
        Currency::create(['code' => 'EGP', 'name' => 'Egyptian Pound']);
    }
}
