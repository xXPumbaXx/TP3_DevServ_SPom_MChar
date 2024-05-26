<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\LanguageResource;
use App\Repository\Eloquent\UserRepository;
use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

   /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Get a user by ID",
     *     description="Retrieve a user by their ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user->tokenCan('create')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $user = $this->userRepository->getById($id); // User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (auth()->user()->id !== (int)$id) {
            return response()->json(['error' => "Can't show user info from another user!"], 403);
        }

        return response()->json(['user' => $user], 200);
    }

    // https://dev.to/shanisingh03/how-to-change-password-in-laravel-9-1fcg
    /**
    * @OA\Post(
    *     path="/users/update-password",
    *     summary="Update user password",
    *     description="Update the password of the authenticated user.",
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="old_password",
    *                     type="string",
    *                     description="The old password",
    *                     example="old_password"
    *                 ),
    *                 @OA\Property(
    *                     property="new_password",
    *                     type="string",
    *                     description="The new password",
    *                     example="new_password"
    *                 ),
    *                 @OA\Property(
    *                     property="new_password_confirmation",
    *                     type="string",
    *                     description="Confirmation of the new password",
    *                     example="new_password"
    *                 ),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Password changed successfully"
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Old password doesn't match or Forbidden"
    *     ),
    *     security={{"bearerAuth":{}}}
    * )
    */
    public function updatePassword($id, Request $request)
    {
        if(auth()->user() === null){
            return response()->json(['error' => "Must be logged in to make changes."], 403);
        }

        if((int)$id !== auth()->user()->id){
            return response()->json(['error' => "Can't change the credentials of another user!"], 422);
        }

        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        #Match The Old Password
        if(!Hash::check($request->old_password , auth()->user()->password)){
            return response()->json(['error' => "Old password doesn't match!"], 422);
        }

        $this->userRepository->update(Auth::id(), ['password' => bcrypt($request->new_password)]);

        return response()->json(['message' => "Password changed successfully!"], 200);
    }
}
