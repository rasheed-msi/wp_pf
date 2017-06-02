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

        if (in_array('adoptive_family', $user->roles)) {

            $form = $gform->set_form(AppForm::adoptive_family(), $user_info);
            echo $formhtmljq->create_form($form);
        } elseif (in_array('adoption_agency', $user->roles)) {

            $form = $gform->set_form(AppForm::adoption_agency(), $user_info);
            echo $formhtmljq->create_form($form);
        } elseif (in_array('birth_mother', $user->roles)) {

            $form = $gform->set_form(AppForm::birth_mother(), $user_info);
            echo $formhtmljq->create_form($form);
        }
    }
}


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
}