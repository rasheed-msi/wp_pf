<?php

/**
 * 
 * Change the permalink from default
 * these are predefined URLs that have special task on request
 * Define the rewrite rules
 */
function mrt_rewrite_rules() {
    add_rewrite_rule('^register-options/?$', 'index.php?pagename=mrt-page-manager&screen=register-options', 'top');
    add_rewrite_rule('^update-user-capabilities/?$', 'index.php?pagename=mrt-page-manager&screen=update-user-capabilities', 'top');
    add_rewrite_rule('^create-table-profile/?$', 'index.php?pagename=mrt-page-manager&screen=create-table-profile', 'top');
    //add_rewrite_rule('^unsubscribe/?$', 'index.php?pagename=mrt-page-manager&screen=unsubscribe', 'top');
    
    if(get_option('mrt_plugin_rewrite_rules_status') != 'mrt_flush_rewrite_rules'){
        flush_rewrite_rules();
        update_option('mrt_plugin_rewrite_rules_status', 'mrt_flush_rewrite_rules');
    }
}

add_action('init', 'mrt_rewrite_rules');

/**
 * 
 * Define all query variables
 * @param array $qvars
 * @return string
 */
function mrt_query_vars($qvars) {
    $qvars[] = 'screen';
    return $qvars;
}

add_filter('query_vars', 'mrt_query_vars');

/**
 * 
 * Display virtual page titles 
 * @param string $title title of the page
 * @param type $sep separator for the title
 * @return string
 */
function mrt_wp_title($title, $sep) {

    $virtual_page = array(
        'confirm-subscription' => 'Confirm your subscription',
        'unsubscribe' => 'Unsubscribe',
    );

    $slug = basename(mrt_get_current_page_url());

    if (array_key_exists($slug, $virtual_page)) {
        $string = $virtual_page[$slug];
        $title = $string . ' ' . $sep . ' ';
    }

    return $title;
}

add_filter('wp_title', 'mrt_wp_title', 10, 2);

/**
 * 
 * Display virtual page titles
 * @param string $title title of the page
 * @return string
 */
function mrt_remove_page_manager($title) {

    if (is_admin()) {
        return $title;
    }

    if ($title == 'mrt-page-manager') {

        $slug = basename(mrt_get_current_page_url());

        $virtual_page = array(
            'confirm-subscription' => 'Confirm your subscription',
            'unsubscribe' => 'Unsubscribe',
        );

        if (array_key_exists($slug, $virtual_page)) {
            $title = $virtual_page[$slug];
        } else {
            $title = '';
        }
    }
    return $title;
}

add_filter('the_title', 'mrt_remove_page_manager');


/**
 * 
 * Execute the function for the shortcode [mrt-page-manager]
 */
function mrt_page_manager_display() {



    $screen = get_query_var('screen');

    // example.com/confirm-subscription
 
    if ($screen == 'register-options') {
        include MRT_PLUGIN_PATH . 'templates/register-options.php';
    } 
    if ($screen == 'update-user-capabilities') {
        Dot::update_user_roles_capabilities();
    } 
    if ($screen == 'create-table-profile') {
        $dbObj = new TableDef();
        $dbObj->profile();
    }
    

}

add_shortcode('mrt-page-manager', 'mrt_page_manager_display');