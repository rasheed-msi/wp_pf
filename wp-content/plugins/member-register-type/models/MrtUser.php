<?php

class MrtUser {

    public function __construct($user_id = null) {

        global $wpdb;
        $this->link = $wpdb;

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

        $this->profile = new MrtProfile;
        $this->contact = new MrtContact;

        $this->user = get_user_by('ID', $this->user_id);
        $this->profile->id = $this->profile->get_profile_id($this->user_id);
        $this->contact->pkey = 'pf_profile_id';
        $this->contact->id = $this->profile->id;

        $this->profile->data = $this->profile->get($this->profile->id);
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

    public function create_profile($data) {

        if (is_null($this->user_id)) {
            return false;
        }
        
        if (isset($data['user_type'])) {
            $user = new WP_User($this->user_id);
            $user->remove_role('subscriber');
            $user->add_role($data['user_type']);
        }

        $data['wp_user_id'] = $this->user_id;
        $this->profile->id = $this->profile->insert($data);

        $data['pf_profile_id'] = $this->profile->id;
        $this->contact->insert($data);
        $this->contact->id = $this->profile->id;
        $this->profile->data = $this->profile->get($this->profile->id);


        if (isset($data['joined_agency_id'])) {
            $this->update_agency($data['joined_agency_id']);
        }
    }

    public function update_profile($data) {

        if (is_null($this->profile->id)) {

            $this->create_profile($data);
        } else {

            $data['wp_user_id'] = $this->user_id;
            $data['pf_profile_id'] = $this->profile->id;
            $this->profile->update($data);
            $this->contact->update($data);
            if (isset($data['joined_agency_id'])) {
                $this->update_agency($data['joined_agency_id']);
            }
        }
    }

    public function delete_profile() {
        $this->profile->delete();
        $this->contact->delete();
        $agency_user = new MrtRelationAgencyUser;
        $agency_user->delete('pf_profile_id', $this->profile->id);
    }

    public function update_agency($agency) {
        // Add agency
        $agency_user = new MrtRelationAgencyUser;
        $agency_user->delete('pf_profile_id', $this->profile->id);

        $insert = [];
        $insert['wp_users_id'] = $this->user_id;
        $insert['pf_profile_id'] = $this->profile->id;
        $insert['pf_agency_id'] = $agency;
        $agency_user->insert($insert);
    }

}
