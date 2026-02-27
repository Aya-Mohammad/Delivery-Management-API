<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;
use App\Traits\ImagesFunctions;
use Illuminate\Support\Facades\DB;
class ProductService
{
    use ImagesFunctions;
    public function index($user_id)
    {
        $products = Product::with(['images', 'category', 'shop'])
            ->where('products.quantity', '>', 0)
            ->leftjoin('break_tables', function ($join) use ($user_id) {
                $join->on('products.id', '=', 'break_tables.product_id')
                    ->where('break_tables.user_id', '=', $user_id);
            })
            ->select('products.*', DB::raw('IF(break_tables.id IS NOT NULL, 1, 0) as is_favourite'))
            ->paginate(10);
        return $products;
    }

    public function show($id, $user_id)
    {
        $product = Product::with(['images', 'category', 'shop'])
            ->find($id)
            ->leftJoin('break_tables', function ($join) use ($user_id) {
                $join->on('products.id', '=', 'break_tables.product_id')
                    ->where('break_tables.user_id', '=', $user_id);
            })
            ->select('products.*', DB::raw('IF(break_tables.id IS NOT NULL, 1, 0) as is_favourite'));
        return $product;
    }

    public function store($attributes, $shop_name, $category_name, $rimages)
    {
        $shop = Shop::where('name', '=', $shop_name)->first();
        $category = Category::where('name', '=', $category_name)->first();
        $attributes['shop_id'] = $shop->id;
        $attributes['category_id'] = $category->id;
        $product = Product::create($attributes);
        $images = $this->addImages($rimages, 'product_id', $product->id, 'products');
        return [
            'product' => $product,
            'images' => $images,
            'shop' => $shop,
            'category' => $category
        ];
    }

    public function update($attributes, $shop_name, $category_name, $id, $rimages)
    {
        $shop = Shop::where('name', $shop_name)->first();
        $category = Category::where('name', $category_name)->first();
        $attributes['shop_id'] = $shop->id;
        $attributes['category_id'] = $category->id;
        $attributes = collect($attributes)->forget(['shop_name', 'category_name'])->toArray();
        $this->removeImages('product_id', $id);
        $this->addImages($rimages, 'product_id', $id, 'products');
        Product::where('id', $id)->update($attributes);
        $product = Product::with(['category', 'shop', 'images'])
            ->find($id);
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::firstWhere('id', $id);
        $product->quantity = -99999;
        $product->save();
    }
}