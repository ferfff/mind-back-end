<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('isAdmin');
    }

    /**
     * @OA\Get(
     * path="/api/acounts",
     * summary="Get all accounts active",
     * description="Get account info",
     * operationId="getAllAccounts",
     * tags={"accounts"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Get users success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="accounts", type="json"),
     *     )
     *    )
     * )
     */
    public function index() {
        $accounts = Account::all()->reject(function ($account) {
            return $account->active === false;
        })->map(function ($account) {
            return $account;
        });
        
        $result = [];
        foreach($accounts as $account) {
            $result[$account->id]['name'] = $account->name;
            $result[$account->id]['responsible'] = $account->responsible()->get()
                ->map->only(['id', 'name']);
        }

        return response()->json([
            'status' => 'success',
            'accounts' => $result,
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/accounts/create",
     * summary="Create new account",
     * description="Create new account, only admins",
     * operationId="authAccountCreate",
     * tags={"accounts"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass account credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", format="name", example="name Account"),
     *       @OA\Property(property="customer", type="string", format="string"),
     *       @OA\Property(property="responsible", type="integer", format="integer"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="User Created succesfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="user", type="json")
     *     )
     *    )
     * )
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'customer' => 'required|string|max:100',
            'responsible' => 'required|integer',
        ]);
        
        $newAccount = Account::create([
            'name' => $request->name,
            'customer' => $request->customer,
            'responsible' => $request->responsible,
            'created_at' => now(),
            'active' => true,
        ]);

        $user = Auth::user();

        if (!$newAccount) {
            Log::error('Issue creating new account '. $user->id);
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to create this user',
            ], 401);
        }

        Log::info('New account created by'. $user->id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully',
            'account' => $newAccount,
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/accounts/{id}",
     * summary="Get Account by id",
     * description="Get account info",
     * operationId="getAccountInfo",
     * tags={"accounts"},
     * security={{"bearerAuth":{}}},
     * @OA\Property(property="id", type="integer"),
     * @OA\Response(
     *    response=200,
     *    description="Get account success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="account", type="json"),
     *     )
     *    )
     * )
     */
    public function show($id)
    {
        $account = Account::where('id', $id)->first();

        if (!$account) {
            Log::error('Error requesting information by'. Auth::user() . ' of ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to get this account info',
            ], 401);
        }

        Log::info('Requested information by'. Auth::user() . ' of ' . $id);
        $result = $this->getAccountResult($id);

        return response()->json([
            'status' => 'success',
            'accounts' => $result,
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/accounts/update/{id}",
     * summary="Update account",
     * description="Update account only admins",
     * operationId="authUpdateaccount",
     * tags={"accounts"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Accounts values",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", format="name", example="name Account"),
     *       @OA\Property(property="customer", type="string", format="string"),
     *       @OA\Property(property="responsible", type="integer", format="integer"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="User Created succesfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="user", type="json")
     *     )
     *    )
     * )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'customer' => 'required|string|max:100',
            'responsible' => 'required|integer',
        ]);

        $account = Account::whereId($id)->update($request->all());

        if (!$account) {
            Log::error('Error requesting information by'. Auth::user() . ' of ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to get this user info',
            ], 401);
        }

        Log::info('Requested information by'. Auth::user() . ' of ' . $id);
        return response()->json([
            'status' => 'success',
            'account' => Account::where('id', $id)->get(),
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/accounts/destroy/{id}de",
     * summary="Delete acount",
     * description="Delete acount logically",
     * operationId="authAccountDelete",
     * tags={"accounts"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     *    response=200,
     *    description="User deleted succesfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string")
     *     )
     *    )
     * )
     * )
     */
    public function destroy($id)
    {
        Account::where('id', $id)
            ->update(['active' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'Account deleted successfully',
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/accounts/add_users/{id}",
     * summary="Add account members",
     * description="Add account members only admins",
     * operationId="authAddaccountMembers",
     * tags={"accounts"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Accounts values",
     *    @OA\JsonContent(
     *       @OA\Property(property="userstoadd", type="json", example="{"1":{"start_date":"2023-02-25","end_date":"2023-02-28"}"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="User Created succesfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="user", type="json")
     *     )
     *    )
     * )
     * )
     */
    public function addUsersToAccount(Request $request, $id)
    {
        $users = json_decode($request->userstoadd, true);
        
        DB::transaction(function() use ($users, $id) {
            foreach($users as $idUser => $usertoadd) {
                $user = User::find($idUser);
                if ($user) {
                    $usersAccounts = DB::table('users_accounts')
                    ->where('user_id', '=', $idUser)
                    ->where('account_id', '=', $id)
                    ->where('active', '=', true)
                    ->get();

                    if($usersAccounts) {
                        UserAccount::create([
                            'created_at' => now(),
                            'user_id' => $user->id,
                            'account_id' => $id,
                            'active' => true,
                            'start_date' => $usertoadd['start_date'],
                            'end_date' => $usertoadd['end_date'],
                        ]);
                    }
                }
            }
        });

        Log::info('Added members by'. Auth::user() . ' of ' . $id);
        $result = $this->getAccountResult($id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully',
            'accounts' => $result,
        ]);
    }

    public function removeUsersFromAccount(Request $request, $id)
    {
        $users = explode(',', $request->userstoremove);

        $deleted = DB::table('users_accounts')
            ->where('account_id', '=', $id)
            ->where('active', '=', true)
            ->whereIn('user_id', $users)
            ->update([
                'active' => false,
            ]);

        if(!$deleted) {
            Log::error('Error deleting '. $request->userstoremove . ' of account ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to delete this users from account',
            ], 401);
        }

        Log::info('Requested information by'. Auth::user() . ' of ' . $id);
        $result = $this->getAccountResult($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully',
            'accounts' => $result,
        ]);
    }

    private function getAccountResult($id)
    {
        $result = [];
        $account = Account::where('id', $id)->first();

        $result['id'] = $account->id;
        $result['name'] = $account->name;
        $result['responsible'] = $account->responsible()->get()
            ->map->only(['id', 'name']);
        $result['members'] = $account->users()->where('users_accounts.active', true)->get()
            ->map->only(['id', 'name']);

        return $result;
    }
}
