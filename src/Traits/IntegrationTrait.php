<?php

namespace App\Traits;

use Auth;

trait IntegrationTrait
{

    public function integrations()
    {
        return $this->belongsToMany('App\Integration');
    }

    public function integrationData()
    {
        return $this->morphMany('App\IntegrationData', 'integrationdataable');
    }

}
