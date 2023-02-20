<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('isAdmin', ['only' => ['index', 'update', 'show' ]]);
    }

    /**
     * @OA\Get(
     * path="/api/users/index",
     * summary="Get all users active",
     * description="Get all user information, only admins",
     * operationId="getAllUsers",
     * tags={"users"},
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
    public function index()
    {
        $users = User::where('active', true)->get();
        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/users/show/{id}",
     * summary="Get user by id",
     * description="Get any user info by id",
     * operationId="getUserById",
     * tags={"users"},
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
        $user = User::find($id);

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
     * path="/api/users/update/{id}",
     * summary="Update user",
     * description="Update user, only admins",
     * operationId="authUpdateUser",
     * tags={"users"},
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
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        
        $affected = DB::table('users')
                ->where('id', '=', $id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

        if (!$affected) {
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
                'name' => $request->name,
                'email' => $request->email
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/users/getinfo",
     * summary="Get user logged info",
     * description="get Info from your User",
     * operationId="authGetInfo",
     * tags={"users"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Info shown succesfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="user", type="json")
     *     )
     *    )
     * )
     * )
     */
    public function getinfo()
    {
        $user = Auth::user();
        Log::info('Requested information by'. Auth::user() . ' of ' . $user->id);

        return response()->json([
            'status' => 'success',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'english_level' => $user->english_level,
                'knowledge' => $user->knowledge,
                'link_cv' => $user->link_cv,
            ],
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/users/destroy/{id}",
     * summary="Delete user",
     * description="Delete user logically",
     * operationId="authDelete",
     * tags={"users"},
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
        DB::table('users')
                ->where('id','=',$id)
                ->update([
                    'active' => false,
                ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ]);
    }
}
