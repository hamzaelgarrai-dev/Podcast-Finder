<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Podcast;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Gate;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

/**
 * @OA\Get(
 *     path="/api/podcasts/{id}/episodes",
 *     tags={"Episode"},
 *     summary="Liste des episode pour a specific podcast",
 *     description="Retrieve a list of all episode for a specific podcast.",
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the podcast",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="episodes retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                      @OA\Property(property="id", type="integer", example="1"),
 *                      @OA\Property(property="titre", type="string", example="test"),
 *                      @OA\Property(property="description", type="string", example="description test"),
 *                      @OA\Property(property="fichier_audio", type="string", example="url//test"),
 *                      @OA\Property(property="podcast_id", type="integer", example="1"),

 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function index($id)
    {
        $podcast = Podcast::with('episodes')->find('$id');
        if (!$podcast) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found.'
            ],);
        }
        return response()->json([
            'success' => true,
            'message' => 'Episodes list retrieved successfully.',
            'data' => $podcast->episodes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

/**
 * @OA\Post(
 *     path="/api/podcasts/{id}/episodes",
 *     tags={"Episode"},
 *     summary="Add Episode to a podcast EndPoint",
 *     
 *        
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"titre","description","fichier_audio"},
 *             @OA\Property(property="titre", type="string", example="test"),
 *             @OA\Property(property="description", type="string", example="description test"),
 *             @OA\Property(property="fichier_audio", type="string", example="url//test"),
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
 *             @OA\Property(property="message", type="string", example="episode created successfully")
 *         )
 *     )
 * 
 * )
 */
    public function store(StoreEpisodeRequest $request , $id , Episode $episode)
    {
        $podcast= Podcast::find($id);
        if (!$podcast) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found.'
            ]);
        }

        Gate::authorize('create' , $episode);

        $uploadAudio= Cloudinary::upload($request->file('audio')->getRealPath(),['resource_type' => 'video'])->getSecurePath();

        $episode = $podcast->episodes()->create([
        'title' => $request->title,
        'description' => $request->description,
        "fichier_audio" => $request->fichier_audio

        ]);

        return response()->json([
        'success' => true,
        'message' => 'Episode created successfully.',
        'data' => $episode
    ]);


    }

    /**
     * Display the specified resource.
     */

/**
 * @OA\Get(
 *     path="/api/episodes/{id}",
 *     tags={"Episode"},
 *     summary="one episode details",
 *     description="Retrieve a episode.",
 *      @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the episode to retriev",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="episode retrieved successfully"),
 *              @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                      @OA\Property(property="id", type="integer", example="1"),
 *                      @OA\Property(property="titre", type="string", example="test"),
 *                      @OA\Property(property="description", type="string", example="description test"),
 *                      @OA\Property(property="fichier_audio", type="string", example="url//test"),
 *                      @OA\Property(property="image_url", type="string", example="url//test"),
 *                      @OA\Property(property="podcast_id", type="integer", example="1"),

 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function show($id)
    {
        $episode = Episode::find($id);
        if (!$episode) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found.'
            ],);
        }
        return response()->json([
            'success' => true,
            'message' => 'Episode retrieved successfully.',
            'data' => $episode
        ]);

    }

    /**
     * Update the specified resource in storage.
     */

/**  @OA\Put(
 *     path="/api/episodes/{id}",
 *     summary="Update an existing episode",
 *     description="Update an episode by ID.",
 * 
 *     tags={"Episode"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the episode to update",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="podcast update payload",
 *         @OA\JsonContent(
 *             required={"titre","description","fichier_audio"},
 *             @OA\Property(property="titre", type="string", example="test"),
 *             @OA\Property(property="description", type="string", example="description test"),
 *             @OA\Property(property="fichier_audio", type="string", example="url//test"),
 *             
 *             
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="episode updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="episode updated successfully")
 *         )
 *  
 *    )
 * )
 */
    public function update(UpdateEpisodeRequest $request, $id)
    {
        $episode= Episode::findOrFail($id);

        if (!$episode) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found.'
            ],);
        }

        $uploadAudio= Cloudinary::upload($request->file('audio')->getRealPath(),['resource_type' => 'video'])->getSecurePath();

        Gate::authorize('update' , $episode);

        $episode->titre = $request->titre;
        $episode->description = $request->description;
        $episode->fichier_audio = $uploadAudio;
        $episode->update();
        return response()->json([
            'success' => true,
            'message' => 'Episode updated successfully.',
            'data' => $episode
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */

/** @OA\Delete(
 *     path="/api/episodes/{id}",
 *     summary="Delete an episode",
 *     description="Delete an existing episode by its ID.",
 *     tags={"Episode"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the episode to delete",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="episode deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="episode deleted successfully")
 *         )
 *  
 *    )
 *)
*/
    public function destroy($id)
    {
        $episode= Episode::findOrFail($id);
        if (!$episode) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found.'
            ],);
        }
        $episode->delete();
        return response()->json([
            'success' => true,
            'message' => 'Episode deleted successfully.',
            
        ]);

    }
}
