<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Premium Widget',
            'base_price' => 199.99,
            'description' => 'High-quality widget for professional use'
        ]);

        Product::create([
            'name' => 'Basic Widget',
            'base_price' => 99.99,
            'description' => 'Entry-level widget for casual users'
        ]);
    }
}
