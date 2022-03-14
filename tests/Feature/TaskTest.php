<?php

namespace Tests\Feature;

use App\Models\Section;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    public function test_access_create_task_page_without_logged_in_user()
    {
        $response = $this->get('create');
        
        $response->assertRedirect('login');
    }

    public function test_access_create_task_page_when_user_is_logged_in()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('create');
        
        $response->assertStatus(200);
    }

    public function test_access_my_tasks_page_when_user_is_logged_in(){
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('my_tasks');

        $response->assertStatus(200);
    }

    public function test_access_my_tasks_page_when_user_is_not_logged_in(){
        $response = $this->get('my_tasks');

        $response
                ->assertRedirect('login')
                ->assertStatus(302);
    }

    public function test_access_edit_task_page_without_logged_in_user()
    {
        $task = Task::factory()->create();

        $response = $this->get('edit_task/'.$task->id);

        $response->assertRedirect('login');
    }

    public function test_access_edit_task_page_when_user_is_logged_in()
    {
        $task = Task::factory()->create();

        $response = $this->get('edit_task/'.$task->id);

        $response->assertStatus(302);
    }


    public function test_get_tasks_page_when_user_not_logged_in()
    {

        $task = Task::factory()->create();

        $response =$this->get('/dashboard');

        $response->assertRedirect('login');
    }

    public function test_get_tasks_when_user_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create();

        $response = $this->get('/dashboard?page=5');

        $response->assertSee($task->task_name);

    }
    
    public function test_my_tasks_page_show_only_the_user_tasks(){
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $taskOne = Task::factory()->create(['user_id' => $user->id]);
        $taskTwo = Task::factory()->create();
        $taskThree = Task::factory()->create();
        $taskFour = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->get('my_tasks');

        $response
                ->assertSee($taskOne->task_name)
                ->assertSee($taskFour->task_name)
                ->assertDontSee($taskTwo->task_name)
                ->assertDontSee($taskThree->task_name);
    }

    public function test_if_my_tasks_page_show_correctly_the_user_name(){
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('my_tasks');

        $response->assertSee($user->name)
                ->assertDontSee($userTwo->name);

    }

    public function test_fail_to_update_task_which_is_created_by_another_user_when_user_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create();
        $task->task_name = "A new task name";

        $this->put('update_task/'.$task->id, $task->toArray());

        $this->assertDatabaseMissing('tasks',[
            'id' => $task->id,
            'task_name' => 'A new task name'
        ]);

    }

    public function test_update_task_which_belongs_to_the_user_which_is_logged_in()
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

        $task->task_name = "A new task name";

        $this->put('update_task/'.$task->id, $task->toArray());

        $this->assertDatabaseHas('tasks',[
            'id' => $task->id,
            'task_name' => 'A new task name'
        ]);

    }


    public function test_create_task()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create();

        $response = $this->post('/store_task',$task->toArray());

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id
        ]);
    }

    public function test_task_name_is_not_blank_when_creating_new()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->make([
            'task_name' => null
        ]);

        $this->post('/store_task', $task->toArray())->assertSessionHasErrors('task_name');
    }

    public function test_task_description_is_not_blank_when_creating_new()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->make([
            'task_description' => null
        ]);
        
        $this->post('/store_task', $task->toArray())->assertSessionHasErrors('task_description');
    }

    public function test_fail_to_delete_task_created_by_another_user_when_user_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create();

        $response = $this->delete('/destroy/'.$task->id);

        $this->assertDatabaseHas('tasks',[
            'id' => $task->id
        ]);

    }

    public function test_delete_task_which_belongs_to_the_user_which_is_logged_in()
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

        $response = $this->delete('/destroy/'.$task->id);

        $this->assertDatabaseMissing('tasks',[
            'id' => $task->id
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(302);
    }

    public function test_if_logged_in_user_can_read_single_task(){
        
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create();

        $response = $this->get('/view_task/'.$task->id);

        $response
            ->assertSee($task->task_name)
            ->assertSee($task->task_description);
    }

    public function test_if_logged_in_user_gets_redirected_when_access_single_task_out_of_bounds_in_database(){
        
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/view_task/1000');

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200);
    }

    public function test_if_logged_in_user_gets_redirected_when_access_single_edit_task_out_of_bounds_in_database(){
        
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/view_task/1000');

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200);
    }

    public function test_access_view_task_page_without_logged_in_user()
    {
        $task = Task::factory()->create();

        $response = $this->get('view_task/'.$task->id);

        $response->assertRedirect('login');
    }

    public function test_access_view_task_page_when_user_is_logged_in()
    {
        $task = Task::factory()->create();

        $response = $this->get('view_task/'.$task->id);

        $response->assertStatus(302);
    }


    public function test_if_seeders_work()
    {

        // THIS TEST WILL FAIL BECAUSE OF THE DATABASE TRASNACTIONS.
        //It's a known php 8.0 problem when implicit commits have been issued in a test.
        
         $this->seed();

        $this->assertDatabaseHas('users',[
            'name' => 'Test Name'
        ]);
        $this->assertDatabaseMissing('users',[
            'name' => 'Wrong Name'
        ]);
        $this->assertDatabaseCount('tasks', 3);
    }

    public function test_search_name_only()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        
        $taskOne = Task::factory()->create(['task_name' => 'The test is working']);
        $taskTwo = Task::factory()->create(['task_name' => 'The Second test']);
        $taskThree = Task::factory()->create();
        $taskFour = Task::factory()->create();
        
        $response = $this->get('/search?task_query_name=The&task_query_creator=&task_query_date_created=');

        $response
                ->assertSee($taskOne->task_name)
                ->assertDontSee($taskThree->task_name)
                ->assertSee($taskTwo->task_name)
                ->assertStatus(200)
                ->assertSessionDoesntHaveErrors();

    }

    public function test_search_creator_only()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);


        $userOne = User::factory()->create();
        $userTwo = User::factory()->create([
            'name' => 'Testing'
        ]);

        $taskOne = Task::factory()->create(['user_id' => $userOne->id]);
        $taskTwo = Task::factory()->create(['user_id' => $userTwo->id]);
        $taskThree = Task::factory()->create();
        $taskFour = Task::factory()->create();

        $response = $this->get('/search?task_query_name=&task_query_creator=Testing&task_query_date_created=');

        $response
                ->assertDontSee($taskOne->task_name)
                ->assertDontSee($taskThree->task_name)
                ->assertSee($taskTwo->task_name)
                ->assertStatus(200)
                ->assertSessionDoesntHaveErrors();
    }

    public function test_search_date_only()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);


        $taskOne = Task::factory()->create(['created_at' => '2024-03-12 00:00:00', 'task_name' => 'has to be hidden']);
        $taskTwo = Task::factory()->create(['created_at' => '2022-01-01 00:00:00', 'task_name' => 'has to be shown']);
        $taskThree = Task::factory()->create(['created_at' => '2018-05-09']);
        $taskFour = Task::factory()->create(['created_at' => '2002-07-02']);

        $response = $this->get('/search?task_query_name=&task_query_creator=&task_query_date_created=2021-01-02');

        $response
                ->assertDontSee($taskOne->task_name)
                ->assertDontSee($taskTwo->task_name)
                ->assertSee($taskThree->task_name)
                ->assertSee($taskFour->task_name)
                ->assertStatus(200)
                ->assertSessionDoesntHaveErrors();
    }

    public function test_search_date_bigger_than_current_date()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/search?task_query_name=&task_query_creator=&task_query_date_created='.Carbon::now()->addDay());

        $response
                ->assertRedirect('dashboard')
                ->assertStatus(302);
    }

    public function test_search_name_and_creator()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $userOne = User::factory()->create();
        $userTwo = User::factory()->create([
            'name' => 'Testing'
        ]);

        $taskOne = Task::factory()->create(['task_name' => 'The test is working', 'user_id' =>$userOne->id]);
        $taskTwo = Task::factory()->create(['task_name' => 'The Second test', 'user_id' => $userTwo->id]);
        $taskThree = Task::factory()->create();
        $taskFour = Task::factory()->create();

        $response = $this->get('/search?task_query_name=The&task_query_creator=Testing&task_query_date_created=');

        $response
                ->assertDontSee($taskOne->task_name)
                ->assertDontSee($taskThree->task_name)
                ->assertSee($taskTwo->task_name)
                ->assertStatus(200)
                ->assertSessionDoesntHaveErrors();

    }

    public function test_search_name_and_date()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $taskOne = Task::factory()->create(['task_name' => 'The test is working', 'created_at' => '2018-03-15']);
        $taskTwo = Task::factory()->create(['task_name' => 'The Second test', 'created_at' => '2021-01-01']);
        $taskThree = Task::factory()->create(['task_name' => 'This should not be shown', 'created_at' => '2020-02-20']);
        $taskFour = Task::factory()->create(['task_name' => 'The same problem here', 'created_at' => '2021-03-03']);
        
        $response = $this->get('/search?task_query_name=The&task_query_creator=&task_query_date_created=2021-01-05');

        $response
                ->assertSee($taskOne->task_name)
                ->assertDontSee($taskThree->task_name)
                ->assertSee($taskTwo->task_name)
                ->assertDontSee($taskFour->task_name)
                ->assertStatus(200)
                ->assertSessionDoesntHaveErrors();

    }

    public function test_search_creator_and_date()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);


        $userOne = User::factory()->create();
        $userTwo = User::factory()->create([
            'name' => 'Testing'
        ]);

        $taskOne = Task::factory()->create(['user_id' => $userOne->id]);
        $taskTwo = Task::factory()->create(['user_id' => $userTwo->id, 'created_at' => '2019-10-30']);
        $taskThree = Task::factory()->create();
        $taskFour = Task::factory()->create(['user_id' => $userTwo->id, 'created_at' => '2020-15-30']);
        $taskFive = Task::factory()->create(['user_id' => $userTwo->id, 'created_at' => '2022-01-30']);

        $response = $this->get('/search?task_query_name=&task_query_creator=Testing&task_query_date_created=2021-05-20');

        $response
                ->assertDontSee($taskOne->task_name)
                ->assertDontSee($taskThree->task_name)
                ->assertSee($taskTwo->task_name)
                ->assertSee($taskFour->task_name)
                ->assertDontSee($taskFive->task_name)
                ->assertStatus(200)
                ->assertSessionDoesntHaveErrors();
        
    }

    public function test_search_name_and_creator_and_date()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $userOne = User::factory()->create();
        $userTwo = User::factory()->create([
            'name' => 'Testing'
        ]);

        $taskOne = Task::factory()->create(['task_name' => 'The test is working', 'user_id' =>$userOne->id, 'created_at' => '2018-05-02']);
        $taskTwo = Task::factory()->create(['task_name' => 'The Second test', 'user_id' => $userTwo->id, 'created_at' => '2019-03-02']);
        $taskThree = Task::factory()->create(['task_name' => 'The third test', 'user_id' => $userTwo->id, 'created_at' => '2020-03-02']);
        $taskFour = Task::factory()->create(['task_name' => 'Should not be shown', 'user_id' => $userTwo->id, 'created_at' => '2018-07-02']);

        $response = $this->get('/search?task_query_name=The&task_query_creator=Testing&task_query_date_created=2020-01-01');

        $response
                ->assertDontSee($taskOne->task_name)
                ->assertDontSee($taskThree->task_name)
                ->assertSee($taskTwo->task_name)
                ->assertDontSee($taskFour->task_name)
                ->assertStatus(200)
                ->assertSessionDoesntHaveErrors();
    }

    public function test_search_no_fields_inserted_redirects_to_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/search?task_query_name=&task_query_creator=&task_query_date_created=');

        $response
                ->assertRedirect('dashboard')
                ->assertStatus(302);
                
    }

    public function test_sort_by_id_ascending()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $userOne = User::factory()->create();
        $userTwo = User::factory()->create([
            'name' => 'Testing'
        ]);

        $taskOne = Task::factory()->create(['user_id' => $userTwo->id]);
        $taskTwo = Task::factory()->create(['user_id' => $userTwo->id]);
        $taskThree = Task::factory()->create(['user_id' => $userTwo->id]);
        $taskFour = Task::factory()->create(['user_id' => $userTwo->id]);

        $response = $this->get('/dashboard?sort=id&direction=asc');

        $response
                ->assertSeeInOrder([$taskOne->task_name, $taskTwo->task_name, $taskThree->task_name, $taskFour->task_name])
                ->assertSessionHasNoErrors();
    }

    public function test_sort_by_id_descending()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $taskOne = Task::factory()->create();
        $taskTwo = Task::factory()->create();
        $taskThree = Task::factory()->create();

        $response = $this->get('/dashboard?sort=id&direction=desc');

        $response
                ->assertSeeInOrder([$taskThree->task_name, $taskTwo->task_name, $taskOne->task_name])
                ->assertSessionHasNoErrors();
    }

    public function test_sort_by_task_name_ascending()
    {
    
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $taskOne = Task::factory()->create(['task_name' => 'Alibaba']);
        $taskTwo = Task::factory()->create(['task_name' => 'Sam is not cool']);
        $taskThree = Task::factory()->create(['task_name' => 'Bad idea']);

        $response = $this->get('/dashboard?sort=task_name&direction=asc');
        
        $response
        ->assertSeeInOrder([$taskOne->task_name, $taskThree->task_name, $taskTwo->task_name])
        ->assertSessionHasNoErrors();
    }

    public function test_sort_by_task_name_descending()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $taskOne = Task::factory()->create(['task_name' => 'Alibaba']);
        $taskTwo = Task::factory()->create(['task_name' => 'Sam is not cool']);
        $taskThree = Task::factory()->create(['task_name' => 'Bad idea']);

        $response = $this->get('/dashboard?sort=task_name&direction=desc');
        
        $response
                ->assertSeeInOrder([$taskTwo->task_name, $taskThree->task_name, $taskOne->task_name])
                ->assertSessionHasNoErrors();
    }

    public function test_sort_by_creator_name_ascending()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $userOne = User::factory()->create([
            'name' => 'Cevin'
        ]);
        $userTwo = User::factory()->create([
            'name' => 'Testing'
        ]);
        $userThree = User::factory()->create([
            'name' => 'Wanda'
        ]);

        $taskOne = Task::factory()->create(['user_id' => $userOne->id]);
        $taskTwo = Task::factory()->create(['user_id' => $userTwo->id]);
        $taskThree = Task::factory()->create(['user_id' => $userThree->id]);

        $response = $this->get('/dashboard?sort=user.name&direction=asc');
        
        $response
                ->assertSeeInOrder([$taskOne->task_name, $taskTwo->task_name, $taskThree->task_name])
                ->assertSessionHasNoErrors();
    }

    public function test_sort_by_creator_name_descending()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $userOne = User::factory()->create([
            'name' => 'Alan'
        ]);
        $userTwo = User::factory()->create([
            'name' => 'Bob'
        ]);
        $userThree = User::factory()->create([
            'name' => 'Cevin'
        ]);

        $taskOne = Task::factory()->create(['user_id' => $userOne->id]);
        $taskTwo = Task::factory()->create(['user_id' => $userTwo->id]);
        $taskThree = Task::factory()->create(['user_id' => $userThree->id]);

        $response = $this->get('/dashboard?sort=user.name&direction=desc');
        
        $response
                ->assertSeeInOrder([$taskThree->user->name, $taskTwo->user->name, $taskOne->user->name])
                ->assertSessionHasNoErrors();
    }

    public function test_sort_by_date_ascending()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $taskTwo = Task::factory()->create(['created_at' => '2019-01-12']);
        $taskOne = Task::factory()->create(['created_at' => '2018-12-13']);
        $taskThree = Task::factory()->create(['created_at' => '2020-01-12']);

        $response = $this->get('/dashboard?sort=created_at&direction=asc');
        
        $response
                ->assertSeeInOrder([$taskOne->task_name, $taskTwo->task_name, $taskThree->task_name])
                ->assertSessionHasNoErrors();
    }

    public function test_assign_task_to_section()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $task = Task::factory()->create();
        $section = Section::factory()->create(['user_id' => $user->id]);

        $task->section_id = $section->id;

        $response = $this->put('/assign_task/' . $task->id, $section->toArray());

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'section_id' =>$section->id
        ]);

        $response->assertRedirect('dashboard');
    }

    public function test_detach_task_from_section()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['section_id' => $section->id]);

        $section->section_name = null;

        $response = $this->put('/assign_task/' . $task->id, $section->toArray());

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'section_id' => $section->id
        ]);

        $response->assertRedirect('dashboard');        
    }
}
