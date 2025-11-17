<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AnimateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */

/**
 * @OA\Get(
 *     path="/api/hosts",
 *     tags={"Hosts"},
 *     summary="Liste des hosts",
 *     description="Retrieve a list of all hosts available in the system.",
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="hosts retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                      @OA\Property(property="id", type="integer", example="1"),
 *                      @OA\Property(property="nom", type="string", example="elgarrai"),
 *                      @OA\Property(property="prenom", type="string", example="hamza"),
 *                      @OA\Property(property="email", type="string", example="hamza@exemple.com"),
 *                      @OA\Property(property="password", type="string", example="12345678"),
 *                      @OA\Property(property="role", type="string", example="animateur"),

 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function index()
    {
        $hosts = User::where('role', 'animateur')->get();
        if (!$hosts) {
            return response()->json([
                'success' => false,
                'message' => 'no host found'
            ],);
        }

         return response()->json([
             'success' => true,
             'message' => 'List of hosts',
             'data' => $hosts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

/**
 * @OA\Post(
 *     path="/api/hosts",
 *     tags={"Hosts"},
 *     summary="Add Host EndPoint",
 *     
 *        
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom","prenom","email","password"},
 *             @OA\Property(property="nom", type="string", example="elgarrai"),
 *             @OA\Property(property="prenom", type="string", example="Hamza"),
 *             @OA\Property(property="email", type="string", example="Hamza@gmail.com"),
 *             @OA\Property(property="password", type="string", example="12345678"),
 *             @OA\Property(property="role", type="string", example="animateur"),
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
 *             @OA\Property(property="message", type="string", example="host created successfully")
 *         )
 *     )
 * 
 * )
 */
    public function store(StoreUserRequest $request)
    {
        $host = User::create([
            "nom"=>$request->nom,
            "prenom"=>$request->prenom,
            "email"=>$request->email,
            "password"=>$request->password,
            "role"=>"animateur",
        
        ]);

        return response()->json([
        'success' => true,
        'message' => 'animateur created.',
        'data' => $host
    ]);
    }

    /**
     * Display the specified resource.
     */

/**
 * @OA\Get(
 *     path="/api/hosts/{id}",
 *     tags={"Hosts"},
 *     summary="one host details",
 *     description="Retrieve a host.",
 *      @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the host to retriev",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="host retrieved successfully"),
 *              @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                      @OA\Property(property="id", type="integer", example="1"),
 *                      @OA\Property(property="nom", type="string", example="elgarrai"),
 *                      @OA\Property(property="prenom", type="string", example="hamza"),
 *                      @OA\Property(property="email", type="string", example="hamza@exemple.com"),
 *                      @OA\Property(property="password", type="string", example="12345678"),
 *                      @OA\Property(property="role", type="string", example="animateur"),

 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function show($id)
    {
        $host = User::where('id', $id)->where('role', 'animateur')->first();

          if (!$host) {
             return response()->json([
                 'success' => false,
                 'message' => 'Host not found'
             ]);
         }

             return response()->json([
              'success' => true,
              'message' => 'Host details retrieved successfully.',
              'data' => $host
               ]);
           
    }

    /**
     * Update the specified resource in storage.
     */

/**  @OA\Put(
 *     path="/api/host/{id}",
 *     summary="Update an existing host",
 *     description="Update a host by ID.",
 * 
 *     tags={"Hosts"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the host to update",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="host update payload",
 *         @OA\JsonContent(
 *             required={"nom","prenom","email","password"},
 *             @OA\Property(property="nom", type="string", example="elgarrai"),
 *             @OA\Property(property="prenom", type="string", example="Hamza"),
 *             @OA\Property(property="email", type="string", example="Hamza@gmail.com"),
 *             @OA\Property(property="password", type="string", example="12345678"),
 *             @OA\Property(property="role", type="string", example="animateur"),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="host updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="host updated successfully")
 *         )
 *  
 *    )
 * )
 */
    public function update(UpdateUserRequest $request, string $id)
    {
        $host = User::where('id', $id)->where('role', 'animateur')->first();

        $host->nom = $request->nom ?? $request->nom;
        $host->prenom = $request->prenom ?? $request->prenom;
        $host->email = $request->email ?? $request->email;
        $host->password = $request->password ?? $request->password;

        $host->update();

        return response()->json([
              'success' => true,
              'message' => 'Host updated successfully.',
              'data' => $host
               ]);
        



    }

    /**
     * Remove the specified resource from storage.
     */

/** @OA\Delete(
 *     path="/api/hosts/{id}",
 *     summary="Delete a host",
 *     description="Delete an existing host by its ID.",
 *     tags={"Hosts"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the host to delete",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="host deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="host deleted successfully")
 *         )
 *  
 *    )
 *)
*/
    public function destroy($id)
    {
        $host = User::where('id', $id)->where('role', 'animateur')->first();
        if (!$host) {
             return response()->json([
                 'success' => false,
                 'message' => 'Host not found'
             ]);
         }

         $host->delete();
         return response()->json([
                 'success' => true,
                 'message' => 'Host deleted successfully'
             ]);
        
    }
}
