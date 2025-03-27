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
        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'exchange_rate' => 1,
            'is_base' => true
        ]);

        Currency::create([
            'code' => 'CAD',
            'name' => 'Canadian Dollar',
            'exchange_rate' => 1.25,
            'is_base' => false
        ]);

        Currency::create([
            'code' => 'EUR',
            'name' => 'Euro',
            'exchange_rate' => 0.85,
            'is_base' => false
        ]);

        Currency::create([
            'code' => 'EGP',
            'name' => 'Egyptian Pound',
            'exchange_rate' => 50.59,
            'is_base' => false
        ]);
    }
}
