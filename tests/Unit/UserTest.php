<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'id' => 100,
            'name' => 'User fake',
            'password' => 'passfake',
            'email' => 'fakeuser@email.com',
            'english_level' => 'B2',
            'knowledge' => 'knowledge',
            'link_cv' => 'link_cv.com.mx',
        ]);
    }


    /**
     * Test get all users.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->actingAs($this->user)
                        ->withSession(['banned' => false])
                        ->get('/api/users/index');
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'users',
            ]);
    }

    /**
     * Test show specific users.
     *
     * @return void
     */
    public function test_show()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->get('/api/users/show/100');
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'user' => [
                    'name' => 'User fake',
                    'email' => 'fakeuser@email.com',
                ],
            ]);
    }

    /**
     * Test show specific users error.
     *
     * @return void
     */
    public function test_show_error()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->get('/api/users/show/1000');
 
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'status',
                'message',
            ]);
    }

    /**
     * Test show specific users.
     *
     * @return void
     */
    public function test_update()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->put('/api/users/update/100', [
                            'name' => 'new name',
                            'email' => 'newemail@email.com',
                            'password' => 'newPassword',
                         ]);
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'user' => [
                    'name' => 'new name',
                    'email' => 'newemail@email.com',
                ],
            ]);
    }

    /**
     * Test show specific users error.
     *
     * @return void
     */
    public function test_update_error()
    {
        User::factory()->create(['id' => 300]);
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->put('/api/users/update/1000', [
                            'name' => 'new name',
                            'email' => 'newemail@email.com',
                            'password' => 'newPassword',
                         ]);
 
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'status',
                'message',
            ]);
    }

    /**
     * Test get Info.
     *
     * @return void
     */
    public function test_get_info()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->get('/api/users/getinfo');
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'user' => [
                    'name' => 'User fake',
                    'email' => 'fakeuser@email.com',
                    'english_level' => 'B2',
                    'knowledge' => 'knowledge',
                    'link_cv' => 'link_cv.com.mx',
                ],
            ]);
    }

    /**
     * Test delete.
     *
     * @return void
     */
    public function test_delete_logical()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->delete('/api/users/delete/100');
 
        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ]);
    }

}
