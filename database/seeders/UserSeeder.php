<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'nanang',
            'password' => Hash::make('password'),
            'name' => 'Nanang Muhamad',
            'token' => 'test_token'
        ]);

        User::create([
            'username' => 'Akhsan',
            'password' => Hash::make('password'),
            'name' => 'Akhsan',
            'token' => 'test_token_akhsan'
        ]);
    }
}
