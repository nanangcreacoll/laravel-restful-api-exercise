<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => 'Nanang',
            'last_name' => 'Muhamad',
            'email' => 'nanangcreacoll@outlook.com',
            'phone' => '082146492752'
        ],
        [
            'Authorization' => 'test_token'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'first_name' => 'Nanang',
                    'last_name' => 'Muhamad',
                    'email' => 'nanangcreacoll@outlook.com',
                    'phone' => '082146492752'
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'Muhamad',
            'email' => 'nanangcreacoll',
            'phone' => '082146492752'
        ],
        [
            'Authorization' => 'test_token'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'first_name' => [
                        'The first name field is required.'
                    ],
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]);
    }

    public function testCreateUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => 'Nanang',
            'last_name' => 'Muhamad',
            'email' => 'nanangcreacoll@outlook.com',
            'phone' => '082146492752'
        ],
        [
            'Authorization' => 'wrong_token'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Unauthorized.'
                    ]
                ]
            ]);
    }
}
