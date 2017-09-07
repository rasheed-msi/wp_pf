<?php

class MrtView {

    function __construct() {
        
    }

    public static function render($template, $data = []) {
        extract($data);
        $file = MRT_TEMPLATE_PATH . '/' . $template . '.php';
        if (file_exists($file)) {
            include $file;
        }
    }

}
