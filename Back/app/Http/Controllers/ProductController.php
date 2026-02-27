<?php
namespace App\Http\Controllers;
use App\Http\Requests\Products\ShowProductRequest;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Requests\Products\DeleteProductRequest;


class ProductController extends APIController
{
    protected $service;

    public function __construct(ProductService $service){
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $user_id = $this->checkUserID($request);
        $products = $this->service->index($user_id);
        return $this->ok(
            __('products sent'),
            $products
        );
    }
    public function show(ShowProductRequest $request)
    {
        $user_id = $this->checkUserID($request);
        $product = $this->service->show($request->id, $user_id);
        return $this->ok(
            __('product details sent'),
            $product
        );
    }
    public function store(StoreProductRequest $request)
    {
        $attributes = $request->validate($request->rules());
        $data = $this->service->store($attributes, $request->shop_name, $request->category_name, $request->images);
        return $this->ok(
            __('product added'),
            $data
        );

    }
    public function update(UpdateProductRequest $request)
    {
        $attributes = $request->validate($request->rules());
        $data = $this->service->update($attributes, $request->shop_name, $request->category_name, $request->id, $request->images);
        return $this->ok(
            __('product updated successfully'),
            $data
        );
    }
    public function destroy(DeleteProductRequest $request)
    {
        $this->service->destroy($request->id);
        return $this->message(__('product deleted'));
    }
}
