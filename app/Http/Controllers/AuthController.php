<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('isSuperadmin', ['only' => ['register']]);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Log in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Loggin success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="user", type="json"),
     *       @OA\Property(property="authorization", type="json")
     *     )
     *    )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
       $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            Log::info('Try to login by unauthorized user');
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Create new user by super admins",
     * description="Create a new user, only super admins",
     * operationId="authRegister",
     * tags={"auth"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="name", type="string", format="string"),
     *       @OA\Property(property="knowledge", type="string", format="string"),
     *       @OA\Property(property="english_level", type="string", format="string"),
     *       @OA\Property(property="link_cv", type="string", format="url"),
     *       @OA\Property(property="role", type="string", format="string"),
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
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = Auth::user();
        $roleCreated = $request->role;
        
        if ($user->isAdmin() && $roleCreated == env('ROLE_SUPERADMIN')) {
            Log::info('Tried to create a super admin by '. Auth::user());
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized for this action',
            ], 401);
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'english_level' => $request->english_level,
            'knowledge' => $request->knowledge,
            'link_cv' => $request->link_cv,
            'role' => $roleCreated,
        ]);

        if (!$newUser) {
            Log::error('Issue creating new user '. Auth::user());
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to create this user',
            ], 401);
        }

        Log::info('New user created by'. Auth::user());
        
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $newUser,
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Log out",
     * description="Delete bearer token",
     * operationId="authLogout",
     * tags={"auth"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *    description="Logout",
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Logout succesfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string")
     *     )
     *    )
     * )
     * )
     */
    public function logout()
    {
        Log::info('Logout by '. Auth::user());

        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);        
    }

    /**
     * @OA\Post(
     * path="/api/refresh",
     * summary="Refresh Token",
     * description="Refresh bearer token",
     * operationId="authrefresh",
     * tags={"auth"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     *    response=200,
     *    description="refresh succesfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="user", type="string"),
     *       @OA\Property(property="authorization", type="json")
     *     )
     *    )
     * )
     * )
     */
    public function refresh()
    {
        Log::info('Token Refreshed by'. Auth::user());

        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
