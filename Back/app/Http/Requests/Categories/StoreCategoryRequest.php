<?php

namespace App\Http\Requests\Categories;

use App\Rules\Language;
use Illuminate\Foundation\Http\FormRequest;
use App\Permissions\Abilities;
class StoreCategoryRequest extends FormRequest
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
        ];
    }
}
