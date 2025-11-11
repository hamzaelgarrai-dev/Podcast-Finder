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



class AuthController extends Controller
{
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
