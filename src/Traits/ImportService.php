<?php

namespace App\Traits;

use App\Import;

trait ImportService
{
    protected $import;
    protected $return = [
        'message' => '',
        'status' => true
    ];

    public function set(Import $import)
    {
        $this->import = $import;
        return $this;
    }

    public function setReturn($field, $value = NULL)
    {
        if (is_string($field)) {
            $this->return[$field] = $value;
        }

        if (is_array($field)) {
            $this->return = array_merge($this->return, $field);
        }
    }

    public function getReturn()
    {
        return $this->return;
    }

}
