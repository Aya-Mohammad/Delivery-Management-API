<?php

namespace App\Services;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ImagesFunctions;
use Illuminate\Support\Facades\DB;
class CategoryService
{
    use ImagesFunctions;
    public function index()
    {
        return Category::with('images')->paginate(10);
    }

    public function show($id, $user_id)
    {
        $category = Category::where('id', '=', $id)->with('images')->get()[0];

        $products = Product::with(['images', 'category', 'shop'])
            ->where('category_id', '=', $id)
            ->where('products.quantity', '>', 0)
            ->leftjoin('break_tables', function ($join) use ($user_id) {
                $join->on('products.id', '=', 'break_tables.product_id')
                    ->where('break_tables.user_id', '=', $user_id);
            })
            ->select('products.*', DB::raw('IF(break_tables.id IS NOT NULL, 1, 0) as is_favourite'))
            ->paginate(10);
        return [
            'category' => $category,
            'products' => $products
        ];
    }

    public function store($attributes,$rimages)
    {
        $category = Category::create($attributes);

        $images = $this->addImages($rimages, 'category_id', $category->id, 'categories');
        return [
            'category' => $category,
            'images' => $images
        ];
    }
}