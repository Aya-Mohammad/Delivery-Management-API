<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class NumberLoginUserRequest extends FormRequest
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
            'number' => ['required', 'string', 'exists:users,number'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
