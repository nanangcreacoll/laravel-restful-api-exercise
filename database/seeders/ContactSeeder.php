<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'nanang')->first();
        Contact::create([
            'first_name' => 'Nanang',
            'last_name' => 'Muhamad',
            'email' => 'nanangcreacoll@outlook.com',
            'phone' => '082314533452',
            'user_id' => $user->id
        ]);
    }
}
