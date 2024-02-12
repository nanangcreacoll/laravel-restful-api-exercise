<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertNotNull;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'nanang',
            'password' => 'password',
            'name' => 'Nanang Muhamad'
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'username' => 'nanang',
                    'name' => 'Nanang Muhamad'
                ]
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => ['The username field is required.'],
                    'password' => ['The password field is required.'],
                    'name' => ['The name field is required.']
                ]
            ]);
    }

    public function testRegisterExists() 
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'nanang',
            'password' => 'password',
            'name' => 'Nanang Muhamad'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => ["The username is already registered."]
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'nanang',
            'password' => 'password'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'nanang',
                    'name' => 'Nanang Muhamad'
                ]
            ]);

        $user = User::where('username', 'nanang')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->post('/api/users/login', [
            'username' => 'nanang',
            'password' => 'password'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Username or password wrong.'
                    ]
                ]
            ]);
    }

    public function testLoginFailedWrongPassword()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'nanang',
            'password' => 'wrong'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Username or password wrong.'
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'test_token'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'nanang',
                    'name' => 'Nanang Muhamad'
                ]
            ]);
    }

    public function testGetUnautorized()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Unauthorized.'
                    ]
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
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
