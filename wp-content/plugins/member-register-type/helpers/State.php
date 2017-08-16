<?php

class State {

    

    public static function get_agencies() {
        $agency = new MrtAgencies;
        return $agency->getAll();
    }
    
    public static function get_approved_agencies_html_select() {
        $agency = new MrtAgencies;
        
        $records = $agency->getApproved();
        $results = Dot::set_array_key_value($records, 'pf_agency_id', 'title');
        return $results;
    }
    
    public static function get_states($country_id) {
        global $wpdb;
        return $wpdb->get_results("SELECT state_id, State FROM pf_states WHERE country_id = {$country_id}", ARRAY_A);
    }
    
    public static function get_statesByLetter($term) {
        global $wpdb;
        return $wpdb->get_results("SELECT state_id, State FROM pf_states WHERE State LIKE '%{$term}%'", ARRAY_A);
    }

}
