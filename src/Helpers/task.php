<?php
if (!function_exists('task')) {

    function task($task, $field, $default = null)
    {
        $t = $task->format();
        if('icon' == $field){
            return '<i class="' . $t['icon'] . '" style="color:' . $t['color']. '"></i>';
        }

        if (isset($task->type->{$field})) {
            return $task->type->{$field};
        }

        if (isset($task->{$field})) {
            return $task->{$field};
        }

        return $default;
    }

}
