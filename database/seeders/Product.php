<?php

namespace Database\Seeders;

use App\Models\Product as Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Product extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Model::create([
            'category_id' => 1,
            'name' => 'Teh Botol',
            'image' => 'products/teh-botol.jpg',
            'stock' => 50,
            'price' => 5000,
            'status' => 'active'
        ]);

        Model::create([
            'category_id' => 2,
            'name' => 'Nasi Goreng',
            'image' => 'products/nasi-goreng.jpg',
            'stock' => 30,
            'price' => 15000,
            'status' => 'active'
        ]);

        Model::create([
            'category_id' => 3,
            'name' => 'Sate Ayam',
            'image' => 'products/sate-ayam.jpg',
            'stock' => 40,
            'price' => 20000,
            'status' => 'active'
        ]);

        Model::create([
            'category_id' => 4,
            'name' => 'Rokok Surya',
            'image' => 'products/rokok-surya.jpg',
            'stock' => 25,
            'price' => 3000,
            'status' => 'active'
        ]);
    }
}
