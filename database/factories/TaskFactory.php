<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'task_name' =>$this->faker->sentence($nbWords = 5, $variableNbWords = true),
            'task_description'=>$this->faker->sentence($nbWords = 7, $variableNbWords = true),
            'user_id'=>User::factory(1)->create()->first(),
        ];
    }
}
