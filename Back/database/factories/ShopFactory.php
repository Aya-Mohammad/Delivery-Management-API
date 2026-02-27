<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shop;
use App\Models\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = ['الصاحب', 'بكداش', 'سبلاش'];
        return [
            'name' => fake()->name,
            'description' => fake()->paragraph(),
            'arabic_name' => $names[array_rand($names,1)],
            'arabic_description' => 'غير متاحة',
        ];
    }
}
