<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // this means that a post belongs to a user
    public function user() {
        return $this->belongsTo('App\User');
    }
}
