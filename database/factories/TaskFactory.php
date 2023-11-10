<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => 0,
            'user_id' => rand(1, 10),
            'status' => Task::TODO,
            'priority' => rand(1, 5),
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'created_at' => now(),
        ];
    }
}
