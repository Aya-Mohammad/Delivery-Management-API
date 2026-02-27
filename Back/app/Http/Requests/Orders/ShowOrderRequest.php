<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use App\Permissions\Abilities;
use App\Models\Order;
class ShowOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->tokenCan(Abilities::ADMIN)) {
            return true;
        }
        $order = Order::find($this->id);
        if(is_null($order))return true;
        if ($order->user_id == $this->user()->id) {
            return true;
        }
        if($this->user()->tokenCan(Abilities::DRIVER)){
            if($order->status=='0')return true;
            if($order->driver_id == $this->user()->id)return true;
        }
        return false;
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
