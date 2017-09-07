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
    add_rewrite_rule('^profile/([a-z0-9_-]+)?$', 'index.php?pagename=mrt-page-manager&user_name=$matches[1]&screen=public-profile', 'top');
    add_rewrite_rule('^agency-selection/?$', 'index.php?pagename=mrt-page-manager&screen=sc-agency-selection', 'top');
    // add_rewrite_rule('^unsubscribe/?$', 'index.php?pagename=mrt-page-manager&screen=unsubscribe', 'top');

    if (get_option('mrt_plugin_rewrite_rules_status') != 'mrt_flush_rewrite_rules') {
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
    $qvars[] = 'user_id';
    $qvars[] = 'user_name';
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
    } elseif ($screen == 'update-user-capabilities') {
        Dot::update_user_roles_capabilities();
    } elseif ($screen == 'create-table-profile') {
        $dbObj = new TableDef();
        $dbObj->profile();
    } elseif ($screen == 'public-profile') {

        $user_name = get_query_var('user_name');
        $user = get_user_by('login', $user_name);
        if (!$user) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit();
        }
        $mrtuser = new MrtUser($user->ID);
        $gform = new Gform();
        $list = new ListHtml();

        $return['form_html'] = $list->create_list($form);

        if ($mrtuser->user_role == 'adoptive_family') {
            $form = $gform->set_form(AppForm::adoptive_family_edit(), $mrtuser->profile->data);
            $form = $list->prepare_user_basic_details($form);
            echo '<h2>Basic Details</h2>';
            echo $list->create_list($form);

            echo '<h2>Contact Details</h2>';
            $form = $gform->set_form(AppForm::general_user_contact(['country_id' => $mrtuser->contact->data['Country']]), $mrtuser->contact->data);
            $form = $list->prepare_user_contact_details($form);
            echo $list->create_list($form);
        } elseif ($mrtuser->user_role == 'adoption_agency') {
            $form = $gform->set_form(AppForm::adoption_agency_edit(), $mrtuser->profile->data);
            $form = $list->prepare_user_basic_details($form);
            echo $list->create_list($form);

            echo '<h2>Contact Details</h2>';
            $form = $gform->set_form(AppForm::general_user_contact(['country_id' => $mrtuser->contact->data['Country']]), $mrtuser->contact->data);
            $form = $list->prepare_user_contact_details($form);
            echo $list->create_list($form);
        } elseif ($mrtuser->user_role == 'birth_mother') {
            $form = $gform->set_form(AppForm::birth_mother_register(), $mrtuser->profile->data);
            $form = $list->prepare_user_basic_details($form);
            echo $list->create_list($form);

            echo '<h2>Contact Details</h2>';
            $form = $gform->set_form(AppForm::general_user_contact(['country_id' => $mrtuser->contact->data['Country']]), $mrtuser->contact->data);
            $form = $list->prepare_user_contact_details($form);
            echo $list->create_list($form);
        }
    } elseif ($screen == 'sc-agency-selection') {
        
        if (isset($_POST['action']) && $_POST['action'] == 'agency_selection') {
            $save_user = new MrtUser();
            $save_user->add_user_multiple_agency($_POST['agencies']);
            $save_user->profile->update(['pf_agency_id' => $_POST['default']]);
        }
        $user = new MrtUser();
        $data['default'] = $user->profile->data['pf_agency_id'];
        $data['agencies'] = $user->get_agencies();
        include MRT_TEMPLATE_PATH . '/agency-selction.php';
    }
}

add_shortcode('mrt-page-manager', 'mrt_page_manager_display');
