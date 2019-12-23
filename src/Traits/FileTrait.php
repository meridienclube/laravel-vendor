<?php

namespace App\Traits;

use Auth;

trait FileTrait
{

    /**
     * Get all of the post's files.
     */
    public function files()
    {
        return $this->morphMany('App\File', 'fileable');
    }
}
