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
        $this->profile->id = $this->profile->getId($this->user_id);


        if (!is_null($this->profile->id)) {
            $this->contact->id = $this->contact->getId($this->profile->id);
            $this->profile->data = $this->profile->get($this->profile->id);
        }
        if (!is_null($this->contact->id)) {
            $this->contact->data = $this->contact->get($this->contact->id);
        }

        $this->profile->data['marital_status'] = (is_null($this->profile->data['couple_id'])) ? 'single' : 'couple';

        $this->user_meta = get_userdata($this->user_id);
        $this->user_role = $this->user_meta->roles[0];
    }

    public function set_couple() {
        if (is_null($this->profile->data['couple_id'])) {
            $this->profile->data['marital_status'] = false;
            return false;
        }

        $this->couple = new MrtProfile();
        $this->couple->id = $this->profile->data['couple_id'];
        $this->couple->data = $this->couple->get($this->couple->id);

        if (!empty($this->couple->data)) {
            $this->profile->data['marital_status'] = true;
        }
    }

    public function create_couple($data) {
        echo 'new couple set';
        $this->couple = new MrtProfile();
        $data['couple_id'] = $this->profile->id;
        $this->couple->id = $this->couple->insert($data);
        $this->couple->data = $this->couple->get($this->couple->id);
        $this->profile->update(['couple_id' => $this->couple->id]);
    }

    public function update_couple($data) {
        if (isset($this->couple->id)) {
            $this->couple->update($data);
            $this->couple->data = $this->couple->get($this->couple->id);
        } else {
            $this->create_couple($data);
        }
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

        if (isset($data['user_type']) && $data['user_type'] == 'adoption_agency') {

            $agency = new MrtAgencies();
            $data['status'] = 2; // Set agency status to pending
            $data['admin_id'] = $this->user_id;
            $agency_id = $agency->insert($data);
        }

        $data['wp_users_id'] = $this->user_id;
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

        $data['wp_users_id'] = $this->user_id;

        if (isset($data['action']) && $data['action'] == 'edit_profile') {
            if (is_null($this->profile->id)) {
                $this->profile->id = $this->profile->insert($data);
            } else {
                $data['pf_profile_id'] = $this->profile->id;
                $this->profile->update($data);
                if (isset($data['pf_agency_id'])) {
                    $this->update_agency($data['pf_agency_id']);
                }
            }
        }

        if (isset($data['action']) && $data['action'] == 'edit_contact') {

            if (is_null($this->contact->id)) {
                $data['pf_profile_id'] = $this->profile->id;
                $this->contact->id = $this->contact->insert($data);
            } else {
                $data['pf_profile_id'] = $this->profile->id;
                $this->contact->update($data);
            }
        }

        if (isset($data['action']) && $data['action'] == 'adoptive_family_couple') {
            $this->set_couple();
            $this->update_couple($data);
        }
    }

    public function delete_profile() {
        $this->profile->delete();
        $this->contact->delete();
        $agency_user = new MrtRelationAgencyUser();
        $agency_user->delete('pf_profile_id', $this->profile->id);
    }

    public function update_agency($agency_id) {
        // Add agency
        $agency_user = new MrtRelationAgencyUser;
        $agency_user->delete('pf_profile_id', $this->profile->id);

        $insert = [];
        $insert['wp_users_id'] = $this->user_id;
        $insert['pf_profile_id'] = $this->profile->id;
        $insert['pf_agency_id'] = $agency_id;
        $agency_user->insert($insert);
    }

}
