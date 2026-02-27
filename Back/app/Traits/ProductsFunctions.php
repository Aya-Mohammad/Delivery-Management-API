<?php

namespace App\Traits;

use App\Models\BreakTable;
use PHPUnit\Framework\Attributes\UsesFunction;
use App\Models\Location;
use App\Models\Product;
use Exception;
trait ProductsFunctions
{
    protected function checkQuantity($products)
    {
        $totalPrice = 20000;
        foreach ($products as $productRequired) {
            $product = Product::firstWhere('id', $productRequired['id']);
            if ($product->quantity < $productRequired['quantity']) {
                return [
                    'available' => false,
                    0
                ];
            }
            $totalPrice += $product->price * $productRequired['quantity'] * (100 - $product->discount) / 100.0;
        }
        return [
            'available' => true,
            'total_price' => $totalPrice
        ];
    }

    protected function bookProducts($products, $order_id)
    {
        $attributes['order_id'] = $order_id;
        foreach ($products as $productRequired) {
            $product = Product::firstWhere('id', $productRequired['id']);
            $attributes['product_id'] = $product->id;
            $attributes['quantity'] = $productRequired['quantity'];
            $attributes['product_price'] = $product->price * (100 - $product->discount) / 100.0;
            BreakTable::create($attributes);
            $product->quantity -= $productRequired['quantity'];
            $product->save();
        }
    }

    protected function cancelBookedProducts($relations)
    {
        foreach ($relations as $relation) {
            $product = Product::firstWhere('id', $relation->product_id);
            $product->quantity += $relation->quantity;
            $product->save();
            $bt = BreakTable::find($relation->id);
            $bt->delete();
        }
    }

}