<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Product::create([
            'name' => 'Jam Matoa',
            'description' => 'Jam keren berbahan kulit',
            'price' => 5000000,
            'stock' => 1,
            'photos' => [],
        ]);
    }
}
