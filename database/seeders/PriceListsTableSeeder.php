<?php

namespace Database\Seeders;

use App\Models\PriceList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PriceList::create([
            'product_id' => 1,
            'country_code' => 'US',
            'currency_code' => 'USD',
            'price' => 179.99,
            'start_date' => '2025-01-01',
            'end_date' => '2025-04-20',
            'priority' => 1
        ]);

        PriceList::create([
            'product_id' => 1,
            'country_code' => null,
            'currency_code' => 'EUR',
            'price' => 159.99,
            'start_date' => '2025-02-01',
            'end_date' => '2025-05-21',
            'priority' => 2
        ]);
    }
}