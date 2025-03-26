<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::create(['code' => 'US', 'name' => 'United States']);
        Country::create(['code' => 'CA', 'name' => 'Canada']);
        Country::create(['code' => 'DE', 'name' => 'Germany']);
        Country::create(['code' => 'EG', 'name' => 'Egypt']);
    }
}