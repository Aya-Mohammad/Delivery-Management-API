<?php

namespace App\Http\Requests\Driver;

use App\Permissions\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class PickOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan(Abilities::DRIVER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:orders,id']
        ];
    }
}
