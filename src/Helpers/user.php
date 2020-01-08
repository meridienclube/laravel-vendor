<?php
if (!function_exists('user')) {

    function user($user, $field, $default = null)
    {
        /*Procura os campos basicos*/
        if ($user && in_array($field, $user->getFillable())) {
            return $user->{$field};
        }
        /*Procura os campos de configurações*/
        if (isset($user->settings[$field])) {
            return $user->settings[$field];
        }
        /*Procura em options algum valor correspondente*/
        $option = option($user, $field, $default);
        if ($option) {
            return $option;
        }
        return $default;
    }

}
