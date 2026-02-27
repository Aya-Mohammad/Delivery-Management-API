<?php

namespace App\Http\Requests\Orders;

use App\Permissions\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan(Abilities::USER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pay_method' => ['required', 'string', 'in:cash,credit'],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'location' => ['required', 'array'],
            'location.longitude' => ['required', 'numeric'],
            'location.latitude' => ['required', 'numeric'],
            'location.city' => ['required', 'string'],
            'location.street' => ['required', 'string'],
            'id' => ['sometimes', 'exists:orders,id']
        ];
    }
}
