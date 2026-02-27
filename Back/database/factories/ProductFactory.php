<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shop;
use App\Models\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = ['خيار', 'بندورة', 'مووووز'];
        $shop_id = Shop::inRandomOrder()->take(1)->pluck('id');
        $category_id = Category::inRandomOrder()->take(1)->pluck('id');
        return [
            'name' => fake()->name,
            'arabic_name' => $names[array_rand($names,1)],
            'description' => fake()->paragraph(),
            'arabic_description' => 'غير متاحة',
            'price' => fake()->numberBetween(1, 100),
            'quantity' => fake()->numberBetween(1, 100),
            'discount' => fake()->numberBetween(10, 70),
            'shop_id' => $shop_id[0],
            'category_id' => $category_id[0]
        ];
    }
}
