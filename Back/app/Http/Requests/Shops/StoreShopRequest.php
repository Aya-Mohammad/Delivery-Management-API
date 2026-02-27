<?php

namespace App\Http\Requests\Shops;

use Illuminate\Foundation\Http\FormRequest;
use App\Permissions\Abilities;
class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan(Abilities::ADMIN);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'arabic_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'arabic_description' => ['required', 'string'],
        ];
    }
}
