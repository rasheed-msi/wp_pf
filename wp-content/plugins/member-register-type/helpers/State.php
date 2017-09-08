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

    public static function has_membership_access($membership_level = null) {
        $user_id = get_current_user_id();

        if (!$user_id) {
            return false;
        }

        $mrt_user = new MrtUser($user_id);
        return $mrt_user->has_mem_access($membership_level);
    }

    public static function create_zip($files) {

        $zip = new ZipArchive();

        $filename = rand(1000, 9999) . time() . '.zip';
        $tmp_file = MRT_DIR_UPLOADS . '/' . $filename;
        $zip->open($tmp_file, ZipArchive::CREATE);

        foreach ($files as $file) {
            $download_file = file_get_contents($file);
            $zip->addFromString(basename($file), $download_file);
        }

        $zip->close();

        return MRT_URL_UPLOADS . '/' . $filename;

        # send the file to the browser as a download
//        header('Content-disposition: attachment; filename="my file.zip"');
//        header('Content-type: application/zip');
//        readfile($tmp_file);
//        unlink($tmp_file);
    }
    
    public static function hasrole($capability, $param = null, $condition = 'AND') {

        if ($capability == null) {
            return is_user_logged_in();
        }

        if (is_user_logged_in()) {
            $current_user_id = get_current_user_id();
            $mrt_user = new MrtUser($current_user_id);
            $role = $mrt_user->user_role;
            
        } else {
            $role = 'public';
            $current_user_id = 0;
        }

        $system_roles = Stock::system_roles();

        if (is_array($capability)) {

            if ($condition == 'OR') {
                foreach ($capability as $value) {
                    if (in_array($value, $system_roles[$role]['capabilities'])) {
                        return true;
                    }
                }
            }

            if ($condition == 'AND') {
                $have = array();
                foreach ($capability as $key => $value) {

                    if (in_array($value, $system_roles[$role]['capabilities'])) {
                        $have[] = 1;
                    }
                }

                if (count($capability) == count($have)) {
                    return true;
                }
            }
        } else {

            switch ($capability) {

                // Special cases for role capability check
                case 'user_delete':
                case 'user_edit_role':
                    $user_id = $param;
                    if ($user_id != $current_user_id && in_array($capability, $system_roles[$role]['capabilities'])) {
                        return true;
                    }

                    break;

                default :
                    if (in_array($capability, $system_roles[$role]['capabilities'])) {
                        return true;
                    }
            }
        }


        return false;
    }

}
