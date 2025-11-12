<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\loginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\PasswordReset;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordResetNotification;

/**
 * @OA\Info(
 *     title="Auth Api documentation",
 *     version="1.0",
 *     description="Documentation avec Swagger"
 * )
 *
 * @OA\Tag(
 *     name="Authentification",
 *     description=" documentation of the Auth method with the reset password"
 * )
 */



class AuthController extends Controller
{

    /**
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Register"},
 *     summary="Register EndPoint",
 *     
 *        
 *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom","prenom","email","password"},
     *             @OA\Property(property="nom", type="string", example="elgarrai"),
     *             @OA\Property(property="prenom", type="string", example="Hamza"),
     *             @OA\Property(property="email", type="string", example="Hamza@gmail.com"),
     *             @OA\Property(property="pasword", type="string", example="12345678"),
     *             
     *         )
     *     ),
     *     @OA\Response(response=201, description="user registred")
     * 
 * )
 */
    public function register(RegisterRequest $request){

        $user = User::create([
            "nom"=>$request->nom,
            "prenom"=>$request->prenom,
            "email"=>$request->email,
            "password"=>$request->password,
 
        ]);

       
        $token = $user->createToken($request->nom);

        return [

            'user' => $user,
            'token' => $token->plainTextToken
        ];

    }
     /**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Login"},
 *     summary="Login EndPoint",
 *     
 *        
 *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","token"},
     *             @OA\Property(property="email", type="string", example="hamza@emple.com"),
     *             @OA\Property(property="password", type="int", example="12345678"),
     *           
     *             
     *         )
     *     ),
     *     @OA\Response(response=201, description="user registred")
     * 
 * )
 */

    public function login(loginRequest $request){

         $user = User::where('email', $request->email)->first();

            if(!$user || !Hash::check($request->password , $user->password)){

                return [
                    "message" => "the credentials are incorrect"
                ];
            }
            $token = $user->createToken($user->nom);

              return [

            'user' => $user,
            'token' => $token->plainTextToken
            ];

    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();

        return [
                    "message" => "you are logged out"
                ];

    }

     /**
 * @OA\Post(
 *     path="/api/password/forget",
 *     tags={"Forget"},
 *     summary="Forget EndPoint",
 *     
 *        
 *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email",},
     *             @OA\Property(property="email", type="string", example="hamza@emple.com"),
     *             
     *           
     *             
     *         )
     *     ),
     *     @OA\Response(response=201, description="a confirmation code is sent to user")
     * 
 * )
 */
    public function forgetPassword(ForgetPasswordRequest $request){

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return[
                "message"=> "invalid email"
            ];
        }

        $resetpasswordToken = str_pad(random_int(1,9999),6, '0', STR_PAD_LEFT);

        if(!$userPasswordReset = PasswordResetToken::where('email', $user->email)->first()){

            PasswordResetToken::updateOrCreate(
                ["email"=> $user->email],
                ["token"=> $resetpasswordToken]
            );

        }

        $user->notify(
            new PasswordResetNotification(
                $user,
                $resetpasswordToken

            )
            );

            return [
                "message" => "a new email has been sent check your email"
            ];

    }

    /**
 * @OA\Post(
 *     path="/api/password/reset",
 *     tags={"Reset"},
 *     summary="Reset EndPoint",
 *     
 *        
 *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","token","password"},
     *             @OA\Property(property="email", type="string", example="hamza@emple.com"),
     *             @OA\Property(property="token", type="string", example="hamza@emple.com"),
     *             @OA\Property(property="password", type="int", example="12345678"),
     *           
     *             
     *         )
     *     ),
     *     @OA\Response(response=201, description="password reset succesfuly")
     * 
 * )
 */
    public function resetPassword(ResetPasswordRequest $request){

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return[
                "message"=> "invalid email"
            ];
        }
        $resetRequest = PasswordResetToken::where('email', $user->email)->first();

        if(!$resetRequest || $resetRequest->token != $request->token){
            return[
                "message"=>"invalid token"
            ];
        }

        $user->fill(['password'=>Hash::make($request->password)]);
        $user->save();
        $user->tokens()->delete();
        $resetRequest->delete();
        $token = $user->createToken($user->nom);

        return[
            "message"=> "password reset succesfuly",
            'user' => $user,
            'token' => $token->plainTextToken

        ];


    }
}
