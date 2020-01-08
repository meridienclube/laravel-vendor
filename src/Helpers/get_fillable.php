<?php
if (!function_exists('get_fillable')) {

    function get_fillable($class)
    {
        $obj = new $class;
        return isset($obj) ? $obj->getFillable() : [];
    }

}
