<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Category;
use App\Models\Shop;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images',function(Blueprint $table){
            $table->id();
            $table->string('path');
            $table->foreignIdFor(Product::class,'product_id')->nullable();
            $table->foreignIdFor(Shop::class,'shop_id')->nullable();
            $table->foreignIdFor(Category::class,'category_id')->nullable();
            $table->foreignIdFor(User::class,'user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
