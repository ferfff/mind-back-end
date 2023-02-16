<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('isAdmin', ['only' => ['index', 'update', 'show' ]]);
    }

    /**
     * @OA\Get(
     * path="/api/users",
     * summary="get all users",
     * description="Get user info",
     * operationId="getAllUsers",
     * tags={"auth"},
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
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;

        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            Log::error('Cannot edit another user: '. $user);
            return response()->json([
                'status' => 'error',
                'message' => 'There is no possible to update another user',
            ], 401);
        }

        $user = User::whereId($id)->update($request->all());

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
            'user' => $user,
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/user/{id}",
     * summary="users",
     * description="Get user info",
     * operationId="getUserById",
     * tags={"auth"},
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
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Selection::whereId($id)->update($request->all());

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
            'user' => $user,
        ]);
    }
}
