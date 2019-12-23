<?php

namespace App\Traits;

use Auth;

trait CommentTrait
{
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
