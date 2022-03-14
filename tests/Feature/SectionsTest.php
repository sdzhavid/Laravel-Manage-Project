<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Section;

class SectionsTest extends TestCase
{
    public function test_access_create_section_page_without_logged_in_user()
    {
        $response = $this->get('create/section');
        
        $response->assertRedirect('login');
    }

    public function test_access_create_section_page_when_user_is_logged_in()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('create/section');
        
        $response->assertStatus(200);
    }

    public function test_access_edit_section_page_without_logged_in_user()
    {
        $section = Section::factory()->create(['user_id' => 1]);

        $response = $this->get('edit_section/'.$section->id);

        $response->assertRedirect('login');
    }

    public function test_access_edit_section_page_when_user_is_logged_in()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create(['user_id' => $user->id]);

        $response = $this->get('edit_section/'.$section->id);

        $response->assertStatus(200);
    }


    public function test_get_tasks_page_when_user_not_logged_in()
    {

        $section = Section::factory()->create(['user_id' => 1]);

        $response =$this->get('/sections');

        $response->assertRedirect('login');
    }

    public function test_get_tasks_when_user_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create(['user_id' => $user->id]);

        $response = $this->get('sections');

        $response->assertSee($section->section_name);

    }

    public function test_fail_to_update_section_which_is_created_by_another_user_when_user_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create(['user_id' => $user->id]);
        $section->task_name = "A new section name";

        $this->put('update_section/'.$section->id, $section->toArray());

        $this->assertDatabaseMissing('sections',[
            'id' => $section->id,
            'section_name' => 'A new section name'
        ]);

    }

    public function test_update_section_which_belongs_to_the_user_which_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create([
            'section_name' => 'Random section name',
            'section_description' => ' Random section description',
            'created_at' => '2021-12-17 15:03:11',
            'updated_at' => '2021-12-17 15:03:11',
            'user_id' => $user->id
        ]);

        $section->section_name = "A new section name";

        $this->put('update_section/'.$section->id, $section->toArray());

        $this->assertDatabaseHas('sections',[
            'id' => $section->id,
            'section_name' => 'A new section name'
        ]);

    }

    public function test_create_section()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create(['user_id' => $user->id]);

        $response = $this->post('/create/store_section',$section->toArray());

        $this->assertDatabaseHas('sections', [
            'id' => $section->id
        ]);
    }

    public function test_section_name_is_not_blank_when_creating_new()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->make([
            'section_name' => null,
            'user_id' => $user->id
        ]);

        $this->post('/create/store_section', $section->toArray())->assertSessionHasErrors('section_name');
    }

    public function test_section_description_is_not_blank_when_creating_new()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->make([
            'section_description' => null,
            'user_id' => $user->id
        ]);
        
        $this->post('create/store_section', $section->toArray())->assertSessionHasErrors('section_description');
    }

    public function test_fail_to_delete_section_created_by_another_user_when_user_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create(['user_id' => 2]);

        $response = $this->delete('/destroy/section/'.$section->id);

        $this->assertDatabaseHas('sections',[
            'id' => $section->id
        ]);

    }

    public function test_delete_section_which_belongs_to_the_user_which_is_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create([
            'section_name' => 'Random section name',
            'section_description' => 'Random section description',
            'created_at' => '2021-12-17 15:03:11',
            'updated_at' => '2021-12-17 15:03:11',
            'user_id' => $user->id
        ]);

        $response = $this->delete('/destroy/section/'.$section->id);

        $this->assertDatabaseMissing('sections',[
            'id' => $section->id
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(302);
    }

    public function test_if_logged_in_user_can_read_single_section(){
        
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $section = Section::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/view_section/'.$section->id);

        $response
            ->assertSee($section->section_name)
            ->assertSee($section->section_description);
    }

    public function test_if_logged_in_user_gets_redirected_when_access_single_section_out_of_bounds_in_database(){
        
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/view_section/1000');

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200);
    }

    public function test_if_logged_in_user_gets_redirected_when_access_single_edit_section_out_of_bounds_in_database(){
        
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/view_section/1000');

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200);
    }

    public function test_access_view_section_page_without_logged_in_user()
    {
        $section = Section::factory()->create(['user_id' => 2]);

        $response = $this->get('view_section/'.$section->id);

        $response->assertRedirect('login');
    }

    public function test_access_view_section_page_when_user_is_logged_in()
    {
        $section = Section::factory()->create(['user_id' => 2]);

        $response = $this->get('view_section/'.$section->id);

        $response->assertStatus(302);
    }
}