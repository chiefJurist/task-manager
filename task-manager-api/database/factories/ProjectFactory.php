<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->words(2, true)),
        ];
    }

    //assigning tasks to projects after creating a project
    public function configure(): static
    {
        return $this->afterCreating(function (Project $project) {
            Task::factory()
                ->count(5)
                ->for($project->user)
                ->for($project)
                ->create();
        });
    }
}
