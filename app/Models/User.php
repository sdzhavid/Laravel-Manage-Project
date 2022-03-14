<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Task;
use App\Models\Section;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'avatar',
    ];

    /**
     * The attributes that can be used for sorting
     * 
     * @var array
     */

    public $sortable = ['id', 'name'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Defines the relationship between User and Tasks.
     * 
     * A User can hold many tasks.
     * 
     * @return type Task
     */
    public function tasks(){
        return $this->hasMany(Task::class);
    }

    /**
     * Defines the relationship between User and Sections
     * 
     * A User can hold many sections.
     * 
     * @return type Section
     */

    public function sections(){
        return $this->hasMany(Section::class);
    }
}
