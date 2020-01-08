<?php
if (!function_exists('scheduleObserver')) {

    function scheduleObserver($obj, $where, $when, $data = [])
    {
        return resolve('ScheduleService')->executeObserver($obj, $where, $when, $data);
    }

}
