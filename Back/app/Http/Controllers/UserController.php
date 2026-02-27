<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\User\AddDriverRequest;
use App\Http\Requests\User\AddFavouriteRequest;
use App\Http\Requests\User\RemoveFavouriteRequest;
use App\Models\User;
use App\Models\Product;
use App\Models\Shop;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Models\BreakTable;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends APIController
{
    public const DEFAULTS = [
        'user_id' => null,
        'product_id' => null,
        'order_id' => null,
        'quantity' => null,
        'product_price' => null
    ];
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    public function update(UserUpdateRequest $request)
    {
        $attributes = $request->validate($request->rules());

        $data = $this->service->update($attributes,$request->user()->id,$request->location,$request->image);
        return $this->ok(
            __('profile updated successfully'),
            $data
        );
    }
    public function favourites(Request $request)
    {
        $data = $this->service->favourites($request->user()->id);
        return $this->ok(
            __('favourite products sent'),
            $data
        );
    }
    public function addFavourite(AddFavouriteRequest $request)
    {
        $user_id = $request->user()->id;
        $product_id = $request->id;

        $message = $this->service->addFavourite($user_id, $product_id);
        return $this->message($message);
    }
    public function removeFavourite(RemoveFavouriteRequest $request)
    {
        $user_id = $request->user()->id;
        $product_id = $request->id;

        $message = $this->service->removeFavourite($user_id, $product_id);
        return $this->message($message);
    }

    public function search(SearchRequest $request)
    {
        $keyword = '%' . $request->word . '%';
        $user_id = $this->checkUserID($request);

        $data = $this->service->search($user_id, $keyword);
        return $this->ok(
            'Products and shops sent',
            $data
        );
    }

    public function addDriver(AddDriverRequest $request){
        $attributes = $request->validate($request->rules());
        $data = $this->service->addDriver($attributes);
        return $this->ok(
            'driver added successfully',
            $$data
        );
    }
}
