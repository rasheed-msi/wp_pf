<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class TableDef {

    private $table_prefix = 'mrt_';

    function profile() {
        global $wpdb;

        $table_name = $this->table_prefix . 'userprofile';

        $charset_collate = $wpdb->get_charset_collate();
        

        $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                wp_user_id bigint(20),
                role varchar(20),
                first_name varchar(55),
                last_name varchar(55),
                marital_status tinytext,
                dob datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                gender tinytext,
                phone varchar(20),
                account_id bigint(20),
                agency_id bigint(20),
                education_id bigint(20),
                ethnicity_id bigint(20),
                faith_id bigint(20),
                religion_id bigint(20),
                waiting_id bigint(20),
                city varchar(55),
                state varchar(55),
                street_address varchar(55),
                zip int(11),
                agency_website varchar(55),
                agency_attorney_name varchar(55),
                last_login datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                PRIMARY KEY  (id)
              ) $charset_collate;";

        dbDelta($sql);
    }

    function bxoldid() {
        global $wpdb;

        $table_name = $this->table_prefix . 'bxoldid';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                new_id bigint(20) NOT NULL,
                old_id bigint(20) NOT NULL,
                type varchar(55),
                PRIMARY KEY (id)
              ) $charset_collate;";

        dbDelta($sql);
    }
    
    

}
