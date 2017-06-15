<?php

class Profile extends ProfileBase {

    public $user_id;
    public $logged_in = false;

    public function __construct($user_id = null) {

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

        parent::__construct();

        $this->user = get_user_by('ID', $this->user_id);
        $this->private_profile = $this->get_profile();
        $this->profile = $this->get_public_profile();
        $this->profile_id = $this->profile['id'];
        $this->user_meta = get_userdata($this->user_id);
        $this->agency_settings = $this->get_agency_settings();
    }

    public function get_agency_settings() {

        if (in_array('adoption_agency', $this->user_meta->roles)) {
            $agency_id = $this->profile_id;
        } else {
            $agency_id = $this->profile['agency_id'];
        }

        // return get_user_meta($agency_id, 'mrt_agency_settings');

        return [
            'approvals' => [
                'auto_activation_profiles' => false
            ]
        ];
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

    public function save($data) {
        if ($this->agency_settings['approvals']['auto_activation_profiles']) {
            $this->update($data);
        } else {
            $this->set_draft($this->user_id, $data);
        }
    }

    /**
     * 
     * Get profile for logged in user
     * @return array
     */
    public function get_profile() {

        $draft = $this->get_draft($this->user_id);

        if (empty($draft)) {
            return $this->get_profileByWpUserId($this->user_id);
        }

        return $draft;
    }

    public function get_public_profile() {
        return $this->get_profileByWpUserId($this->user_id);
    }

}
