<?php
add_shortcode('test-page', 'test_page');

function test_page() {
<<<<<<< HEAD
    global $mrt_profile;
    $gform = new Gform();
    $form = $gform->set_form(AppForm::new_adoptive_family());
    
    echo '<pre>', print_r($form), '</pre>';
    exit();
}

add_shortcode('dtest-page', 'mrt_update_role');

function mrt_update_role() {

    $result = add_role('birth_mother', __('Birth Mother'), array(
        'read' => true, // true allows this capability
        'edit_posts' => true, // Allows user to edit their own posts
        'edit_pages' => true, // Allows user to edit pages
        'edit_others_posts' => true, // Allows user to edit others posts not just their own
        'create_posts' => true, // Allows user to create new posts
        'manage_categories' => true, // Allows user to manage post categories
        'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
//        'edit_themes' => true, // false denies this capability. User can’t edit your theme
//        'install_plugins' => false, // User cant add new plugins
//        'update_plugin' => false, // User can’t update any plugins
//        'update_core' => false // user cant perform core updates
            )
    );

    $result = add_role('birth_mother', __('Birth Mother'), array(
        'read' => true, // true allows this capability
        'edit_posts' => true, // Allows user to edit their own posts
        'edit_pages' => true, // Allows user to edit pages
        'edit_others_posts' => true, // Allows user to edit others posts not just their own
        'create_posts' => true, // Allows user to create new posts
        'manage_categories' => true, // Allows user to manage post categories
        'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
//        'edit_themes' => true, // false denies this capability. User can’t edit your theme
//        'install_plugins' => false, // User cant add new plugins
//        'update_plugin' => false, // User can’t update any plugins
//        'update_core' => false // user cant perform core updates
            )
    );

    $result = add_role('birth_mother', __('Birth Mother'), array(
        'read' => true, // true allows this capability
        'edit_posts' => true, // Allows user to edit their own posts
        'edit_pages' => true, // Allows user to edit pages
        'edit_others_posts' => true, // Allows user to edit others posts not just their own
        'create_posts' => true, // Allows user to create new posts
        'manage_categories' => true, // Allows user to manage post categories
        'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
//        'edit_themes' => true, // false denies this capability. User can’t edit your theme
//        'install_plugins' => false, // User cant add new plugins
//        'update_plugin' => false, // User can’t update any plugins
//        'update_core' => false // user cant perform core updates
            )
    );

    $roles = get_option('wp_user_roles');
    echo '<pre>' . print_r($roles) . '<pre>';
}

add_action('user_register', 'mrt_user_register');

function mrt_user_register($user_id) {

    global $mrt_profile;

    if (isset($_POST['user_type'])) {
        $user = new WP_User($user_id);
        $user->remove_role('subscriber');
        $user->add_role($_POST['user_type']);

        $_POST['wp_user_id'] = $user_id;
        $mrt_profile->insert($_POST);

        if ($_POST['user_type'] == 'adoption_agency') {
            wp_update_user([
                'ID' => $user_id,
                'display_name' => $_POST['agency_attorney_name'],
            ]);
        }
=======

    $test = null;
    if (isset($test)) {
        echo 'set';
    } else {
        echo 'not set';
    }
}

add_shortcode('mrt-user-dashboard', 'mrt_display_user_dashboard');

function mrt_display_user_dashboard() {
    $mrt_user = new MrtUser();
    include MRT_TEMPLATE_PATH . '/user/dashboard.php';
}

add_filter('registration_errors', 'mrt_tml_registration_errors');

function mrt_tml_registration_errors($errors) {
    $mrt_errors = MrtProfile::validate($_POST);
    foreach ($mrt_errors as $key => $error) {
        $errors->add('empty_' . $key, $error);
    }
    $agency = new MrtAgencies();
    $agency_errors = $agency->validate($_POST);
    foreach ($agency_errors as $key => $error) {
        $errors->add('empty_' . $key, $error);
    }
    return $errors;
}

add_action('user_register', 'mrt_user_register');

function mrt_user_register($user_id) {
    if (isset($_POST['user_type'])) {
        $mrtuser = new MrtUser($user_id);
        $mrtuser->create_profile($_POST);
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
    }
}

add_action('mrt_edit_user_profile', 'mrt_profile_update', 10, 2);

function mrt_profile_update($user_id) {
<<<<<<< HEAD
    global $mrt_profile;
    if (isset($_POST['user_type'])) {
        $_POST['wp_user_id'] = $user_id;
        $mrt_profile->update($_POST);
    }

    if (isset($_POST['user_type']) && $_POST['user_type'] == 'adoption_agency') {
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $_POST['agency_attorney_name'],
        ]);
    }
=======
    $mrtuser = new MrtUser($user_id);
    $mrtuser->update_profile($_POST);
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
}

add_action('after_setup_theme', 'mrt_remove_admin_bar');

function mrt_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

add_action('delete_user', 'mrt_delete_user');

function mrt_delete_user($user_id) {
<<<<<<< HEAD
    global $mrt_profile;
    $mrt_profile->delete($user_id);
=======
    $profile = new MrtUser($user_id);
    $profile->delete_profile();
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
}

add_shortcode('filter-page', 'page_filter_user');

function page_filter_user() {

<<<<<<< HEAD

    $mrt_muser = new Muser;
=======
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
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
<<<<<<< HEAD
=======

function mrt_admin_menu_item() {
    add_menu_page('Agencies', 'Agencies', 'manage_options', 'mrt-agencies', 'mrt_admin_page_agencies');
}

add_action('admin_menu', 'mrt_admin_menu_item');

function mrt_admin_page_agencies() {
    include MRT_PLUGIN_PATH . 'templates/admin/agency.php';
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
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
