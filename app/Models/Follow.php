<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $timestamps = false;

    # To get the info of a follower
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
    }
}
