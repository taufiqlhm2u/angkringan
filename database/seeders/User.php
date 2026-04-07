<?php

namespace Database\Seeders;

use App\Models\User as UserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserModel::create([
            'name' => 'Super Admin',
            'address' => 'Head Office',
            'phone_number' => '081111111111',
            'email' => 'super@mail.com',
            'password' => Hash::make('password'),
            'role' => 'super',
            'status' => 'active'
        ]);

        UserModel::create([
            'name' => 'Admin User',
            'address' => 'Admin Office',
            'phone_number' => '082222222222',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        UserModel::create([
            'name' => 'Cashier User',
            'address' => 'Cashier Desk',
            'phone_number' => '083333333333',
            'email' => 'cashier@mail.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'status' => 'active'
        ]);
    }
}
