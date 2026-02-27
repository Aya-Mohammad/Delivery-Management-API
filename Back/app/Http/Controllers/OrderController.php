<?php
namespace App\Http\Controllers;

use App\Http\Requests\Driver\PickOrderRequest;
use App\Http\Requests\Orders\CancelOrderRequest;
use App\Http\Requests\Orders\HistoryRequest;
use App\Http\Requests\Orders\ShowOrderRequest;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Requests\Orders\UpdateOrderStatusRequest;
use App\Http\Requests\Driver\ShowAcceptedOrdersRequest;
use App\Notifications\EmailVerificationNotification;
use App\Services\FirebaseService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
class OrderController extends APIController
{
    protected $firebase;
    protected $service;
    protected const STATUSTONAME = [
        '0' => 'peding',
        '1' => 'delivering',
        '2' => 'delivered',
        '3' => 'received'
    ];

    public function __construct(FirebaseService $firebase, OrderService $service)
    {
        $this->firebase = $firebase;
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $data = $this->service->index($request->user()->id);
        return $this->ok(
            $data['message'],
            $data['orders']
        );
    }
    public function show(ShowOrderRequest $request)
    {
        $data = $this->service->show($request->id);
        return $this->ok(
            __('order details sent'),
            $data
        );
    }
    public function showAcceptedOrders(ShowAcceptedOrdersRequest $request)
    {
        $data = $this->service->showAcceptedOrders($request->user()->id);
        return $this->ok(
            'driver orders sent',
            $data
        );
    }
    public function store(StoreOrderRequest $request)
    {
        $data = $this->service->store($request->id, $request->products, $request->user()->id, $request['location'], $request['pay_method']);
        return $this->ok(
            $data['message'],
            $data['data'],
            $data['status']
        );
    }
    public function cancel(CancelOrderRequest $request)
    {
        $message = $this->service->cancel($request->id);
        return $this->message(
            $message
        );
    }
    public function updateOrderStatus(UpdateOrderStatusRequest $request)
    {
        $data =$this->service->updateOrderStatus($request->id, $request->status);
        
        return $this->ok(
            __("order status updated successfully"),
            $data
        );
    }
    public function pick(PickOrderRequest $request)
    {
        $data = $this->service->pick($request->id, $request->user()->id);
        return $this->ok(
            $data['message'],
            $data['data'],
            $data['status']
        );
    }

    public function history(HistoryRequest $request)
    {
        $data = $this->service->history($request->user()->id, $request->user()->type);
        return $this->ok(
            __("User's orders sent successfully"),
            $data
        );
    }
}


