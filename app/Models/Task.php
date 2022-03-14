<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;
use App\Models\Section;
use Kyslik\ColumnSortable\Sortable;

class Task extends Model
{
    use HasFactory;
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'task_name',
        'task_description'
    ];

    /**
     * The attributes that can be used for sorting
     * 
     * @var string[]
     */
    public $sortable = ['id', 'task_name', 'created_at', 'updated_at'];

    /**
     * Defines the relationship between a Task and User
     * 
     * A task belongs to only one User
     * 
     * @return type User
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship between Task and Comment
     * 
     * A Task can have one or more Comments.
     * 
     * @return type Comment[]
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * Defines the relationship between Task and Section
     * 
     * A task can be only in one Section at a time.
     * 
     * @return type Section
     */
    public function section(){
        return $this->belongsTo(Section::class);
    }

}

