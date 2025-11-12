<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            "nom" =>"required|string|max:255",
            "prenom" =>"required|string|max:255",
            "email" => "required|email|unique:users",
            "password"=>"required|string|min:8" ,
        
        ];
    }

    public function messages(){
        return [
            "name.required" => "name obligatoire",
            "name.max" => "name too long",
            "email.required" => "email obligatoire",
            "email.email"=> "email format not correct",
            "password.required" => "password obligatoire",
            "password.min" => "your password is too short"
        ];
    }
}
