<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //predictable user
        User::factory()
            ->hasProjects(3)
            ->has(
                Task::factory()
                ->count(5)
                ->sequence(fn ($sequence) => ['priority' => $sequence->index + 1])
            )
            ->create([
                'name' => 'Anthony Nnanna',
                'email' => 'anthonynnanna@ct.com'
            ]);

        //unpredictable users
        User::factory()
            ->count(2)
            ->hasProjects(3)
            ->has(
                Task::factory()
                ->count(5)
                ->sequence(fn ($sequence) => ['priority' => $sequence->index + 1])
            )
            ->create();
    }
}
