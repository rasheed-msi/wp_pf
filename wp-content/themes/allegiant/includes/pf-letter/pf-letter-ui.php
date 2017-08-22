<?php

/**
 * Post Type : Letters
 */
class LetterUI {

    function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'pf_letter_scripts'));
        add_action('wp_ajax_delete_letter', array($this, 'pf_letter_del_func'));
        add_action('wp_ajax_nopriv_delete_letter', array($this, 'pf_letter_del_func'));
        add_action('wp_ajax_edit_letter', array($this, 'pf_letter_edit_func'));
        add_action('wp_ajax_nopriv_edit_letter', array($this, 'pf_letter_edit_func'));
    }

    function pf_letter_scripts() {
        wp_enqueue_style('pf-float-label', get_template_directory_uri() . '/includes/common/css/bootstrap-float-label.min.css');
        wp_enqueue_style('pf-letter', get_template_directory_uri() . '/includes/pf-letter/css/pf-letter.css', array('pf-float-label'), '5.8.2');
        wp_enqueue_script('tinymce_js', includes_url('js/tinymce/') . 'wp-tinymce.php', array('jquery'), false, true);
        wp_register_script('pf-letter', get_template_directory_uri() . '/includes/pf-letter/js/pf-letter.js', array('jquery', 'tinymce_js'), '1.1.8', true);
        wp_localize_script('pf-letter', 'letter_obj', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('pf-letter');
    }

    function pf_letter_del_func() {
        $letter_id = isset($_POST['letter-id']) && is_numeric($_POST['letter-id']) ? $_POST['letter-id'] : 0;
        if ($letter_id != 0) {
            global $wpdb;
            $status = $wpdb->update($wpdb->posts, array('post_status' => 'trash'), array('ID' => $letter_id));
            if ($status == 1) {
                echo json_encode(array('status' => 200, 'message' => 'Deleted successfully'));
            } else {
                echo json_encode(array('status' => 400, 'message' => 'Something went wrong. Please try later.'));
            }
        } else {
            echo json_encode(array('status' => 401, 'message' => 'Something went wrong. Please try later.'));
        }
        die();
    }

    function pf_letter_edit_func() {
        $letterAction = $_POST['jornal_action'];
        $letterTitle = wp_strip_all_tags($_POST['letter_title']);
        $letterContent = $_POST['jornal_content'];
        $author_id = get_current_user_id();
        $return_array = array();

        switch ($letterAction) {
            case 'add':
                $letter_post = array(
                    'post_title' => $letterTitle,
                    'post_content' => $letterContent,
                    'post_status' => 'publish',
                    'post_author' => $author_id,
                    'post_type' => 'letter'
                );

                // Insert the post into the database
                $status = wp_insert_post($letter_post);
                if (is_wp_error($status)) {
                    $return_array['status'] = 400;
                    $return_array['message'] = 'Some thing went worng. Please try later';
                } else {
                    $return_array['status'] = 200;
                    $return_array['message'] = 'Journal inserted successfully';
                    $return_array['data'] = get_post($status);
                }
                echo json_encode($return_array);
                die();
                break;
            case 'update':
                $letter_id = $_POST['letter_id'];

                if (is_numeric($letter_id) && $letter_id != 0) {

                    $letterObj = get_post($letter_id);
                    if ($letterObj->ID && $letterObj->post_author == $author_id) {
                        $update_obj = array(
                            'ID' => $letterObj->ID,
                            'post_title' => $letterTitle,
                            'post_content' => $letterContent,
                        );
                        $status = wp_update_post($update_obj);
                        if (is_wp_error($status)) {
                            $return_array['status'] = 400;
                            $return_array['message'] = 'Some thing went worng. Please try later';
                        } else {
                            $return_array['status'] = 200;
                            $return_array['message'] = 'Journal saved successfully';
                        }
                    } else {
                        $return_array['status'] = 400;
                        $return_array['message'] = 'Some thing went worng. Please try later';
                    }
                    echo json_encode($return_array);
                    die();
                }
                break;

                echo json_encode($return_array);
                die();
        }
    }

}

new LetterUI;