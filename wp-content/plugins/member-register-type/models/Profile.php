<?php

class Profile extends DBBase {

    public $user_id;
    public $logged_in = false;

    public function __construct($user_id = null) {

        global $wpdb;
        $this->link = $wpdb;
        $this->table = 'wppf_profiles';
        $this->primary_key = 'id';
        $this->fields = [
            'wp_user_id',
            'pf_old_id',
            'first_name',
            'last_name',
            'gender',
            'dob',
            'marital_status',
            'occupation',
            'agency_attorney_name',
            'agency_website',
            'religion_id',
            'faith_id',
            'ethnicity_id',
            'education_id',
            'status_id',
        ];

        $this->field_default = [];

        if (!is_null($user_id)) {
            $this->user_id = $user_id;
            $this->logged_in = false;
        } else {
            if (is_user_logged_in()) {
                $this->user_id = get_current_user_id();
                $this->logged_in = true;
            } else {
                return false;
            }
        }

       

        $this->user = get_user_by('ID', $this->user_id);
        $this->id = $this->get_profile_id($this->user_id);
        $this->profile = $this->get($this->id);

        $this->user_meta = get_userdata($this->user_id);
    }

    /**
     * 
     * Get the membership levels of a logged in user
     * @return array
     */
    public function get_memberships() {

        if (!$this->logged_in) {
            return [];
        }

        $user = MeprUtils::get_currentuserinfo();
        $active_memberships = $user->active_product_subscriptions('products');

        $return = [];
        foreach ($active_memberships as $membership) {
            $return[$membership->ID] = $membership->post_title;
        }

        return $return;
    }

    public function has_mem_access($membership_level) {
        $memberships = $this->get_memberships();
        return in_array($membership_level, $memberships);
    }

    public function filter_user() {
//        Get users in north east location (6) and ABD agency(3)
//        ----------------------------------------------
//        SELECT * FROM wp_groups_user_group rt
//        LEFT JOIN wp_user u ON rt.user_id = u.ID
//        LEFT JOIN wp_user u ON rt.user_id = u.ID
//        WHERE rt.group_id = 6
//        
//        
//        SELECT * FROM
//        (SELECT 
//        rt.user_id,
//        rt.group_id AS location_id
//        FROM wp_groups_user_group rt
//        LEFT JOIN wp_users u ON rt.user_id = u.ID
//        LEFT JOIN wp_users u2 ON rt.user_id = u2.ID
//        WHERE rt.group_id = 6 ) AS  rl
//        INNER JOIN
//        (SELECT 
//        rt.user_id,
//        rt.group_id AS agency_id
//        FROM wp_groups_user_group rt
//        LEFT JOIN wp_users u ON rt.user_id = u.ID
//        LEFT JOIN wp_users u2 ON rt.user_id = u2.ID
//        WHERE rt.group_id = 3 ) AS  ra
//        ON rl.user_id = ra.user_id
//        LEFT JOIN wp_users wu ON rl.user_id = wu.ID ORDER BY `ID` ASC
    }

    public function get_profile_id($user_id) {
        return $this->link->get_var("SELECT {$this->primary_key} FROM {$this->table} WHERE wp_user_id = {$user_id}");
    }

    public function create_profile($data) {
        $this->insert($data);
        $contact = new ContactBase;
        $contact->update($data, 'pf_profile_id', $this->id);
        
    }
    
    public function update_profile($data) {
        $this->update($data);
        $contact = new ContactBase;
        $contact->update($data, 'pf_profile_id', $this->id);
    }

    public function delete_profile() {
        $this->delete();
        $contact = new ContactBase;
        $contact->delete('pf_profile_id', $this->id);
    }

}
