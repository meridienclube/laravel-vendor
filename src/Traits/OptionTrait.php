<?php

namespace App\Traits;

use App\Optiongable;

trait OptionTrait
{

    public function options()
    {
        return $this->belongsToMany('App\Option');
    }

    public function optionsValues()
    {
        return $this->morphToMany('App\Option', 'optiongable')
            ->using(Optiongable::class)
            ->withPivot('content')
            ->withTimestamps();
    }

}
