<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->hasHeader('Authorization')) {
            $token = str_replace('Bearer ', '', $this->header('Authorization'));
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken) {
                Auth::setUser($accessToken->tokenable);
            }
        }
        return !Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'number' => ['required', 'string', 'min:10', 'max:10', 'unique:users,number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'fcm_token' => ['sometimes', 'string']
        ];
    }

}
