<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Http\Requests\StorePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
/**
 * @OA\Get(
 *     path="/api/podcasts",
 *     tags={"Podcasts"},
 *     summary="Liste des podcasts",
 *     description="Retrieve a list of all podcasts available in the system.",
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="podcasts retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                      @OA\Property(property="id", type="integer", example="1"),
 *                      @OA\Property(property="titre", type="string", example="test"),
 *                      @OA\Property(property="description", type="string", example="description test"),
 *                      @OA\Property(property="category", type="string", example="sport"),
 *                      @OA\Property(property="image_url", type="string", example="url//test"),

 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function index()
    {
        $podcast = Podcast::all();
        return response()->json([
        'success' => true,
        'message' => 'podcasts liste',
        'data' => $podcast
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */

/**
 * @OA\Post(
 *     path="/api/podcasts",
 *     tags={"Podcasts"},
 *     summary="Add Podcast EndPoint",
 *     
 *        
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"titre","description","category","image_url"},
 *             @OA\Property(property="titre", type="string", example="test"),
 *             @OA\Property(property="description", type="string", example="description test"),
 *             @OA\Property(property="category", type="string", example="sport"),
 *             @OA\Property(property="image_url", type="string", example="url//test"),
 *             
 *             
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="podcast created successfully")
 *         )
 *     )
 * 
 * )
 */
    public function store(StorePodcastRequest $request)
    {
        $user = Auth::user();

        

        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

        $podcast = $user->podcasts()->create([

            "titre"=> $request->titre,
            "description"=> $request->description,
            "category"=> $request->category,
            "image_url"=> $uploadedFileUrl
        ]);
        return response()->json([
        'success' => true,
        'message' => 'podcast created',
        'data' => $podcast
        ]);
    }

    /**
     * Display the specified resource.
     */

/**
 * @OA\Get(
 *     path="/api/podcasts/{id}",
 *     tags={"Podcasts"},
 *     summary="one podcasts details",
 *     description="Retrieve a podcast.",
 *      @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the podcast to retriev",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="podcast retrieved successfully"),
 *              @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                      @OA\Property(property="id", type="integer", example="1"),
 *                      @OA\Property(property="titre", type="string", example="test"),
 *                      @OA\Property(property="description", type="string", example="description test"),
 *                      @OA\Property(property="category", type="string", example="sport"),
 *                      @OA\Property(property="image_url", type="string", example="url//test"),

 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function show($id)
    {
        $podcast = Podcast::findOrFail($id);
        return response()->json([
        'success' => true,
        'message' => 'podcast',
        'data' => $podcast
    ]);

    }

    /**
     * Update the specified resource in storage.
     */
/**  @OA\Put(
 *     path="/api/podcasts/{id}",
 *     summary="Update an existing podcast",
 *     description="Update a podcast by ID.",
 * 
 *     tags={"Podcasts"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the podcast to update",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="podcast update payload",
 *         @OA\JsonContent(
 *             required={"titre","description","category","image_url"},
 *             @OA\Property(property="titre", type="string", example="test"),
 *             @OA\Property(property="description", type="string", example="description test"),
 *             @OA\Property(property="category", type="string", example="sport"),
 *             @OA\Property(property="image_url", type="string", example="url//test"),
 *             
 *             
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="user updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="podcast updated successfully")
 *         )
 *  
 *    )
 * )
 */
    public function update(UpdatePodcastRequest $request, $id)
    {
       
        $podcast = Podcast::findOrFail($id);
        
        $podcast->titre = $request->titre ?? $request->titre;
        $podcast->description = $request->description ?? $request->description;
        $podcast->category = $request->category ?? $request->category;
        
        if ($request->hasFile('image')) {
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $podcast->image_url = $uploadedFileUrl;
    }

        $podcast->update();

        return response()->json([
        'success' => true,
        'message' => 'podcast updated',
        'data' => $podcast
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */

/** @OA\Delete(
 *     path="/api/podcasts/{id}",
 *     summary="Delete a podcast",
 *     description="Delete an existing podcast by its ID.",
 *     tags={"Podcasts"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the podcast to delete",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="podcast deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="podcast deleted successfully")
 *         )
 *  
 *    )
 *)
*/
    public function destroy($id)
    {
        $user = Auth::user();
        $podcast = Podcast::findOrFail($id);

        $podcast->delete();
        return response()->json([
        'success' => true,
        'message' => 'podcast deleted',
        
    ]);
        
    }
}
