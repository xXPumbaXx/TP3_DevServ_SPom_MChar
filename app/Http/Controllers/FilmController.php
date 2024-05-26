<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Repository\Eloquent\FilmRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{   
    private FilmRepository $filmRepository;

    public function __construct(FilmRepository $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }

    /**
    * @OA\Post(
    *     path="/films",
    *     summary="Create a new film",
    *     description="Create a new film entry.",
    *     @OA\RequestBody(
    *         required=true,
    *         description="Film data",
    *         @OA\JsonContent(
    *             @OA\Property(property="title", type="string", example="ALCHEMY OF DREAMS 2"),
    *             @OA\Property(property="description", type="string", example="A Captivating Journey of a Dreamer And a Magician who must Unlock the Secrets of the Universe in a Parallel Dimension"),
    *             @OA\Property(property="release_year", type="integer", example=2024),
    *             @OA\Property(property="length", type="integer", example=120),
    *             @OA\Property(property="rating", type="string", example="PG"),
    *             @OA\Property(property="special_features", type="string", example="Trailers,Deleted Scenes"),
    *             @OA\Property(property="image", type="string", example="alchemy_of_dreams.jpg"),
    *             @OA\Property(property="language_id", type="integer", example=1),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Film created successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Film created"),
    *             @OA\Property(property="film", type="object",
    *                 @OA\Property(property="title", type="string"),
    *                 @OA\Property(property="description", type="string"),
    *                 @OA\Property(property="release_year", type="integer"),
    *                 @OA\Property(property="length", type="integer"),
    *                 @OA\Property(property="rating", type="string"),
    *                 @OA\Property(property="special_features", type="string"),
    *                 @OA\Property(property="image", type="string"),
    *                 @OA\Property(property="language_id", type="integer"),
    *                 @OA\Property(property="updated_at", type="string", format="date-time"),
    *                 @OA\Property(property="created_at", type="string", format="date-time"),
    *                 @OA\Property(property="id", type="integer"),
    *             ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Validation errors"
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

        if ($user->role_id !== 2) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'release_year' => 'required|integer',
            'length' => 'required|integer',
            'rating' => 'required|string|max:5|in:G,PG,PG-13,R,NC-17',
            'special_features' => 'nullable|string|max:200',
            'image' => 'required|string',
            'language_id' => 'required|exists:languages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $film = new Film();
        $film->title = $request->title;
        $film->description = $request->description;
        $film->release_year = $request->release_year;
        $film->length = $request->length;
        $film->rating = $request->rating;
        $film->special_features = $request->special_features;
        $film->image = $request->image;
        $film->language_id = $request->language_id;

        $film->save();

        return response()->json(['message' => 'Film created', 'film' => $film], 201);
    }

    /**
    * @OA\Put(
    *     path="/films/{id}",
    *     summary="Update a film by ID",
    *     description="Update an existing film entry by its ID.",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the film",
    *         required=true,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         description="Updated film data",
    *         @OA\JsonContent(
    *             @OA\Property(property="title", type="string", example="New Title"),
    *             @OA\Property(property="description", type="string", example="New Description"),
    *             @OA\Property(property="release_year", type="integer", example=2025),
    *             @OA\Property(property="length", type="integer", example=130),
    *             @OA\Property(property="rating", type="string", example="PG-13"),
    *             @OA\Property(property="special_features", type="string", example="New Special Features"),
    *             @OA\Property(property="image", type="string", example="new_image.jpg"),
    *             @OA\Property(property="language_id", type="integer", example=2),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Film updated successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Film updated"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Validation errors"
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Film not found"
    *     ),
    *     security={{"bearerAuth":{}}}
    * )
    */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->tokenCan('create')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        if ($user->role_id !== 2) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $film = $this->filmRepository->getById($id); // Film::find($id);
        if (!$film) {
            return response()->json(['error' => 'Film not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:50',
            'description' => 'string',
            'release_year' => 'integer',
            'length' => 'integer',
            'rating' => 'string|max:5|in:G,PG,PG-13,R,NC-17',
            'special_features' => 'nullable|string|max:200',
            'image' => 'string|max:40',
            'language_id' => 'exists:languages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $film->save();

        return response()->json(['message' => 'Film updated'], 200);
    }
    /**
     * @OA\Delete(
     *     path="/films/{id}",
     *     summary="Delete a film by ID",
     *     description="Delete an existing film entry by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the film",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Film deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user->tokenCan('create')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        if ($user->role_id !== 2) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $film = $this->filmRepository->getById($id); //Film::find($id);
        if (!$film) {
            return response()->json(['message' => 'Film not found'], 404);
        }

        // Suppression des critiques liées
        $film->critics()->delete();

        // Suppression des acteurs liés
        $film->actors()->detach();

        $film->delete();

        return response()->json(['message' => 'Film deleted'], 200);
    }
}
