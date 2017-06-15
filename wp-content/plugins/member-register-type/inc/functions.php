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

    

    $gform = new Gform();
    $formhtmljq = new FormHtmlJq();
    $type = '';
    
    $return = [];
    if (isset($_GET['rft']) && $_GET['rft'] == 1) {

        $form = $gform->set_form(AppForm::adoptive_family());
        $return['heading'] = 'Adoptive Family';
        $return['form_html'] = $formhtmljq->create_form($form);
    } elseif (isset($_GET['rft']) && $_GET['rft'] == 2) {

        $form = $gform->set_form(AppForm::adoption_agency());
        $return['heading'] = 'Adoptive Agency';
        $return['form_html'] = $formhtmljq->create_form($form);
    } elseif (isset($_GET['rft']) && $_GET['rft'] == 3) {
        
        $form = $gform->set_form(AppForm::birth_mother());
        $return['heading'] = 'Birth Mother';
        $return['form_html'] = $formhtmljq->create_form($form);
    } elseif (!isset($_GET['rft'])) {

        $profile = new Profile;
        
        $return['heading'] = '';
        if (in_array('adoptive_family', $profile->user_meta->roles)) {

            $form = $gform->set_form(AppForm::adoptive_family(), $profile->private_profile);
            $return['form_html'] = $formhtmljq->create_form($form);
            
        } elseif (in_array('adoption_agency', $profile->user_meta->roles)) {

            $form = $gform->set_form(AppForm::adoption_agency(), $profile->private_profile);
            $return['form_html'] = $formhtmljq->create_form($form);
            
        } elseif (in_array('birth_mother', $profile->user_meta->roles)) {

            $form = $gform->set_form(AppForm::birth_mother(), $profile->private_profile);
            $return['form_html'] = $formhtmljq->create_form($form);
        }
    }
    
    return $return;
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