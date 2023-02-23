<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $account;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'id' => 1001,
            'name' => 'User fake',
            'password' => 'passfake',
            'email' => 'fakeuser@email.com',
            'english_level' => 'B2',
            'knowledge' => 'knowledge',
            'link_cv' => 'link_cv.com.mx',
        ]);

        $this->account = Account::factory()->create([
            'id' => 2000,
            'name' => 'account fake 2',
            'customer' => 'customer fake 2',
            'responsible' => 1001,
        ]);
    }
    
    /**
     * Accounts test unit for index.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->actingAs($this->user)
                        ->withSession(['banned' => false])
                        ->get('/api/accounts/index');
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'accounts',
            ]);
    }

    /**
     * Accounts test unit for create.
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->actingAs($this->user)
                        ->withSession(['banned' => false])
                        ->post('/api/accounts/create', [
                            'name' => 'account test',
                            'customer' => 'customer test',
                            'responsible' => 1001,
                            'created_at' => now(),
                            'active' => true,
                        ]);
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'message' => 'Account created successfully',
                'account' => [
                    'id' => 2001,
                    'name' => 'account test',
                    'customer' => 'customer test',
                    'responsible' => [
                        [ 'id' => 1001, 'name' => 'User fake', ]
                    ],
                ],
            ]);
    }

    /**
     * Accounts test unit for show.
     *
     * @return void
     */
    public function test_show_correct()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->get('/api/accounts/show/2000');
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'accounts' => [
                    'id' => 2000,
                    'members' => [],
                    'name' => 'account fake 2',
                    'customer' => 'customer fake 2',
                    'responsible' => [
                        ['id' => 1001, 'name' => 'User fake'],
                    ]
                ],
            ]);
    }

    /**
     * Accounts test unit for show fail.
     *
     * @return void
     */
    public function test_show_account_doesnt_exist()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->get('/api/accounts/show/2001');
 
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'There is no possible to get this account info',
            ]);
    }

    /**
     * Accounts test unit for update.
     *
     * @return void
     */
    public function test_update()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->put('/api/accounts/update/2000', [
                            'name' => 'new fake name',
                            'customer' => 'new fake customer',
                            'responsible' => 1001,
                         ]);
 
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'account' => [
                    'id' => 2000,
                    'members' => [],
                    'name' => 'new fake name',
                    'customer' => 'new fake customer',
                    'responsible' => [
                        ['id' => 1001, 'name' => 'User fake'],
                    ]
                ],
            ]);
    }

    /**
     * Accounts test unit for update.
     *
     * @return void
     */
    public function test_update_account_doesnt_exist()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->put('/api/accounts/update/3000', [
                            'name' => 'new fake name',
                            'customer' => 'new fake customer',
                            'responsible' => 1001,
                         ]);
 
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'There is no possible to get this user info',
            ]);
    }

    /**
     * Accounts test unit for delete.
     *
     * @return void
     */
    public function test_destroy()
    {
        $response = $this->actingAs($this->user)
                         ->withSession(['banned' => false])
                         ->delete('/api/accounts/delete/2000');
 
        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'message' => 'Account deleted successfully',
            ]);
    }

    /**
     * Accounts test unit add users to account.
     *
     * @return void
     */
    public function test_add_users_to_account()
    {
        User::factory()->create([
            'id' => 1002,
            'name' => 'Member fake',
            'password' => 'passmemberfake',
            'email' => 'fakememer@email.com',
            'english_level' => 'A2',
            'knowledge' => 'knowledge member',
            'link_cv' => 'link_member_cv.com.mx',
        ]);

        $response = $this->actingAs($this->user)
                    ->withSession(['banned' => false])
                    ->put('/api/accounts/add_users/2000', [
                        'id' => 1002,
                        'start_date' => '2023-03-01',
                        'end_date' => '2024-03-01',
                    ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'message' => 'Account updated successfully',
                'account' => [
                    'id' => 2000,
                    'members' => [
                        ['id' => 1002, 'name' => 'Member fake']
                    ],
                    'name' => 'account fake 2',
                    'customer' => 'customer fake 2',
                    'responsible' => [
                        ['id' => 1001, 'name' => 'User fake'],
                    ]
                ],
            ]);
    }

    /**
     * Accounts test unit add users to account.
     *
     * @return void
     */
    public function test_add_users_to_account_user_already_in_account()
    {
        User::factory()->create([
            'id' => 1002,
            'name' => 'Member fake',
            'password' => 'passmemberfake',
            'email' => 'fakememer@email.com',
            'english_level' => 'A2',
            'knowledge' => 'knowledge member',
            'link_cv' => 'link_member_cv.com.mx',
        ]);

        $this->actingAs($this->user)
                    ->withSession(['banned' => false])
                    ->put('/api/accounts/add_users/2000', [
                        'id' => 1002,
                        'start_date' => '2023-03-01',
                        'end_date' => '2024-03-01',
                    ]);

        $response = $this->actingAs($this->user)
                    ->withSession(['banned' => false])
                    ->put('/api/accounts/add_users/2000', [
                        'id' => 1002,
                        'start_date' => '2023-03-01',
                        'end_date' => '2024-03-01',
                    ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'There is no possible to add this user',
            ]);
    }

    /**
     * Accounts test unit remove users from account.
     *
     * @return void
     */
    public function test_remove_users_from_account()
    {
        User::factory()->create([
            'id' => 1003,
            'name' => 'Member fake3',
            'password' => 'passmemberfake3',
            'email' => 'fakememer3@email.com',
            'english_level' => 'A2',
            'knowledge' => 'knowledge member3',
            'link_cv' => 'link_member_cv3.com.mx',
        ]);

        $response = $this->actingAs($this->user)
                    ->withSession(['banned' => false])
                    ->put('/api/accounts/add_users/2000', [
                        'id' => 1003,
                        'start_date' => '2023-03-01',
                        'end_date' => '2024-03-01',
                    ]);

        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->put('/api/accounts/remove_users/2000', [
                'userstoremove' => '1003',
            ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'message' => 'Account updated successfully',
                'account' => [
                    'id' => 2000,
                    'members' => [],
                    'name' => 'account fake 2',
                    'customer' => 'customer fake 2',
                    'responsible' => [
                        ['id' => 1001, 'name' => 'User fake'],
                    ]
                ],
            ]);
    }

    /**
     * Accounts test unit fail remove users from account.
     *
     * @return void
     */
    public function test_remove_users_from_account_user_is_not_linked()
    {
        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->put('/api/accounts/remove_users/2000', [
                'userstoremove' => '1004',
            ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'status' => 'error',
                'message' => 'There is no possible to delete this users from account',
            ]);
    }

    /**
     * Accounts test unit get users accounts log.
     *
     * @return void
     */
    public function test_filter()
    {
        User::factory()->create([
            'id' => 1005,
            'name' => 'Member fake5',
            'password' => 'passmemberfake5',
            'email' => 'fakememer3@email.com',
            'english_level' => 'A2',
            'knowledge' => 'knowledge member5',
            'link_cv' => 'link_member_cv5.com.mx',
        ]);

        $response = $this->actingAs($this->user)
                    ->withSession(['banned' => false])
                    ->put('/api/accounts/add_users/2000', [
                        'id' => 1005,
                        'start_date' => '2023-01-25',
                        'end_date' => '2023-01-28',
                    ]);

        $response = $this->actingAs($this->user)
            ->withSession(['banned' => false])
            ->post('/api/accounts/filter', [
                'account_id' => '2000',
                'user_id' => '1005',
            ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'status' => 'success',
                'total' => 1,
                'log' => [
                    [
                        'accountname' => 'account fake 2',
                        'start_date' => '2023-01-25',
                        'end_date' => '2023-01-28',
                        'username' => 'Member fake5',
                    ]
                ],
            ]);
    }
}
