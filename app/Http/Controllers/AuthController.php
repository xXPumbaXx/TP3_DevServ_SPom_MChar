<?php

// https://dev.to/olodocoder/laravel-api-series-laravel-sanctum-setup-sign-up-login-and-logout-3kb

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/signup",
     *      tags={"AuthController"},
     *      summary="Add a new user to the database",
     *      @OA\Response(
     *          response=201,
     *          description="User added successfuly"),
     *          @OA\RequestBody(
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                          property="login",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="last_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="first_name",
     *                          type="string"
     *                      ),
     *                  )
     *              )
     *          )
     *       )
     *    )
     */
    public function register(Request $request){
        $data = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string|email:rfc,dns|unique:users,email',
            'last_name' => 'required|string',
            'first_name' => 'required|string'
        ]);

        $user = User::create([
            'login' => $data['login'],
            'password' => bcrypt($data['password']),
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name']
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response($res, 201);
    }

    /**
     * @OA\Post(
     *      path="/api/signin",
     *      tags={"AuthController"},
     *      summary="Log a user to the database",
     *      @OA\Response(
     *          response=201,
     *          description="User logged in successfuly"),
     *          @OA\RequestBody(
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      )
     *                  )
     *              )
     *          )
     *       )
     *    )
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'incorrect username or password'
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response($res, 201);
    }

    /**
     * @OA\Post(
     *      path="/api/signout",
     *      tags={"AuthController"},
     *      summary="Sign out a user to the database",
     *      @OA\Response(
     *          response=200,
     *          description="User signed out successfuly"),
     *          @OA\RequestBody(
     *              @OA\MediaType(
     *                  mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      )
     *                  )
     *              )
     *          )
     *       )
     *    )
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];

    }


}
