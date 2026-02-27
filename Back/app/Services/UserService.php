<?php

namespace App\Services;

use App\Traits\ImagesFunctions;
use App\Traits\LocationFunctions;
use App\Models\User;
use App\Models\BreakTable;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserService
{
    public const DEFAULTS = [
        'user_id' => null,
        'product_id' => null,
        'order_id' => null,
        'quantity' => null,
        'product_price' => null
    ];
    use ImagesFunctions;
    use LocationFunctions;
    public function update($attributes, $id, $location, $image)
    {
        $user = User::find($id);

        if(!is_null($location)) $attributes['location_id'] = $this->location(json_decode($location, true));
        if(!is_null($image)){
            $this->removeImages('user_id', $id);
            $this->addImages($image, 'user_id', $id, 'users');
        }
        User::where('id', $id)->update($attributes);
        $user = User::with(['location','images'])
            ->find($id);
        return $user;
    }

    public function favourites($id)
    {
        $user = User::find($id);
        $products = $user->favourites;
        return $products;
    }

    public function addFavourite($user_id, $product_id)
    {

        $check = BreakTable::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();
        if (!is_null($check)) {
            return __('producte already in favourites');
        }
        $attributes = self::DEFAULTS;
        $attributes['user_id'] = $user_id;
        $attributes['product_id'] = $product_id;
        BreakTable::create($attributes);
        return __('product added to favourites');
    }

    public function removeFavourite($user_id, $product_id)
    {
        $check = BreakTable::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if (is_null($check)) {
            return __('producte is not in favourites');
        }
        $check->delete();
        return __('product removed from favourites');
    }

    public function search($user_id, $keyword)
    {
        $products = $products = Product::with(['images', 'category', 'shop'])
            ->where('products.quantity', '>', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', $keyword)
                    ->orWhere('arabic_name', 'like', $keyword);
            })
            ->leftjoin('break_tables', function ($join) use ($user_id) {
                $join->on('products.id', '=', 'break_tables.product_id')
                    ->where('break_tables.user_id', '=', $user_id);
            })
            ->select('products.*', DB::raw('IF(break_tables.id IS NOT NULL, 1, 0) as is_favourite'))
            ->paginate(10);
        $shops = Shop::with('images')
            ->where('name', 'like', $keyword)
            ->orWhere('arabic_name', 'like', $keyword)
            ->paginate(10);

        return [
            'products' => $products,
            'shops' => $shops
        ];
    }

    public function addDriver($attributes)
    {
        $attributes['type'] = 'D';
        $attributes['password'] = Hash::make($attributes['password']);
        $user = User::create($attributes);
        $user->email_verified_at = now();
        $user->save();
        return $user;
    }
}