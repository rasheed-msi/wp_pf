<?php

class PF_Post_Types {

    function __construct() {
        add_action('init', array($this, 'pf_post_types'));
        add_filter('wp_dropdown_users', array($this, 'author_override'));
    }

    function pf_post_types() {
        $this->registerJournals();
        $this->registerLetters();
    }

    function registerJournals() {
        $labels = array(
            'name' => _x('Journals', 'post type general name'),
            'singular_name' => _x('Journal', 'post type singular name'),
            'add_new' => _x('Add New ', 'book'),
            'add_new_item' => __('Add New Journal'),
            'edit_item' => __('Edit Journal'),
            'new_item' => __('New Journal'),
            'all_items' => __('All Journals'),
            'view_item' => __('View Journal'),
            'search_items' => __('Search Journals'),
            'not_found' => __('No Journals found'),
            'not_found_in_trash' => __('No Journals found in the Trash'),
            'parent_item_colon' => '',
            'menu_name' => 'Journals'
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Holds Adoptive Parents/Birth Mothers Journals',
            'public' => true,
            'menu_position' => 5,
            'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments'),
            'has_archive' => true,
        );
        register_post_type('journal', $args);
    }

    function registerLetters() {
        $labels = array(
            'name' => _x('Letters', 'post type general name'),
            'singular_name' => _x('Letter', 'post type singular name'),
            'add_new' => _x('Add New', 'book'),
            'add_new_item' => __('Add New Letter'),
            'edit_item' => __('Edit Letter'),
            'new_item' => __('New Letter'),
            'all_items' => __('All Letters'),
            'view_item' => __('View Letter'),
            'search_items' => __('Search Letters'),
            'not_found' => __('No Letters found'),
            'not_found_in_trash' => __('No Letters found in the Trash'),
            'parent_item_colon' => '',
            'menu_name' => 'Letters'
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Holds Adoptive Parents/Birth Mothers Letters About Persons',
            'public' => true,
            'menu_position' => 5,
            'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments'),
            'has_archive' => true,
        );
        register_post_type('letter', $args);
    }

    // Filter to fix the Post Author Dropdown
    function author_override($output) {
        global $post, $user_ID;

        // return if this isn't the theme author override dropdown
        if (!preg_match('/post_author_override/', $output))
            return $output;

        // return if we've already replaced the list (end recursion)
        if (preg_match('/post_author_override_replaced/', $output))
            return $output;

        // replacement call to wp_dropdown_users
        $output = wp_dropdown_users(array(
            'echo' => 0,
            'name' => 'post_author_override_replaced',
            'selected' => empty($post->ID) ? $user_ID : $post->post_author,
            'include_selected' => true
        ));

        // put the original name back
        $output = preg_replace('/post_author_override_replaced/', 'post_author_override', $output);

        return $output;
    }

    
}

new PF_Post_Types;
