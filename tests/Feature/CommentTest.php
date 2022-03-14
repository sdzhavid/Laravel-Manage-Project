<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Task;
use App\Models\Comment;
use App\Models\User;

class CommentTest extends TestCase{

    public function test_user_delete_comment_they_wrote()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create([
            'task_name' => 'Random task name',
            'task_description' => ' Random task description',
            'created_at' => '2021-12-17 15:03:11',
            'updated_at' => '2021-12-17 15:03:11',
            'user_id' => $user->id
        ]);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'parent_id'=>null,
            'comment'=>'Some random comment',
            'commentable_id'=> $task->id,
            'commentable_type'=> 'App\Models\Task'
        ]);
        
        $response = $this->delete('/destroy/comment/' .$comment->id);

        $this->assertDatabaseMissing('comments', [
            'id'=>$comment->id
        ]);
    }

    public function test_user_delete_comment_they_did_not_write()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create([
            'task_name' => 'Random task name',
            'task_description' => ' Random task description',
            'created_at' => '2021-12-17 15:03:11',
            'updated_at' => '2021-12-17 15:03:11',
            'user_id' => $user->id
        ]);

        $comment = Comment::factory()->create();
        
        $response = $this->delete('/destroy/comment/' .$comment->id);

        $this->assertDatabaseHas('comments', [
            'id'=>$comment->id
        ]);

        $response
                ->assertStatus(302)
                ->assertSessionHasNoErrors();
    }

    public function test_user_creates_a_comment()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create([
            'task_name' => 'Random task name',
            'task_description' => ' Random task description',
            'created_at' => '2021-12-17 15:03:11',
            'updated_at' => '2021-12-17 15:03:11',
            'user_id' => $user->id
        ]);

        $comment = Comment::factory()->create();

        $response = $this->post('/comment/store', $comment->toArray());

        $this->assertDatabaseHas('comments', [
            'id'=>$comment->id
        ]);

        $response->assertSee($comment->comment);
    }
    public function test_user_creates_a_null_comment_()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $comment = Comment::factory()->make([
            'comment'=> null
        ]);

        $response = $this->post('/comment/store', $comment->toArray());

        $response->assertDontSee($comment->comment);
        $this->assertDatabaseMissing('comments',[
            'id'=>$comment->id
        ]);
    }
    
    public function test_user_replies_to_a_comment()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create([
            'task_name' => 'Random task name',
            'task_description' => ' Random task description',
            'created_at' => '2021-12-17 15:03:11',
            'updated_at' => '2021-12-17 15:03:11',
            'user_id' => $user->id
        ]);

        $firstComment = Comment::factory()->create();

        $reply = Comment::factory()->create([
            'user_id' => $user->id,
            'parent_id'=> $firstComment->id,
            'comment'=>'Some random comment',
            'commentable_id'=> $task->id,
            'commentable_type'=> 'App\Models\Task'
        ]);

        $response = $this->post('/reply/store', $reply->toArray());

        $this->assertDatabaseHas('comments',[
            'id'=>$reply->id
        ]);

        $response->assertSee($reply->comment);
    }
    public function test_user_replies_with_null_message_to_a_comment()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create();

        $comment = Comment::factory()->create();

        $reply = Comment::factory()->make([
            'user_id' => $user->id,
            'parent_id'=> $comment->id,
            'comment'=> null,
            'commentable_id'=> $task->id,
            'commentable_type'=> 'App\Models\Task'
        ]);

        $response = $this->post('/reply/store', $reply->toArray());

        $response->assertDontSee($reply->comment);
        $this->assertDatabaseMissing('comments',[
            'id'=>$reply->id
        ]);
    }

}