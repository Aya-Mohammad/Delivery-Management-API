<?php

namespace App\Http\Requests\Orders;

use App\Permissions\Abilities;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = Order::find($this->id);
        if ($order->status == '3')return false;
        if($order->driver_id != $this->user()->id)return false;
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
            'id' => ['required', 'exists:orders,id'],
            'status' => ['required', 'string', 'in:0,1,2,3']
        ];
    }
}
