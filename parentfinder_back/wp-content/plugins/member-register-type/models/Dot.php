<?php

class Dot {

    public static function get_usersOfRole($role) {
        $args = [
            'role' => $role,
        ];
        return get_users($args);
    }

    public static function get_user_id_role($role) {

        $users = self::get_usersOfRole($role);

        $return = [];

        foreach ($users as $key => $user) {
            $return[$user->ID] = $user->display_name;
        }

        return $return;
    }

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

    public static function get_roles_select_option() {

        global $wp_roles;

        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);

        $return = [];
        $banned = ['administrator', 'editor', 'author', 'contributor', 'subscriber'];

        foreach ($editable_roles as $key => $roles) {

            if (in_array($key, $banned)) {
                continue;
            }

            $return[$key] = $roles['name'];
        }
        return $return;
    }

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

    

}
