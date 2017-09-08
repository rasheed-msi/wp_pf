<?php
class MrtPrint {

    function __construct() {
        
    }
    
    public static function array_list($array) {
        foreach ($array as $key => $value) {
            echo "'{$value}',<br />";
        }
    }

}