<?php
add_shortcode('test-page', 'test_page');

function test_page() {

    $test = null;
    if(isset($test)){
        echo 'set';
    }else{
        echo 'not set';
    }
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
    include MRT_PLUGIN_PATH . 'templates/admin/agency.php';
}
