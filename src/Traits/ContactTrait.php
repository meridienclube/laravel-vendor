<?php

namespace App\Traits;

trait ContactTrait
{
    public function contacts()
    {
        return $this->morphMany('App\Contact', 'contactable');
    }
    
}
