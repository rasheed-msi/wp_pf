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
            $zip->addFromString(rand(1000, 9999) . basename($file), $download_file);
        }

        $zip->close();

        return MRT_URL_UPLOADS . '/' . $filename;

        # send the file to the browser as a download
//        header('Content-disposition: attachment; filename="my file.zip"');
//        header('Content-type: application/zip');
//        readfile($tmp_file);
//        unlink($tmp_file);
    }

    

    public static function get_array_comma_separated($array, $arkey) {
        $display_list = [];
        foreach ($array as $key => $value) {
            if ((isset($value[$arkey]) && trim($value[$arkey]) != '')) {
                $display_list[] = $value[$arkey];
            }
        }
        return implode(', ', $display_list);
    }

}
