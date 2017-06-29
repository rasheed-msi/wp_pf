<?php

class MrtMidController {

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    function test() {
        $form = AppForm::sample_test_form();
        
        echo '<pre>', print_r($form), '</pre>';
        exit();
    }

}
