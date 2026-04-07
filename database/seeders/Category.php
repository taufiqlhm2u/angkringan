<?php

namespace Database\Seeders;

use App\Models\Category as Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Category extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    Model::create([
            'name' => 'Minuman',
            'status' => 'active'
        ]);

        Model::create([
            'name' => 'Makanan',
            'status' => 'active'
        ]);

        Model::create([
            'name' => 'Sate',
            'status' => 'active'
        ]);

        Model::create([
            'name' => 'Rokok',
            'status' => 'active'
        ]);
    }
}
