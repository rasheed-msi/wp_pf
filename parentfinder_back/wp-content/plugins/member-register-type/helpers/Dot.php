<?php

class Dot {

    public static function get_usersOfRole($role) {
        $args = [
            'role' => $role,
        ];
        return get_users($args);
    }

    /**
     * 
     * Get array containg user id and display name for given role
     * @param string $role
     * @return array
     */
    public static function get_user_id_role($role) {

        $users = self::get_usersOfRole($role);

        $return = [];

        foreach ($users as $key => $user) {
            $return[$user->ID] = $user->display_name;
        }

        return $return;
    }

    /**
     * 
     * Get array containg post id and post title for given posttype
     * @param string $post_type
     * @return array
     */
    public static function get_posts_select_option($post_type) {
        $return = [];
        $the_query = mrt_get_customposts($post_type);
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $return[get_the_ID()] = get_the_title();
            }
        }

        return $return;
    }

    /**
     * 
     * Get array containg available user roles
     * @return array
     */
    public static function get_roles_select_option() {

        global $wp_roles;

        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);

        $return = [];
        $banned = Stock::wordpress_user_roles();

        foreach ($editable_roles as $key => $roles) {

            if (in_array($key, $banned)) {
                continue;
            }

            $return[$key] = $roles['name'];
        }
        return $return;
    }

    /**
     * Plugin:Groups
     * Get the child groups for group with parent name
     * @param string $parent_name
     * @return array
     */
    public static function get_groups_select_option($parent_name) {
        global $wpdb;
        $group_table = $wpdb->prefix . 'groups_group';

        $registered_group = Groups_Group::read_by_name($parent_name);


        $children = $wpdb->get_results($wpdb->prepare(
                        "SELECT group_id, name FROM $group_table WHERE parent_id = %d", Groups_Utility::id($registered_group->group_id)
        ));

        $return = [];
        foreach ($children as $key => $value) {
            $return[$value->group_id] = $value->name;
        }

        return $return;
    }

    /**
     * 
     * Update user roles and capabilities
     */
    public static function update_user_roles_capabilities() {



        $roles = Stock::system_roles();
        $banned_roles = Stock::wordpress_user_roles();

        foreach ($roles as $key => $value) {

            if (in_array($key, $banned_roles)) {
                continue;
            }

            remove_role($key);
            add_role($key, $value['name'], self::set_array_value($value['capabilities'], true));
        }


        $db_roles = get_option('wp_user_roles');
        $required_roles = array_keys(Stock::role_list());

        foreach ($db_roles as $key => $value) {
            if (in_array($key, $banned_roles)) {
                continue;
            }
            
            if (!in_array($key, $required_roles)) {
                remove_role($key);
            }
        }

//        $roles = get_option('wp_user_roles');
//        echo '<pre>' , print_r($roles) , '<pre>';
//        exit();
    }

    /**
     * 
     * Set array values to a constant
     * @param array $array
     * @param string $value
     * @return array
     */
    public static function set_array_value($array, $value) {
        $return = [];
        foreach ($array as $val) {
            $return[$val] = $value;
        }

        return $return;
    }

    /**
     * 
     * Create array to use in html select options (key => 120, value => test)
     * converted to 120 => test
     * @param type $array
     * @return type
     */
    public static function set_array_key_value($array, $column1 = 'key', $column2 = 'value') {
        $return = [];
        foreach ($array as $key => $value) {
            if (!isset($value[$column1]) || !isset($value[$column2])) {
                continue;
            }
            $return[$value[$column1]] = $value[$column2];
        }

        return $return;
    }

    public static function get_table_select_option($table, $column1, $column2, $null_select = false) {
        global $wpdb;
        $records = $wpdb->get_results("SELECT {$column1}, {$column2} FROM {$table}", ARRAY_A);
        $results = self::set_array_key_value($records, $column1, $column2);
        if ($null_select) {
            $null_array = ['' => "-- select --"];
            $results = $null_array + $results;
        }
        return $results;
    }

    public static function get_states($country_id) {
        global $wpdb;
        return $wpdb->get_results("SELECT state_id, State FROM pf_states WHERE country_id = {$country_id}", ARRAY_A);
    }

}
