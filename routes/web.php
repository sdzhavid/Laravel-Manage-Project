<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ThirdPartyAuthController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Tasks

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TaskController::class, 'dashboard'])
    ->middleware(['auth'])->name('dashboard');

Route::get('create', [TaskController::class, 'create'])
    ->middleware(['auth'])->name('crud_tasks/create_task');

Route::post('store_task', [TaskController::class, 'store'])
    ->middleware(['auth'])->name('crud_tasks/store_task');

Route::delete('destroy/{id}', [TaskController::class, 'destroy'])
    ->middleware(['auth'])->name('crud_tasks/destroy_task');

Route::get('edit_task/{id}', [TaskController::class, 'edit'])
    ->middleware(['auth'])->name('crud_tasks/edit_task');

Route::put('update_task/{id}', [TaskController::class, 'update'])
    ->middleware(['auth'])->name('crud_tasks/update_task');

Route::get('update_task/{id}', [TaskController::class, 'update'])
    ->middleware(['auth'])->name('crud_tasks/update_task');

Route::get('view_task/{id}', [TaskController::class, 'show'])
    ->middleware(['auth'])->name('crud_tasks.view_task');

Route::put('assign_task/{id}', [TaskController::class, 'assignToSection'])
    ->middleware(['auth'])->name('assignToSection');
    
Route::get('assign_task/{id}', [TaskController::class, 'assignToSection'])
    ->middleware(['auth'])->name('assignToSection');

// Comment and Replies

Route::post('/comment/store', [CommentController::class, 'store'])
    ->middleware(['auth'])->name('comment.add');

Route::post('/reply/store', [CommentController::class, 'replyStore'])
    ->middleware(['auth'])->name('reply.add');

Route::delete('destroy/comment/{id}', [CommentController::class, 'destroy'])
    ->middleware(['auth'])->name('comment.destroy');


Route::get('/search', [TaskController::class, 'searchTask'])
    ->middleware(['auth'])->name('searchTask');

require __DIR__.'/auth.php';

// Google Login
Route::get('login/google', [ThirdPartyAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [ThirdPartyAuthController::class, 'handleGoogleCallback']);

// Github Login
Route::get('login/github', [ThirdPartyAuthController::class, 'redirectToGithub'])->name('login.github');
Route::get('login/github/callback', [ThirdPartyAuthController::class, 'handleGithubCallback']);

// Facebook Login
Route::get('login/facebook', [ThirdPartyAuthController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [ThirdPartyAuthController::class, 'handleFacebookCallback']);

// User My Tasks
Route::get('my_tasks', [TaskController::class, 'my_tasks'])
    ->middleware(['auth'])->name('my_tasks');


// Sections
Route::get('sections', [SectionController::class, 'sections'])
    ->middleware(['auth'])->name('sections');

Route::get('create/section', [SectionController::class, 'create'])
    ->middleware(['auth'])->name('crud_sections/create_section');

Route::post('create/store_section', [SectionController::class, 'store'])
    ->middleware(['auth'])->name('crud_sections/store_section');

Route::delete('destroy/section/{id}', [SectionController::class, 'destroy'])
    ->middleware(['auth'])->name('crud_sections/destroy_section');

Route::get('edit_section/{id}', [SectionController::class, 'edit'])
    ->middleware(['auth'])->name('crud_sections/edit_section');

Route::put('update_section/{id}', [SectionController::class, 'update'])
    ->middleware(['auth'])->name('crud_sections/update_section');

Route::get('update_section/{id}', [SectionController::class, 'update'])
    ->middleware(['auth'])->name('crud_sections/update_section');

Route::get('view_section/{id}', [SectionController::class, 'show'])
    ->middleware(['auth'])->name('crud_sections.view_section');
