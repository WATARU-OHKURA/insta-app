<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    # A post belongs to a user
    # To get the owner of the post
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    # To get the categories under a post
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    # To get all the comments of a post
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    # Returns TRUE if the Auth user already liked the post
    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
