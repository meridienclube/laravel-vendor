<?php
if (! function_exists('percent')) {

    function percent($total = 100, $medio = 0){
        return (100*$medio) / $total;
     }

}
