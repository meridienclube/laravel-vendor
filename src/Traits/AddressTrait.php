<?php

namespace App\Traits;

use Auth;

trait AddressTrait
{

    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }
}
