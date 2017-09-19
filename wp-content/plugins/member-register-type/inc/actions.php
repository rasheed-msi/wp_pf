<?php
add_shortcode('test-page', 'test_page');

function test_page() {

    $test = new MrtApiTest;

    if (isset($_GET['action']) && $_GET['action'] == 'clear') {
        Dot::clear_log();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'setpass') {
        $test->setpass();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'print_table_column') {
        $col = Dot::get_columns('pf_videofiles');
        MrtPrint::array_list($col);
    }

    if (isset($_GET['action']) && $_GET['action'] == 'upload_file') {
        $test->upload_file();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'find_images') {
        $test->find_images();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'update_youtube_id') {
        $test->update_youtube_id();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'setYoutubeLinkOne') {
        $test->setYoutubeLinkOne();
    }

    
}

add_shortcode('mrt-user-dashboard', 'mrt_display_user_dashboard');

function mrt_display_user_dashboard() {
    $mrt_user = new MrtUser();
    MrtView::render('user/dashboard', compact('mrt_user'));
}

add_filter('registration_errors', 'mrt_tml_registration_errors');

function mrt_tml_registration_errors($errors) {
//    $mrt_errors = MrtProfile::validate($_POST);
//    foreach ($mrt_errors as $key => $error) {
//        $errors->add('empty_' . $key, $error);
//    }
    $agency = new MrtAgencies();
    $agency_errors = $agency->validate($_POST);
    foreach ($agency_errors as $key => $error) {
        $errors->add('empty_' . $key, $error);
    }
    return $errors;
}

add_action('user_register', 'mrt_user_register', 10);
add_action('pmpro_after_checkout', 'mrt_user_register');

function mrt_user_register($user_id) {

    if (isset($_POST['user_type'])) {
        $mrtuser = new MrtUser($user_id);
        $mrtuser->create_profile($_POST);
    }
}

add_action('mrt_edit_user_profile', 'mrt_profile_update', 10, 2);

function mrt_profile_update($user_id) {
    $mrtuser = new MrtUser($user_id);
    $mrtuser->update_profile($_POST);
}

add_action('after_setup_theme', 'mrt_remove_admin_bar');

function mrt_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

add_action('delete_user', 'mrt_delete_user');

function mrt_delete_user($user_id) {
    $profile = new MrtUser($user_id);
    $profile->delete_profile();
}

add_shortcode('filter-page', 'page_filter_user');

function page_filter_user() {

    $gform = new Gform();
    $formhtmljq = new FormHtmlJq();

    // memberpressgroup
    // memberpressproduct

    $form = $gform->set_form(AppForm::user_filter());
    ?>
    <?php foreach ($form['fields'] as $key => $value): ?>
        <h3><?php echo $value['label']; ?></h3>
        <ul>
            <?php foreach ($value['options'] as $key => $value): ?>
                <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
    <?php
}

add_action('admin_menu', 'mrt_admin_menu_item');

function mrt_admin_menu_item() {
    add_menu_page('Agencies', 'Agencies', 'manage_options', 'mrt-agencies', 'mrt_admin_page_agencies');
}

function mrt_admin_page_agencies() {
    MrtView::render('admin/agency');
}

add_filter('mrt_agency_selection', 'mrt_agency_selection_exec');

function mrt_agency_selection_exec($data) {

    if (isset($_POST['action']) && $_POST['action'] == 'agency_selection') {
        $save_user = new MrtUser();
        $save_user->add_user_multiple_agency($_POST['agencies']);
        $save_user->profile->update(['pf_agency_id' => $_POST['default']]);
    }

    $user = new MrtUser();
    $data['default'] = $user->profile->data['pf_agency_id'];
    $data['agencies'] = $user->get_agencies();
    $data['approved_agencies'] = State::get_approved_agencies_html_select();
    return $data;
}

add_action('wp_authenticate', 'mrt_custom_authentication');
add_action('wp_login', 'mrt_custom_authentication');

function mrt_custom_authentication($username) {

    global $wpdb;

    if (!username_exists($username)) {
        return;
    }

    $userinfo = get_user_by('login', $username);
    $auth = new MrtAuth;
    $token = $auth->set_token($userinfo->ID);
    setcookie('MrtToken', $token, time() + (3600 * 24), '/');
}

add_action('wp_logout', 'mrt_logout');

function mrt_logout() {
    setcookie('MrtToken', $token, time() - (3600 * 24), '/');
    wp_cache_flush();
}

add_action('wp', 'mrt_auth_page');

function mrt_auth_page() {

    $title = trim(get_the_title());

    $pages = [
        'Albums',
        'Dashboard',
        'Your Profile',
    ];

    $membership_level = [
        'Silver',
        'Gold',
        'Platinum',
    ];

    if (in_array($title, $pages)) {
        if (!is_user_logged_in()) {
            wp_redirect(site_url() . '/login');
        }


        if (!State::has_membership_access()) {
            wp_redirect(site_url() . '/membership-account/membership-levels');
            exit();
        }
    }
}

add_filter('tml_action_links', 'mrt_tml_action_links', 10, 2);

function mrt_tml_action_links($action_links, $args) {

    foreach ($action_links as $key => $value) {
        if ($value['title'] == 'Register') {
            $value['url'] = site_url() . '/register-options';
        }
        $action_links[$key] = $value;
    }

    return $action_links;
}

add_filter('wsl_render_auth_widget_alter_authenticate_url', 'mrt_wsl_render_auth_widget_alter_authenticate_url', 10, 2);

function mrt_wsl_render_auth_widget_alter_authenticate_url($authenticate_url) {
    if (isset($_GET['rft'])) {
        // $authenticate_url = $authenticate_url . '&rft=' . $_GET['rft'];
    }

    return $authenticate_url;
}

add_filter('wsl_hook_process_login_alter_wp_insert_user_data', 'mrt_wsl_alter_insert_user_data', 10, 2);

function mrt_wsl_alter_insert_user_data($userdata) {

    if (!isset($_REQUEST['redirect_to'])) {
        return $userdata;
    }

    $redirect_to = $_REQUEST['redirect_to'];
    $parts = parse_url($redirect_to);
    parse_str($parts['query'], $query);

    if (!isset($query['rft'])) {
        return $userdata;
    }


    if ($query['rft'] == 1) {
        $userdata['role'] = 'adoptive_family';
    } elseif ($query['rft'] == 2) {
        $userdata['role'] = 'adoption_agency';
    } elseif ($query['rft'] == 3) {
        $userdata['role'] = 'birth_mother';
    }

    return $userdata;
}

add_filter('wsl_hook_process_login_after_wp_insert_user', 'mrt_after_wp_insert_user', 10, 2);

function mrt_after_wp_insert_user($user_id) {

    $mrtuser = new MrtUser($user_id);
    $data = [
        'first_name' => get_user_meta($user_id, 'first_name', true),
        'last_name' => get_user_meta($user_id, 'last_name', true),
        'user_type' => $mrtuser->user_role,
    ];
    $mrtuser->create_profile($data);
}

add_action('tml_new_user_activated', 'mrt_tml_new_user_activated');

function mrt_tml_new_user_activated($user_id) {
    $u = new WP_User($user_id);
    $u->remove_role(get_option('default_role'));
    $role = get_user_meta($user_id, 'mrt_user_role_register', true);
    $u->add_role($role);
}
