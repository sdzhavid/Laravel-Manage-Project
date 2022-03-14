<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Comment;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>User::factory(1)->create()->first(),
            'comment'=>$this->faker->sentence($nbwords = 5, $variableNbWords = true),
            'commentable_id'=>Task::factory(1)->create()->first(),
            'commentable_type'=>'App\Models\Task'
        ];
    }
}
