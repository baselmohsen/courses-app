<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

class CourseFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(50, 500),
            'thumbnail' => null,
            'category_id' => Category::factory(),
            'instructor_id' => User::factory()->state(['role'=>'instructor']), // مهم
        ];
    }
}
