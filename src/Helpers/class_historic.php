<?php
if (! function_exists('class_historic')) {

    function class_historic($obj, $class_basename = ''){ //CommentCreated
        $get_class_basename = class_basename(get_class($obj));
        return 'App\\Historics\\' . $get_class_basename . $class_basename . 'Historic';
     }

}
