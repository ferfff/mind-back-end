<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'id' => 1001,
            'name' => 'User fake',
            'password' => Hash::make('passfake'),
            'email' => 'fakeuser@email.com',
            'english_level' => 'B2',
            'knowledge' => 'knowledge',
            'link_cv' => 'link_cv.com.mx',
            'role' => 'superadmin',
        ]);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_login_fail()
    {
        $this->postJson('api/login', [
            'email' => 'randomemail@email.com',
            'password' => 'random',
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthorized',
            ]);
    }

    public function test_login_succesfull()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'passfake',
            'email' => 'fakeuser@email.com',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'user',
                'authorization'
            ]);
    }

    public function test_register()
    {
        $response = $this->actingAs($this->user)
                    ->withSession(['banned' => false])
                    ->post('/api/register', [
                        'name' => 'new user',
                        'email' => 'newemailuser@email.com',
                        'password' => 'newPassword',
                        'english_level' => 'A1',
                        'knowledge' => 'Lot of knowledge',
                        'link_cv' => 'http://mycv.com.mx',
                        'role' => 'normal',
                    ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'user',
            ]);
    }

    public function test_logout()
    {
        $login = $response = $this->postJson('/api/login', [
            'password' => 'passfake',
            'email' => 'fakeuser@email.com',
        ]);

        $token = $login->getOriginalContent();

        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/api/logout', [], ['Authorization' => 'Bearer '. $token['authorization']['token']]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);
    }

}
