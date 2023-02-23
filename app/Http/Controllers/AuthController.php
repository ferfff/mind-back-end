<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     *       @OA\Property(property="authorization", type="object")
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
            ], Response::HTTP_UNAUTHORIZED);
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
     *       @OA\Property(property="user", type="object")
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

        $roleCreated = $request->role;
        
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'english_level' => $request->english_level,
            'knowledge' => $request->knowledge,
            'link_cv' => $request->link_cv,
            'role' => $roleCreated,
        ]);

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

}
