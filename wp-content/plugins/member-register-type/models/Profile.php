<?php

class Profile {

    private $link;

    function __construct() {
        global $wpdb;
        $this->link = $wpdb;
        $this->table = 'profiles';
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

    function user_info($user_id = null) {
        if (is_null($user_id) || $user_id == 0) {
            $empty = array_fill(0, count($this->fields), '');
            return array_combine($this->fields, $empty);
        }

        $row = $this->link->get_row("SELECT * FROM {$this->table} WHERE wp_user_id = {$user_id}", ARRAY_A);
        return (empty($row)) ? $this->user_info() : $row;
    }

    function delete($user_id) {
        $this->link->delete($this->table, ['wp_user_id' => $user_id]);
    }

}
