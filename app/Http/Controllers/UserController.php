<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


/**
 * @OA\Get(
 *     path="/api/users",
 *     tags={"Users"},
 *     summary="Liste des users",
 *     description="Retrieve a list of all users available in the system.",
 *     @OA\Response(
 *         response=200,
 *         description="Succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="users retrieved successfully")
 *         )
 *     )
 * )
 */
    public function index()
    {
        $user = User::all();

        return [
            "user liste" => $user
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
/**
 * @OA\Post(
 *     path="/api/users",
 *     tags={"Users"},
 *     summary="Add User EndPoint",
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
 *             @OA\Property(property="message", type="string", example="user created successfully")
 *         )
 *     )
 * 
 * )
 */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            "nom"=>$request->nom,
            "prenom"=>$request->prenom,
            "email"=>$request->email,
            "password"=>$request->password,
            "role"=>$request->role,
        
        ]);

        return response()->json([
        'success' => true,
        'message' => 'user created.',
        'data' => $user
    ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


/**  @OA\Put(
 *     path="/api/users/{id}",
 *     summary="Update an existing user",
 *     description="Update a user by ID.",
 * 
 *     tags={"Users"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to update",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="user update payload",
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
 *         description="user updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="user updated successfully")
 *         )
 *  
 *    )
 * )
 */
 public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;

        $user->update();

        return response()->json([
        'success' => true,
        'message' => 'user updated.',
        'data' => $user
    ]);
        




    }

    /**
     * Remove the specified resource from storage.
     */

/** @OA\Delete(
 *     path="/api/users/{id}",
 *     summary="Delete a user",
 *     description="Delete an existing user by its ID.",
 *     tags={"Users"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to delete",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="user updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="timestamp", type="string", example="2025-10-13 11:45:00"),
 *             @OA\Property(property="message", type="string", example="user deleted successfully")
 *         )
 *  
 *    )
 *)
*/
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

         return response()->json([
        'success' => true,
        'message' => 'user deleted successfully.',
        
    ]);
    }
}
