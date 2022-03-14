<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'section_name',
        'section_description'
    ];

    /**
     * Defines the relationship between Section and Task
     * 
     * A Section can hold many Tasks
     * 
     * @return type Task[]
     */
    public function tasks(){
        return $this->hasMany(Task::class);
    }
    /**
     * Defines the relationship between Section and User
     * 
     * A Section belongs only to one User
     * 
     * @return type User
     */

    public function user(){
        return $this->belongsTo(User::class);
    }
}
