<?php

namespace App\Http\Controllers;
use App\Http\Requests\Categories\ShowCategoryRequest;
use App\Services\CategoryService;
use App\Http\Requests\Categories\StoreCategoryRequest;
class CategoryController extends APIController
{
    protected $service;

    public function __construct(CategoryService $service){
        $this->service = $service;
    }
    public function index()
    {
        $categories = $this->service->index();
        return $this->ok(
            __('categories sent'),
            $categories
        );
    }
    public function show(ShowCategoryRequest $request)
    {
        $user_id = $this->checkUserID($request);
        $data = $this->service->show($request->id, $user_id);
        return $this->ok(
            __('category details sent'),
            $data
        );
    }
    public function store(StoreCategoryRequest $request)
    {
        $attributes = $request->validate($request->rules());

        $data = $this->service->store($attributes,$request->images);
        return $this->ok(
            __("category added"),
            $data
        );
    }
}
