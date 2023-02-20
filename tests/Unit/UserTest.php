<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get all users.
     *
     * @return void
     */
    public function test_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
                         ->withSession(['banned' => false])
                         ->get('/api/users/index');
 
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'users',
            ]);
    }
}
