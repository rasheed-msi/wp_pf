<?php

class MrtMidController {

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    function test() {
        echo $form = MrtApiController::base('set_agency_status_approve');
        
      
    }

}
