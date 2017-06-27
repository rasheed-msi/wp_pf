<?php

class MrtMidController {

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
    }

    function test() {
        $states = $this->link->get_results("SELECT state_id, State FROM pf_states WHERE country_id = 181", ARRAY_A);
        $results = Dot::set_array_key_value($states, 'state_id', 'State');
        echo '<pre>', print_r($results), '</pre>';
        exit();
    }

}
