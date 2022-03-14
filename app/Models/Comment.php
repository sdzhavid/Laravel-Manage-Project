<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are guarded.
     * 
     * @var string[]
     */
    protected $guarded = [];
    
    /**
     * Defines the relationship between Comment and User
     * 
     * A comment can be created only by one User
     * 
     * @return type User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Defines the relationship between Comment and itself(Comment)
     * 
     * A comment can have one or more replies(Comments)
     * 
     * @return type Comment[]
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
