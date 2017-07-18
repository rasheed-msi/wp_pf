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

    

}
