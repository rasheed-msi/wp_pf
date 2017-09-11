<?php

class MrtUser {

    public $profile;
    public $contact;
    public $user_id;

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

        $this->user = get_user_by('ID', $this->user_id);
        $this->user_meta = get_userdata($this->user_id);
        $this->user_role = $this->user_meta->roles[0];

        $this->profile = MrtProfile::find($this->user_id, 'wp_user_id');

        if (isset($this->profile->id)) {
            $this->contact = MrtContact::find($this->profile->id, 'pf_profile_id');
        } else {
            $this->contact = new MrtContact();
        }
    }

    /**
     * 
     * Get the membership levels of a logged in user
     * @return array
     */
    public function get_memberships() {
        
        $active_memberships = pmpro_getMembershipLevelForUser($this->user_id);
        
        if (is_object($active_memberships)) {
            $return[$active_memberships->ID] = $active_memberships->name;
            return $return;
        }

        $return = [];
        if (is_array($active_memberships)) {
            foreach ($active_memberships as $membership) {
                $return[$membership->ID] = $membership->name;
            }
        }
        
        return $return;
    }

    public function has_mem_access($membership_level = null) {
        $memberships = $this->get_memberships();
        

        if (is_null($membership_level)) {
            // Check has any membership level
            return (count($memberships) > 0) ? true : false;
        }

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

        if (!isset($this->user_id)) {
            return false;
        }

        if (isset($data['user_type'])) {
            $user = new WP_User($this->user_id);
            $user->remove_role('subscriber');
            $user->add_role($data['user_type']);
            add_user_meta($this->user_id, 'mrt_user_role_register', $data['user_type']);
        }

        if (isset($data['user_type']) && $data['user_type'] == 'adoption_agency') {

            $agency = new MrtAgencies();
            $data['status'] = 2; // Set agency status to pending
            $data['admin_id'] = $this->user_id;
            $agency_id = $agency->insert($data);
        }

        $data['wp_user_id'] = $this->user_id;
        $data['pf_agency_id'] = $agency_id;
        $this->profile->id = $this->profile->insert($data);
        $this->profile->data = $this->profile->get($this->profile->id);

        $data['pf_profile_id'] = $this->profile->id;
        $this->contact->id = $this->contact->insert($data);

        if (isset($data['pf_agency_id'])) {
            $this->update_agency($data['pf_agency_id']);
        }
    }

    public function update_profile($data) {

        $data['wp_user_id'] = $this->user_id;

        if (isset($data['action']) && $data['action'] == 'edit_profile') {
            if (isset($this->profile->id)) {
                $data['pf_profile_id'] = $this->profile->id;
                $this->profile->update($data);
                if (isset($data['pf_agency_id'])) {
                    $this->update_agency($data['pf_agency_id']);
                }
            } else {
                $this->profile->id = $this->profile->insert($data);
            }
        }

        if (isset($data['action']) && $data['action'] == 'edit_contact') {

            $data['pf_profile_id'] = $this->profile->id;
            if (isset($this->contact->id)) {
                $this->contact->update($data);
            } else {
                $this->contact->id = $this->contact->insert($data);
            }
        }
    }

    public function delete_profile() {
        $this->profile->delete();
        $this->contact->delete();
        $agency_user = new MrtRelationAgencyUser();
        $agency_user->delete('pf_profile_id', $this->profile->id);
    }

    public function get_agencies() {
        $agency_user = new MrtRelationAgencyUser;
        return $agency_user->get_agencies($this->profile->id);
    }

    public function update_agency($agency_id) {
        // Add agency
        $agency_user = new MrtRelationAgencyUser;
        $agency_user->delete('pf_profile_id', $this->profile->id);

        $insert = [];
        $insert['wp_user_id'] = $this->user_id;
        $insert['pf_profile_id'] = $this->profile->id;
        $insert['pf_agency_id'] = $agency_id;
        $agency_user->insert($insert);
    }

    public function add_user_multiple_agency($agencies) {

        if (!isset($this->profile->id)) {
            return false;
        }

        $agency_user = new MrtRelationAgencyUser;
        $agency_user->delete('pf_profile_id', $this->profile->id);

        foreach ($agencies as $key => $agency_id) {
            $insert = [];
            $insert['wp_user_id'] = $this->user_id;
            $insert['pf_profile_id'] = $this->profile->id;
            $insert['pf_agency_id'] = $agency_id;
            $agency_user->insert($insert);
        }
    }

    public function set_preferences() {
        $this->preferences['type'] = $this->link->get_results("SELECT "
                . "ats.adoption_type_id, ats.adoption_type "
                . "FROM `pf_adoption_type_preference` atp "
                . "LEFT JOIN pf_adoption_type ats ON ats.adoption_type_id = atp.adoption_type_id "
                . "WHERE user_id = {$this->user_id} GROUP BY ats.adoption_type_id", ARRAY_A);

        $this->preferences['age'] = $this->link->get_results("SELECT "
                . "ag.Age_group_id, ag.Age_group "
                . "FROM `pf_age_group_preference` agp "
                . "LEFT JOIN pf_age_group ag ON ag.Age_group_id = agp.age_group_id "
                . "WHERE user_id = {$this->user_id} GROUP BY ag.Age_group_id", ARRAY_A);

        $this->preferences['ethnicity'] = $this->link->get_results("SELECT "
                . "e.ethnicity_id, e.ethnicity "
                . "FROM `pf_ethnicity_pref` ep "
                . "LEFT JOIN pf_ethnicity e ON e.ethnicity_id = ep.ethnicity_id "
                . "WHERE user_id = {$this->user_id} GROUP BY e.ethnicity_id ", ARRAY_A);

        $this->preferences['intro'] = $this->link->get_var("SELECT post_content "
                . "FROM `wp_posts` p INNER JOIN wp_postmeta pm ON pm.post_id = p.ID "
                . "WHERE p.post_author = {$this->user_id} AND pm.meta_key = 'letter_intro' AND pm.meta_value = 1 ");
    }

}
