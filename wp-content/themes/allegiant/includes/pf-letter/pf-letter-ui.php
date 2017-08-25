<?php

/**
 * Post Type : Letters
 */
define('PF_CURRENT_SERVER_PATH', 'http://parentfinder.com');
define('PF_PHOTOS_BUCKET_PATH', 'https://s3.amazonaws.com/cairs/pf');
define('PF_PHOTOS_SERVER_PATH', PF_CURRENT_SERVER_PATH . '/modules/boonex/photos/data/files');

class LetterUI {

    function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'pf_letter_scripts'));
        add_action('wp_ajax_delete_letter', array($this, 'pf_letter_del_func'));
        add_action('wp_ajax_nopriv_delete_letter', array($this, 'pf_letter_del_func'));
        add_action('wp_ajax_edit_letter', array($this, 'pf_letter_edit_func'));
        add_action('wp_ajax_nopriv_edit_letter', array($this, 'pf_letter_edit_func'));
        add_action('wp_ajax_update_letter_order', array($this, 'pf_update_letter_order_func'));
        add_action('wp_ajax_nopriv_update_letter_order', array($this, 'pf_update_letter_order_func'));
    }

    function pf_letter_scripts() {
        wp_enqueue_style('pf-float-label', get_template_directory_uri() . '/includes/common/css/bootstrap-float-label.min.css');
        wp_enqueue_style('pf-letter', get_template_directory_uri() . '/includes/pf-letter/css/pf-letter.css', array('pf-float-label'), '5.8.27');
        wp_enqueue_media();
        wp_enqueue_script('tinymce_js', includes_url('js/tinymce/') . 'wp-tinymce.php', array('jquery'), false, true);
        wp_register_script('pf-letter', get_template_directory_uri() . '/includes/pf-letter/js/pf-letter.js', array('jquery', 'jquery-ui-sortable', 'tinymce_js'), '1.1.19.1', true);
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
        $letterAction = $_POST['letter_action'];
        $letterTitle = wp_strip_all_tags($_POST['letter_title']);
        $letterContent = $_POST['letter_content'];
        $author_id = get_current_user_id();
        $currIntroId = $_POST['current_intro_id'];
        $isIntro = $_POST['is_intro'];
        $letterLabel = $_POST['letter_label'];

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
                
                //echo '<pre>';print_r($letter_post);exit;
                // Insert the post into the database
                $status = wp_insert_post($letter_post);
                
                if (is_wp_error($status) || $status == 0) {
                    $return_array['status'] = 400;
                    $return_array['message'] = 'Some thing went worng. Please try later';
                } else {
                    update_post_meta($status, 'letter_about_selection', $letterLabel);
                    update_post_meta($status, 'letter_is_intro', $isIntro);
                    if ($isIntro == 1 && $currIntroId != 0) {
                        update_post_meta($currIntroId, 'letter_is_intro', 0);
                    }

                    $return_array['status'] = 200;
                    $return_array['message'] = 'Letter inserted successfully';
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
                            'post_content' => $letterContent,
                        );
                        $status = wp_update_post($update_obj);
                        if (is_wp_error($status)) {
                            $return_array['status'] = 400;
                            $return_array['message'] = 'Some thing went worng. Please try later';
                        } else {
                            update_post_meta($status, 'letter_is_intro', $isIntro);
                            if ($isIntro == 1 && $currIntroId != 0) {
                                update_post_meta($currIntroId, 'letter_is_intro', 0);
                            }
                            $return_array['status'] = 200;
                            $return_array['message'] = 'Letter saved successfully';
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

    function pf_update_letter_order_func() {
        $sort_order = $_POST['letter_order'];
        $return_array = array();
        try {
            foreach ($sort_order as $key => $order) {
                wp_update_post(array('ID' => $order, 'menu_order' => $key));
            }
            $return_array['status'] = 200;
        } catch (Exception $exc) {
            $return_array['status'] = 400;
        }
        echo json_encode($return_array);
        die();
    }

    public static function getAlbumsPhotosByUid($user_ID) {
        global $wpdb;

        $album_args = array('thumb', $user_ID, 0, 0);
        $album_query = "SELECT a.caption,a.pf_album_id, p.pf_photo_id,p.Uri,p.Ext, f.cloud_path,p.filename,f.cloud_filename
                          FROM `pf_albums` a  
                          INNER JOIN pf_photos p ON p.pf_album_id = a.pf_album_id
                          LEFT JOIN pf_filestack_photos f ON f. pf_photo_id = p.pf_photo_id and f.view_type=%s 
                          WHERE  a.user_id = %d and  ifnull(f.deleteFlag,%d)=%d 
                          ORDER BY a.pf_album_id,p.pf_photo_id";

        return $wpdb->get_results($wpdb->prepare($album_query, $album_args));
    }

}

new LetterUI;


//add_filter( 'parse_query','bs_event_table_filter' );

function bs_event_table_filter( $query ) {
     global $wpdb;
    echo $wpdb->last_query;
    exit;
//    var_dump($query->last_query);
}

//pf_filestack_photos
//pf_photos