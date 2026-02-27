<?php

namespace App\Http\Controllers;
use App\Http\Requests\Shops\StoreShopRequest;
use App\Http\Requests\Shops\ShowShopRequest;
use App\Services\ShopService;

class ShopController extends APIController
{

    protected $service;
    public function __construct(ShopService $shopService)
    {
        $this->service = $shopService;
    }
    public function index()
    {
        $shops = $this->service->index();
        return $this->ok(
            __('shops sent'),
            $shops
        );
    }
    public function show(ShowShopRequest $request)
    {
        $user_id = $this->checkUserID($request);
        $data = $this->service->show($request->id, $user_id);
        return $this->ok(
            __('shop details sent'),
            $data
        );
    }
    public function store(StoreShopRequest $request)
    {
        $attributes = $request->validate($request->rules());
        $data = $this->service->store($attributes, $request->images, $request->location);

        return $this->ok(
            __("category added"),
            $data
        );
    }
}
