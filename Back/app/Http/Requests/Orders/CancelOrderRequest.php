<?php

namespace App\Http\Requests\Orders;

use App\Permissions\Abilities;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;
class CancelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->tokenCan(Abilities::ADMIN))
            return true;
        $order = Order::find($this->id);
        if(is_null($order))return true;
        if ($order->user_id != $this->user()->id)
            return false;
        return $order->status == '0';
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
