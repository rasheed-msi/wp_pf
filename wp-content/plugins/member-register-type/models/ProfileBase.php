<?php

class ProfileBase {

    private $link;
    private $table;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
        $this->table = 'wppf_profiles';
        $this->fields = [
            'first_name',
            'last_name',
            'agency_id',
            'gender',
            'marital_status',
            'wp_user_id',
            'agency_attorney_name',
            'agency_website',
            'phone',
            'street_address',
            'city',
            'state',
            'zip',
        ];
    }

    public function get($profile_id) {
        $row = $this->link->get_row("SELECT * FROM {$this->table} WHERE profile_id = {$profile_id}", ARRAY_A);
        return $row;
    }

    function get_profileByWpUserId($user_id) {
        return $this->link->get_row("SELECT * FROM {$this->table} WHERE wp_user_id = {$user_id}", ARRAY_A);
    }

    public function insert($args) {

        $insert = [];

        foreach ($this->fields as $value) {
            $insert[$value] = (isset($args[$value])) ? $args[$value] : '';
        }

        $this->link->insert(
                $this->table, $insert
        );
    }

    function update($args) {

        $update = [];

        foreach ($this->fields as $value) {

            if ($value == 'wp_user_id') {
                continue;
            }

            $update[$value] = (isset($args[$value])) ? $args[$value] : '';
        }
        
        
        $this->link->update(
                $this->table, $update, ['wp_user_id' => $args['wp_user_id']]
        );
    }

    function delete($user_id) {
        $this->link->delete($this->table, ['wp_user_id' => $user_id]);
    }

    public function get_draft($user_id) {
        return get_user_meta($user_id, 'mrt_profile_draft', true);
    }

    public function set_draft($user_id, $data) {
        
        update_user_meta($user_id, 'mrt_profile_draft', $data);
    }

    public function delete_draft($user_id) {
        delete_user_meta($user_id, 'mrt_profile_draft');
    }

    public function draftToProfile($user_id) {

        $draft = $this->get_draft($user_id);

        if (!empty($draft)) {
            $this->update($draft);
            $this->delete_draft($user_id);
        }

        return true;
    }

}
