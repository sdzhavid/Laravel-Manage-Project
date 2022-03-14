<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::truncate();

        $tasks = [
            [
                'task_name' => 'Eat your vegetables',
                'task_description' => 'They are really healthy',
                'user_id' => '1',
            ],
            [
                'task_name' => 'Study for university',
                'task_description' => 'For modules: Advanced Web Programming, AI, Individual project',
                'user_id' => '2',
            ],
            [
                'task_name' => 'Call girlfriend',
                'task_description' => 'You haven\'t done that in the past week!!!',
                'user_id' => '1',
            ]
        ];

        Task::insert($tasks);
    }
}
