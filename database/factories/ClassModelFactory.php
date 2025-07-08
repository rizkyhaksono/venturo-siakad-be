<?php

namespace Database\Factories;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassModelFactory extends Factory
{
    protected $model = ClassModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true) . ' Class',
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
