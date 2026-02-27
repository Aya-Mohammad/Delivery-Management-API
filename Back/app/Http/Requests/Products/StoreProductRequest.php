<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use App\Permissions\Abilities;
class StoreProductRequest extends FormRequest
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
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'category_name' => ['required', 'string', 'exists:categories,name'],
            'shop_name' => ['required', 'string', 'exists:shops,name'],
            'discount' => ['required', 'numeric', 'between:0,100'],
        ];
    }
}
