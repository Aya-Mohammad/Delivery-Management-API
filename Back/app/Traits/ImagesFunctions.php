<?php

namespace App\Traits;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\UsesFunction;
trait ImagesFunctions
{

    protected function addImages($images, $key, $value, $pathName)
    {
        if (is_null($images)) {
            return;
        }
        $ret = [];
        foreach ($images as $image) {
            $attributes = [
                'path' => null,
                'product_id' => null,
                'category_id' => null,
                'shop_id' => null,
                'user_id' => null,
            ];
            $fileName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs($pathName, $fileName, 'public');
            $attributes['path'] = $path;
            $ret[] = $path;
            $attributes[$key] = $value;
            Image::create($attributes);
        }
        return $ret;
    }

    protected function removeImages($key, $value)
    {
        while (true) {
            $image = Image::firstWhere($key, '=', $value);
            if (is_null($image))
                break;
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        return;
    }

    protected function getImages($key, $value)
    {
        $images = Image::where($key, '=', $value);
        return $images;
    }
}