<?php

if (! function_exists('clean_integration_user_option_key')) {

    function clean_integration_user_option_key($key)
    {
        $cleanRDStationCustomFieldPrefix = function ($string) {
            return str_replace([ 'cf_plug', 'cf_'], '', $string);
        };

        $removeUnderlineBySpace = function ($string) {
          return str_replace('_', ' ', $string);
        };

        $convertToPascalCase = function ($string) {
            return ucwords($string);
        };

        $formattedKey = $cleanRDStationCustomFieldPrefix($key);
        $formattedKey = $removeUnderlineBySpace($formattedKey);
        $formattedKey = $convertToPascalCase($formattedKey);

        return $formattedKey;
    }
}
