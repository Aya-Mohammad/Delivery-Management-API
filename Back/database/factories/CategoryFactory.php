<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names=['خضراوات','إلكترونيات','ملابس'];
         return [
             'name' => fake()->colorName,
             'arabic_name' => $names[array_rand($names,1)]
         ];
    }
}
