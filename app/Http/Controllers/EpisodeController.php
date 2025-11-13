<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Podcast;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

/**
 * @OA\Get(
 *     path="/api/podcasts/{id}/episodes",
 *     tags={"episode"},
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
    public function store(StoreEpisodeRequest $request , $id)
    {
        $podcast= Podcast::find($id);
        if (!$podcast) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found.'
            ]);
        }

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
    public function update(UpdateEpisodeRequest $request, $id)
    {
        $episode= Episode::findOrFail($id);

        if (!$episode) {
            return response()->json([
                'success' => false,
                'message' => 'Podcast not found.'
            ],);
        }

        $episode->titre = $request->titre;
        $episode->description = $request->description;
        $episode->fichier_audio = $request->fichier_audio;
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
