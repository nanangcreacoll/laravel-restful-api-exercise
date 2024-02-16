<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\ContactSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => 'Nanang',
            'last_name' => 'Muhamad',
            'email' => 'nanangcreacoll@outlook.com',
            'phone' => '082314533452'
        ],
        [
            'Authorization' => 'test_token'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'first_name' => 'Nanang',
                    'last_name' => 'Muhamad',
                    'email' => 'nanangcreacoll@outlook.com',
                    'phone' => '082314533452'
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
            'phone' => '082314533452'
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
            'phone' => '082314533452'
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

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test_token'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Nanang',
                    'last_name' => 'Muhamad',
                    'email' => 'nanangcreacoll@outlook.com',
                    'phone' => '082314533452'
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id + 1, [
            'Authorization' => 'test_token'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Not found.'
                    ]
                ]
            ]);
    }

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test_token_akhsan'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Not found.'
                    ]
                ]
            ]);
    }
}
