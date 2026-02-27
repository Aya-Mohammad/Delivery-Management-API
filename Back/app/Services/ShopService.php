<?php

namespace App\Services;
use App\Models\Shop;
use App\Models\Product;
use App\Traits\ImagesFunctions;
use App\Traits\LocationFunctions;
use Illuminate\Support\Facades\DB;
class ShopService
{
    use LocationFunctions;
    use ImagesFunctions;
    public function index()
    {
        return Shop::with(['images','location'])->paginate(10);
    }
    
    public function show($id, $user_id)
    {
        $shop = Shop::with(['images','location'])
            ->find($id);
        $products = Product::with(['images', 'category', 'shop'])
            ->where('shop_id', '=', $id)
            ->where('products.quantity', '>', 0)
            ->leftjoin('break_tables', function ($join) use ($user_id) {
                $join->on('products.id', '=', 'break_tables.product_id')
                    ->where('break_tables.user_id', '=', $user_id);
            })
            ->select('products.*', DB::raw('IF(break_tables.id IS NOT NULL, 1, 0) as is_favourite'))
            ->paginate(10);
        return [
            'shop' => $shop,
            'products' => $products
        ];
    }

    public function store($attributes, $rimages, $rlocation)
    {
        $attributes['location_id'] = $this->location(json_decode($rlocation, true));

        $shop = Shop::create($attributes);
        $images = $this->addImages($rimages, 'shop_id', $shop->id, 'shops');

        return [
            'shop' => $shop,
            'images' => $images
        ];
    }
}