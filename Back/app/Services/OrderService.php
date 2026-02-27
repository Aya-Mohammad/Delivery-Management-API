<?php

namespace App\Services;
use App\Models\User;
use App\Models\Order;
use App\Traits\LocationFunctions;
use App\Traits\ProductsFunctions;
use App\Notifications\EmailVerificationNotification;
class OrderService
{
    use ProductsFunctions;
    use LocationFunctions;
    protected const STATUSTONAME = [
        '0' => 'peding',
        '1' => 'delivering',
        '2' => 'delivered',
        '3' => 'received'
    ];
    protected $firebase;
    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }
    public function index($id)
    {
        $user = User::find($id);
        if ($user->type == 'A') {
            $orders = Order::with(['user', 'location'])
                ->paginate(10);
            return [
                'message' => __('orders sent successfully'),
                'orders' => $orders
            ];
        }
        if ($user->type == 'D') {
            $orders = Order::with([
                'user',
                'driver',
                'location',
                'breakTable.product.images',
                'breakTable.product.shop'
            ])
                ->where('status', '=', '0')
                ->get();
            return [
                'message' => 'pending orders sent',
                'orders' => $orders
            ];
        }
        $orders = Order::with(['user', 'location', 'driver'])
            ->where('user_id', $id)
            ->where('status', '!=', '3')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return [
            'message' => __("User's orders sent successfully"),
            'orders' => $orders
        ];
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'driver.images',
            'location',
            'breakTable.product.images',
            'breakTable.product.shop'
        ])
            ->find($id);
        return ['order' => $order];
    }

    public function showAcceptedOrders($id)
    {
        $orders = Order::with([
            'user.images',
            'driver.images',
            'location',
            'breakTable.product.images',
            'breakTable.product.shop'
        ])
            ->where('driver_id', '=', $id)
            ->where('status', '!=', '3')
            ->get();
        return $orders;
    }

    public function cancel($id)
    {
        $order = Order::with('relations')->find($id);

        $this->cancelBookedProducts($order->relations);

        $message = $order->pay_method == 'cash' ? "order canceled" : "order canceled, and money returned back";
        $order->delete();

        return $message;
    }

    public function store($id, $products,$user_id, $location, $pay_method)
    {
        if (!is_null($id)) {
            $order = Order::find($id);
            if ($order->status != '0') {
                return [
                    'message' => 'order is already on the way',
                    'data' => null,
                    'status' => 1
                ];
            }
            self::cancel($id);
        }
        $check = $this->checkQuantity($products);
        if (!$check['available']) {
            return [
                'message' => __("you're ordering more quantity than available"),
                'data' => null,
                'status' => 0,
            ];
        }
        $attributes['pay_method'] = $pay_method;
        $attributes['status'] = '0';
        $attributes['location_id'] = $this->location($location);
        $attributes['user_id'] = $user_id;
        $attributes['total_price'] = $check['total_price'];

        $order = Order::create($attributes);

        $this->bookProducts($products, $order->id);

        return [
            'message' => 'order added',
            'data' => $order,
            'status' => 200
        ];
    }

    public function updateOrderStatus($id, $status)
    {
        $order = Order::find($id);
        $user = User::find($order->user_id);
        $order->status = $status;
        $order->save();
        /// send notification or email
        $title = 'Order status updated';
        $body = "your order status is now ";

        if ($user->fcm_token != null)
            $this->firebase->sendNotification(
                $user->fcm_token,
                $title,
                $body . self::STATUSTONAME[$status],
                ['order_id' => $order->id]
            );
        else
            $user->notify(new EmailVerificationNotification(self::STATUSTONAME[$status], $body, $title));
        return $order;
    }

    public function pick($id, $user_id)
    {
        $order = Order::with([
            'user',
            'driver',
            'location',
            'breakTable.product.images',
            'breakTable.product.shop'
        ])
            ->find($id);
        if ($order['status'] != '0') {
            return [
                'message' => 'someone picked this order',
                'data' => null,
                'status' => 0
            ];
        }
        $user = User::find($order->user_id);
        $token = $user->fcm_token;
        $order->status = '1';
        $order->driver_id = $user_id;
        $order->save();
        $title = 'Order status updated';
        $body = "your order status is now ";
        if (is_null($token)) {
            $user->notify(new EmailVerificationNotification(self::STATUSTONAME['1'], $body, $title));
        } else {
            $this->firebase->sendNotification(
                $token,
                $title,
                $body . self::STATUSTONAME['1'],
                ['order_id' => $order->id]
            );
        }
        return [
            'message' => 'order picked',
            'data' => $order,
            'status' => 200
        ];
    }

    public function history($id, $type)
    {
        if ($type == 'D') {
            $orders = Order::where('driver_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Order::where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return $orders;
    }
}