<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

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
     *       @OA\Property(property="users", type="json"),
     *     )
     *    )
     * )
     */
    public function index() {
        $accounts = Account::where('active', true);
        return response()->json([
            'status' => 'success',
            'accounts' => $accounts,
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
    public function create($id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'customer' => 'required|string|max:100',
        ]);

        $newAccount = Account::create([
            'name' => $request->name,
            'customer' => $request->customer,
            'responsible' => $request->responsible,
            'active' => $request->english_level,
            'created_at' => now(),
            'active' => true,
        ]);

        if (!$newAccount) {
            Log::error('Issue creating new account '. Auth::user());
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to create this user',
            ], 401);
        }

        Log::info('New account created by'. Auth::user());
        
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
     *    description="Get user success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="user", type="json"),
     *     )
     *    )
     * )
     */
    public function show($id)
    {
        $user = Account::find($id)->where('active', 1);

        if (!$user) {
            Log::error('Error requesting information by'. Auth::user() . ' of ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to get this user info',
            ], 401);
        }

        Log::info('Requested information by'. Auth::user() . ' of ' . $id);
        return response()->json([
            'status' => 'success',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/accounts/update",
     * summary="Update account",
     * description="Update account only admins",
     * operationId="authUpdateaccount",
     * tags={"accounts"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="name", type="string", format="string"),
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
        $user = Account::whereId($id)->update($request->all());

        if (!$user) {
            Log::error('Error requesting information by'. Auth::user() . ' of ' . $id);
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to get this user info',
            ], 401);
        }

        Log::info('Requested information by'. Auth::user() . ' of ' . $id);
        return response()->json([
            'status' => 'success',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/accounts/destroy",
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
            'message' => 'User deleted successfully',
        ]);
    }

    public function addUsersToAccount(Request $request, $id)
    {
        
    }
}
