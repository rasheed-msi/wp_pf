<?php
add_shortcode('test-page', 'test_page');

function test_page() {
    global $mrt_profile;
    $gform = new Gform();
    $form = $gform->set_form(AppForm::new_adoptive_family());
    
    echo '<pre>', print_r($form), '</pre>';
    exit();
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
    }
}

add_action('mrt_edit_user_profile', 'mrt_profile_update', 10, 2);

function mrt_profile_update($user_id) {
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
}

add_action('after_setup_theme', 'mrt_remove_admin_bar');

function mrt_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

add_action('delete_user', 'mrt_delete_user');

function mrt_delete_user($user_id) {
    global $mrt_profile;
    $mrt_profile->delete($user_id);
}

add_shortcode('filter-page', 'page_filter_user');

function page_filter_user() {


    $mrt_muser = new Muser;
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
