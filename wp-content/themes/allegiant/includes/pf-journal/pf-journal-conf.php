<?php

/**
 * Post Type : Letters
 */
class JournalConf {

    function __construct() {
        add_action('init', array($this, 'registerJournals'));
        add_filter('wp_dropdown_users', array($this, 'author_override'), 11);
        add_action('admin_init', array($this, 'disableComments'));
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
            'include_selected' => true,
            'class' => 'widefat',
           'show' => 'display_name_with_login'
        ));

        // put the original name back
        $output = preg_replace('/post_author_override_replaced/', 'post_author_override', $output);

        return $output;
    }

    //to disable comments from journal and letter post type
    function disableComments() {
        $post_types = array('journal', 'letter');
        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

}

new JournalConf;

