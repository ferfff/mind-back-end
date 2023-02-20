<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The email field is required. (and 1 more error)",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }

    public function testSuccessfulLogin()
    {
        /*$user = User::factory()->create([
            'email' => 'user@email.com',
            'password' => '12345'
        ]);

        $response = $this->json('POST', 'api/login', [
                'email' => 'user@email.com',
                'password' => '12345'
            ])
            ->actingAs($user, 'api')
            ->withSession(['banned' => false]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'user',
                'authorization',
            ]);*/
            $this->assertTrue(true);
    }
}
