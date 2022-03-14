<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::truncate();

        $comments = [
            [
                'user_id' => '1',
                'parent_id'=>null,
                'comment'=>'Some random comment',
                'commentable_id'=> '2',
                'commentable_type'=> 'App\Models\Task'
            ],
            [
                'user_id' => '2',
                'parent_id'=>null,
                'comment'=>'This is sick!',
                'commentable_id'=> '2',
                'commentable_type'=> 'App\Models\Task'
            ],
            [
                'user_id' => '1',
                'parent_id'=>null,
                'comment'=>'I can\'t believe it!',
                'commentable_id'=> '1',
                'commentable_type'=> 'App\Models\Task'
            ],
            [
                'user_id' => '1',
                'parent_id'=> '3',
                'comment'=>'I\'m a reply comment',
                'commentable_id'=> '1',
                'commentable_type'=> 'App\Models\Task'
            ]
        ];

        Comment::insert($comments);
    }
}
