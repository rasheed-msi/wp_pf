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
        add_action('wp_ajax_show_letter_section', array($this, 'pf_journal_show_letter_section_func'));
        add_action('wp_ajax_nopriv_show_letter_section', array($this, 'pf_journal_show_letter_section_func'));
    }

    function pf_letter_scripts() {
        wp_enqueue_style('pf-float-label', get_template_directory_uri() . '/includes/common/css/bootstrap-float-label.min.css');
        wp_enqueue_style('pf-letter', get_template_directory_uri() . '/includes/pf-letter/css/pf-letter.css', array('pf-float-label'), '5.8.28');
        wp_enqueue_media();
        wp_enqueue_script('tinymce_js', includes_url('js/tinymce/') . 'wp-tinymce.php', array('jquery'), false, true);
        wp_enqueue_script('pf-isotop', get_template_directory_uri() . '/includes/common/js/isotope.pkgd.min.js', array('jquery'), '1.1.8', true);
        wp_register_script('pf-letter', get_template_directory_uri() . '/includes/pf-letter/js/pf-letter.js', array('jquery', 'pf-isotop', 'jquery-ui-sortable', 'tinymce_js'), '1.1.19.23', true);
        wp_localize_script('pf-letter', 'letter_obj', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('pf-letter');
    }

    function pf_letter_del_func() {
        $letter_id = isset($_POST['letter-id']) && is_numeric($_POST['letter-id']) ? $_POST['letter-id'] : 0;
        if ($letter_id != 0) {
            $letter_intro = get_post_meta($letter_id, 'letter_intro', true);
            if ($letter_intro != 1) {
                global $wpdb;
                $status = $wpdb->update($wpdb->posts, array('post_status' => 'trash'), array('ID' => $letter_id));
                if ($status == 1) {
                    echo json_encode(array('status' => 200, 'message' => 'Deleted successfully'));
                } else {
                    echo json_encode(array('status' => 400, 'message' => 'Something went wrong. Please try later.'));
                }
            } else {
                echo json_encode(array('status' => 200, 'message' => 'Please uncheck before deleting letter'));
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
        $photo_ids = !empty($_POST['photo_ids']) ? $_POST['photo_ids'] : '';
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
                    update_post_meta($status, 'letter_slug', $letterLabel);
                    update_post_meta($status, 'letter_intro', $isIntro);
                    if ($isIntro == 1 && $currIntroId != 0) {
                        update_post_meta($currIntroId, 'letter_intro', 0);
                    }

                    update_post_meta($status, 'letter_image', $photo_ids);
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
                            update_post_meta($status, 'letter_intro', $isIntro);
                            update_post_meta($status, 'letter_image', $photo_ids);
                            if ($isIntro == 1 && $currIntroId != 0) {
                                update_post_meta($currIntroId, 'letter_intro', 0);
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

        $album_args = array('thumb', 0, 0, $user_ID);
        $album_query = "SELECT a.caption,a.pf_album_id, p.pf_photo_id,p.Uri,p.Ext, f.cloud_path,p.filename,f.cloud_filename
            FROM `pf_albums` a  
            INNER JOIN pf_photos p ON p.pf_album_id = a.pf_album_id
            LEFT JOIN pf_filestack_photos f ON f. pf_photo_id = p.pf_photo_id and f.view_type=%s  and  ifnull(f.deleteFlag,%d)=%d 
            WHERE  a.user_id = %d 
            ORDER BY a.pf_album_id,p.pf_photo_id";

        return $wpdb->get_results($wpdb->prepare($album_query, $album_args));
    }

    public function pf_journal_show_letter_section_func() {
        global $user_ID;
        ob_start();
        ?>
        <div class="col-xs-12 add-letter-container"><a id="add-letter" class="btn btn-default add-letter"><?php _e('Add new letter'); ?></a></div>
        <?php
        $is_couple = GenInc::is_couple($user_ID);
        $assoc_obj = self::getAlbumsPhotosByUid($user_ID);
        $parent1 = GenInc::parent1($user_ID);

        if ($is_couple == 1) {
            $parent2 = GenInc::parent2($user_ID);
            $default_letter = array(
                'about_him' => __('About ', '') . (!empty($parent1) ? $parent1 : __('Parent 1', '') ),
                'about_her' => __('About ', '') . (!empty($parent2) ? $parent2 : __('Parent 2', '') ),
                'about_them' => __('About them', ''),
                'agency_letter' => __('Agency Letter', ''),
            );
        } else {
            $default_letter = array(
                'agency_letter' => __('Agency Letter', ''),
                ' ' => __('Expecting Mother Letter', ''),
                'about_him' => __('About ', '') . (!empty($parent1) ? $parent1 : __('Parent 1', '') ),
            );
        }

        $common_letters = array(
            'our_family' => __('Our Family'),
            'our_holidays' => __('Our Holidays'),
            'our_home' => __('Our Home'),
            'our_promise' => __('Our Promise'),
            'our_traditions' => __('Our Traditions'),
            'our_vacations' => __('Our Vacations'),
            'other' => __('Other'),
            'thanksgiving' => __('Thanksgiving')
        );



        $letter_args = array('post_type' => 'letter', 'author' => $user_ID, 'post_status' => 'publish', 'meta_key' => 'letter_slug', 'orderby' => 'menu_order', 'order' => 'ASC');

        $letter_query = new WP_Query($letter_args);

        $disabledLbl = array();
        if ($letter_query->have_posts()) : while ($letter_query->have_posts()) : $letter_query->the_post();
                $post_id = get_the_ID();
                $letter_label = get_post_meta($post_id, 'letter_slug', true);
                $letter_intro = get_post_meta($post_id, 'letter_intro', true);
                $assoc_images = get_post_meta($post_id, 'letter_image', true);
                ?>
                <div data-id="<?php echo $post_id; ?>" class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-letter-container letter-<?php echo $post_id; ?>">
                    <div class="articleItem">
                        <div class="articleItemButtons clearfix text-right">
                            <a class="edit-letter" title="<?php _e('Edit this letter', ''); ?>" id="edit-letter-<?php echo $post_id; ?>"><i class="fa fa-pencil"></i></a>
                            <?php if (!key_exists($letter_label, $default_letter)) : ?>
                                <a class="delete-letter" title="<?php _e('Delete this letter', ''); ?>" id="delete-letter-<?php echo $post_id; ?>"><i class="fa fa-trash"></i></a>
                                <?php
                            else:
                                //unset($default_letter[$letter_label]);
                                array_push($disabledLbl, $letter_label);
                            endif;
                            ?>
                        </div>
                        <?php
                        if (!empty($letter_label) && $letter_label != 'other') {
                            array_push($disabledLbl, $letter_label);
                        }
                        ?>
                        <input type="hidden" id="selected_assoc_img<?php echo $post_id; ?>" name="selected_assoc_img<?php echo $post_id; ?>" value="<?php echo $assoc_images; ?>"/>
                        <?php if ($letter_intro == 1): ?>
                            <input type="hidden" id="is_intro_selected" name="is_intro_selected" value="<?php echo $post_id; ?>"/>
                        <?php endif; ?>
                        <div class="articleItemHead clearfix noBg"  id="letter-title-<?php echo $post_id; ?>"><?php echo (key_exists($letter_label, $default_letter)) ? $default_letter[$letter_label] : get_the_title(); ?></div>
                        <div class="articleItemContents noPad" id="letter-content-<?php echo $post_id; ?>">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div><!--//Post Item-->
                <?php
            endwhile;
        else:
            ?><?php
        endif;
        ?>
        <!-- Modal -->
        <div id="edit-letter-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php _e('Edit Letter', ''); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div style="padding: 3rem 1rem">
                            <!-- The form is placed inside the body of modal -->
                            <form id="letter-form" method="post" class="center-block">
                                <div id="letter-label-group" class="form-group has-float-label">
                                    <select class="form-control" id="letter-label" name="letter-label" >
                                        <!--<option value=""><?php _e('Select'); ?></option>-->
                                        <option disabled="disabled" value=""><?php _e('Select'); ?></option>
                                        <?php foreach ($default_letter as $letter_key => $letter_value) : ?>
                                            <?php
                                            $disabled = '';
                                            if (in_array($letter_key, $disabledLbl)):
                                                $disabled = 'disabled="disabled"';
                                            endif;
                                            ?> 
                                            <option <?php echo $disabled; ?> value="<?php echo $letter_key; ?>"><?php echo $letter_value; ?></option>
                                        <?php endforeach; ?>

                                        <?php foreach ($common_letters as $letter_key => $letter_value) : ?>
                                            <?php
                                            $disabled = '';
                                            if (in_array($letter_key, $disabledLbl)):
                                                $disabled = 'disabled="disabled"';
                                            endif;
                                            ?> 
                                            <option <?php echo $disabled; ?> value="<?php echo $letter_key; ?>"><?php echo $letter_value; ?></option>
                                        <?php endforeach; ?>
                            <!--<option value="other"><?php _e('Other'); ?></option>-->
                                    </select>
                                    <label for="letter-label"><?php _e('Letter Label'); ?></label>
                                </div>
                                <div id="letter-title-group" class="form-group has-float-label hide">
                                    <input type="hidden" name="letter-id" id="letter-id" />
                                    <input type="hidden" name="letter-action" id="letter-action" />
                                    <input type="hidden" name="photo-ids" id="photo-ids" />
                                    <input class="form-control" id="letter-title" type="text" placeholder="<?php _e('Letter Title'); ?>" />
                                    <label for="letter-title"><?php _e('Letter Title'); ?></label>
                                </div>
                                <div id="letter-title-text" class="form-group hide">
                                    <strong></strong>
                                </div>
                                <div class="form-group has-float-label">
                                    <textarea desabled="desabled" class="form-control" id="letter-editor" name="letter-editor" placeholder="<?php _e('Letter Content'); ?>"></textarea>
                                </div>
                                <div class="form-group checkbox">
                                    <label for="is_intro">
                                        <input id="is_intro" name="is_intro" type="checkbox" /> <?php _e('Set as Introduction'); ?>
                                    </label>
                                </div>
                                <div class="form-group has-float-label" id="selected-assoc">
                                    <label for="associated_images">Letter Images</label>
                                    <div class="row">
                                        <ul id="selected-assoc-list" class="thumbnails"></ul>
                                    </div>

                                </div>
                                <div class="row top-buffer">
                                    <div class="col-md-offset-1 col-md-10">
                                        <button title="<?php _e('Choose Associative Images'); ?>" id="asociate-img" class="btn btn-default" type="button"><?php _e('Albums', ''); ?></button>
                                        <button title="Update Image Selection" id="add-assoc-images" class="hide btn btn-success pull-right" type="button">Update Image Selection</button>
                                    </div>
                                </div>
                                <!--                            <div  class="row top-buffer hide">
                                                                <div class="col-md-12">-->
                                <div  id="associate-img-container" class="panel-group row top-buffer hide" style="overflow-y: auto;max-height: 200px;">
                                    <!--                                        <div class="panel-group-heading">
                                                                                <strong class="panel-group-title"><?php _e('Select Album Photo', ''); ?></strong>
                                                                            </div>-->
                                    <?php
                                    if (!empty($assoc_obj)):
                                        $albumId = '';
                                        $default_pan = 1;
                                        $file_header_not_exist = array('HTTP/1.0 404 Not Found', 'HTTP/1.0 302 Found');
                                        foreach ($assoc_obj as $key => $singel_item):
                                            if ($albumId !== $singel_item->pf_album_id):
                                                $albumId = $singel_item->pf_album_id;
                                                if ($default_pan == 0):
                                                    ?>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endif;
                            ?>
                            <div class="panel panel<?php ($default_pan === 1) ? "-default" : ""; ?>">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#associate-img-container" href="#collapse<?php echo $key; ?>"><?php echo $singel_item->caption; ?></a>
                                    </h4>
                                </div>
                                <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse <?php ($default_pan === 1) ? "in" : ""; ?>">
                                    <div class="panel-body">
                                        <div class="row">
                                            <?php
                                        else:
                                            if (strpos($singlephoto->Uri, 'cdn.filestackcontent.com') !== false):
                                                //if (@getimagesize(PF_PHOTOS_BUCKET_PATH . '/' . $albumId . '/album/' . $singel_item->pf_photo_id . '/thumb/' . $singel_item->pf_photo_id . '/' . $singel_item->cloud_filename)):
                                                ?>
                                                <div class="col-xs-3"><label class="image-checkbox"><img id="assoc-img-<?php echo $singel_item->pf_photo_id; ?>" class="img-responsive thumbnail" src="<?php echo PF_PHOTOS_BUCKET_PATH . '/' . $albumId . '/album/' . $singel_item->pf_photo_id . '/thumb/' . $singel_item->pf_photo_id . '/' . $singel_item->cloud_filename; ?>" alt="<?php echo strip_tags($singel_item->Uri); ?>" /><input type="checkbox" id="asso_image<?php echo $singel_item->pf_photo_id; ?>" name="asso_image[]" value="<?php echo $singel_item->pf_photo_id; ?>" /><i class="fa fa-check hidden"></i></label></div> 
                                                <?php //endif;   ?>
                                                <?php
                                            else:
                                                //if (@getimagesize(PF_PHOTOS_SERVER_PATH . '/' . $singel_item->filename . '_t.' . $singel_item->Ext)):
                                                ?>
                                                <div class="col-xs-3"><label class="image-checkbox"><img id="assoc-img-<?php echo $singel_item->pf_photo_id; ?>" class="img-responsive thumbnail" src="<?php echo PF_PHOTOS_SERVER_PATH . '/' . $singel_item->filename . '_t.' . $singel_item->Ext; ?>"  /><input type="checkbox" id="asso_image<?php echo $singel_item->pf_photo_id; ?>" name="asso_image[]" value="<?php echo $singel_item->pf_photo_id; ?>" /><i class="fa fa-check hidden"></i></label></div>
                                            <?php
                                            //endif;
                                            endif;
                                            ?>
                                        <?php
                                        endif;
                                        $default_pan = 0;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                else:
                    //no albums
                    ?>
                    <p class="text-center">No Albums found</p>
                <?php
                endif;
                ?>
            </div>
            <!--            </div>
            
                    </div>-->

        </form>
        </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default cancel" data-dismiss="modal" aria-hidden="true"><?php _e('Cancel'); ?></button>
            <button class="btn btn-primary edit-letter-btn" ><?php _e('Save'); ?></button>
        </div>
        </div>

        </div>
        </div>

        <div id="delete-letter-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php _e('Confirm', ''); ?></h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="del-letter-id" name="del-letter-id" />
                        <p><?php _e('Do you really want to delete this letter ?', ''); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default cancel" data-dismiss="modal"><?php _e('Cancel', ''); ?></button>
                        <button type="button" class="btn btn-primary delete-btn" ><?php _e('Remove Letter', ''); ?></button>
                    </div>
                </div>

            </div>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        echo json_encode(array('status' => 200, 'content' => $content));
        die();
    }

}

new LetterUI;
