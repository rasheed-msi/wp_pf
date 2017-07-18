<?php

function mrt_get_current_page_url() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function mrt_display_user_register() {

<<<<<<< HEAD
    global $mrt_profile;

    $gform = new Gform();
    $formhtmljq = new FormHtmlJq();
    $type = '';
    if (isset($_GET['rft']) && $_GET['rft'] == 1) {

        $form = $gform->set_form(AppForm::adoptive_family());
        echo $formhtmljq->create_form($form);
    } elseif (isset($_GET['rft']) && $_GET['rft'] == 2) {

        $form = $gform->set_form(AppForm::adoption_agency());
        echo $formhtmljq->create_form($form);
    } elseif (isset($_GET['rft']) && $_GET['rft'] == 3) {
        
        $form = $gform->set_form(AppForm::birth_mother());
        echo $formhtmljq->create_form($form);
    } elseif (!isset($_GET['rft'])) {

        $user = get_userdata(get_current_user_id());
        $user_info = $mrt_profile->user_info(get_current_user_id());

        if (@in_array('adoptive_family', $user->roles)) {

            $form = $gform->set_form(AppForm::adoptive_family(), $user_info);
            echo $formhtmljq->create_form($form);
        } elseif (@in_array('adoption_agency', $user->roles)) {

            $form = $gform->set_form(AppForm::adoption_agency(), $user_info);
            echo $formhtmljq->create_form($form);
        } elseif (@in_array('birth_mother', $user->roles)) {

            $form = $gform->set_form(AppForm::birth_mother(), $user_info);
            echo $formhtmljq->create_form($form);
        }
    }
}

=======
    $gform = new Gform();
    $formhtmljq = new FormHtmlJq();
    $type = '';

    $return = [];
    if (isset($_GET['rft']) && $_GET['rft'] == 1) {

        $post = [];
        if (isset($_POST['user_type'])) {
            $post = $_POST;
        }

        $form = $gform->set_form(AppForm::adoptive_family_register(), $post);
        $return['heading'] = 'Adoptive Family';
        $return['form_html'] = $formhtmljq->create_form($form);
    } elseif (isset($_GET['rft']) && $_GET['rft'] == 2) {

        
        
        $return['heading'] = 'Adoptive Agency';
        $return['form_html'] = $formhtmljq->create_form($gform->set_form(AppForm::adoption_agency_register(), (isset($_POST['user_type']))? $_POST : []));
    } elseif (isset($_GET['rft']) && $_GET['rft'] == 3) {

        $post = (isset($_POST['user_type']))? $_POST : [];
        $form = $gform->set_form(AppForm::birth_mother_register(), $post);
        $return['heading'] = 'Birth Mother';
        $return['form_html'] = $formhtmljq->create_form($form);
    } elseif (!isset($_GET['rft'])) {

        $mrtuser = new MrtUser;


        $return['heading'] = '';
        if ($mrtuser->user_role == 'adoptive_family') {
            $form = $gform->set_form(AppForm::adoptive_family_edit(), $mrtuser->profile->data);
            $return['form_html'] = $formhtmljq->create_form($form);
        } elseif ($mrtuser->user_role == 'adoption_agency') {
            $form = $gform->set_form(AppForm::adoption_agency_edit(), $mrtuser->profile->data);
            $return['form_html'] = $formhtmljq->create_form($form);
        } elseif ($mrtuser->user_role == 'birth_mother') {
            $form = $gform->set_form(AppForm::birth_mother_edit(), $mrtuser->profile->data);
            $return['form_html'] = $formhtmljq->create_form($form);
        }
    }

    return $return;
}

function mrt_display_register_adoptive_family() {
    $gform = new Gform();
    $formhtmljq = new FormHtmlJq();

    $post = [];
    if (isset($_POST['user_type'])) {
        $post = $_POST;
    }

    $form = $gform->set_form(AppForm::adoptive_family_register(), $post);
    $return['form_html'] = $formhtmljq->create_form($form);
    return $return;
}

function mrt_display_user_contact() {
    $gform = new Gform();
    $formhtmljq = new FormHtmlJq();
    $mrtuser = new MrtUser;
    
    $form_data = (isset($mrtuser->contact->data))? $mrtuser->contact->data : [];
    $country_selected = (isset($mrtuser->contact->data['Country']))? ['country_id' => $mrtuser->contact->data['Country']] : [];
        
    $form = $gform->set_form(AppForm::general_user_contact($country_selected), $form_data);
    $return['form_html'] = $formhtmljq->create_form($form);
    return $return;
}

function mrt_display_user_couple() {
    $gform = new Gform();
    $formhtmljq = new FormHtmlJq();
    $mrtuser = new MrtUser;
    $form_data = ($mrtuser->set_couple())? $mrtuser->couple->data : [];
    $form = $gform->set_form(AppForm::adoptive_family_couple(), $form_data);
    $return['form_html'] = $formhtmljq->create_form($form);
    return $return;
}
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a

function mrt_get_customposts($post_type, $display_count = -1) {
    $page = get_query_var('paged') ? get_query_var('paged') : 1;
    $offset = ( $page - 1 ) * $display_count;

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $display_count,
        'page' => $page,
        'offset' => $offset
    );

    $the_query = new WP_Query($args);
    return $the_query;
<<<<<<< HEAD
}
=======
}
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
