<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critic;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CriticController extends Controller
{
    /**
    * @OA\Post(
    *     path="/critics",
    *     summary="Create a new critic",
    *     description="Create a new critic entry.",
    *     @OA\RequestBody(
    *         required=true,
    *         description="Critic data",
    *         @OA\JsonContent(
    *             @OA\Property(property="score", type="number", example=8.5),
    *             @OA\Property(property="comment", type="string", example="J'ai vraiment apprécié ce film, l'intrigue était captivante et les acteurs étaient excellents."),
    *             @OA\Property(property="user_id", type="integer", example=1),
    *             @OA\Property(property="film_id", type="integer", example=1),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Critic created successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Critic created"),
    *             @OA\Property(property="critic", type="object",
    *                 @OA\Property(property="score", type="number"),
    *                 @OA\Property(property="comment", type="string"),
    *                 @OA\Property(property="user_id", type="integer"),
    *                 @OA\Property(property="film_id", type="integer"),
    *                 @OA\Property(property="updated_at", type="string", format="date-time"),
    *                 @OA\Property(property="created_at", type="string", format="date-time"),
    *                 @OA\Property(property="id", type="integer"),
    *             ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Validation errors or critic already exists"
    *     ),
    *     security={{"bearerAuth":{}}}
    * )
    */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->tokenCan('create')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|min:0|max:10',
            'comment' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'film_id' => 'required|exists:films,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Check if a critic already exists for the given film
        $existingCritic = Critic::where('film_id', $request->film_id)->first();
        if ($existingCritic) {
            return response()->json(['error' => 'Critic for this film already exist'], 400);
        }

        $critic = new Critic();
        $critic->score = $request->score;
        $critic->comment = $request->comment;
        $critic->user_id = $request->user_id;
        $critic->film_id = $request->film_id;

        $critic->save();

        return response()->json(['message' => 'Critic created', 'critic' => $critic], 201);
    }
}
