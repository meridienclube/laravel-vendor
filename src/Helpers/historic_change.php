<?php
if (! function_exists('historic_change')) {

    function historic_change($obj){
        $changes = (!$obj->wasRecentlyCreated)? $obj->getChanges() : $obj->getAttributes();

        unset($changes['created_at']);
        unset($changes['updated_at']);
        unset($changes['deleted_at']);
        unset($changes['password']);
        unset($changes['id']);

        return $changes;
     }

}
