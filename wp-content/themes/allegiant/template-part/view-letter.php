<?php
global $user_ID;
?>
<style>
    #selected-assoc, #associate-img-container {
        margin: 25px 0 0 0;
        padding: 20px 0 0 0;
        width: 100%;
        font-size: 14px;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    #selected-assoc ul li {list-style:none;}
    .top-buffer { margin-top:20px; }
</style>
<div class="accordianItem">
    <div class="accordianItemHeader clearfix flexbox verticalAlign">
        <a href="#" class="buttons text-center"><i class="fa fa-pencil"></i></a>
        <div class="accordianItemIcons clearfix">
            <svg version="1.1" role="presentation" viewBox="0 0 1792 1792" class="fa-icon"><path fill="#ffffff" d="M768 1664h896v-640h-416q-40 0-68-28t-28-68v-416h-384v1152zM1024 224v-64q0-13-9.5-22.5t-22.5-9.5h-704q-13 0-22.5 9.5t-9.5 22.5v64q0 13 9.5 22.5t22.5 9.5h704q13 0 22.5-9.5t9.5-22.5zM1280 896h299l-299-299v299zM1792 1024v672q0 40-28 68t-68 28h-960q-40 0-68-28t-28-68v-160h-544q-40 0-68-28t-28-68v-1344q0-40 28-68t68-28h1088q40 0 68 28t28 68v328q21 13 36 28l408 408q28 28 48 76t20 88z"/></svg>
        </div>
        <div class="accordianItemHeaderText"><?php _e('Letters'); ?></div>
    </div>
    <div class="accordianItemContents">
        <div class="row articleRow articlePosts clearfix">
            <div class="col-xs-12 add-letter-container"><a id="add-letter" class="btn btn-default add-letter"><?php _e('Add new letter'); ?></a></div>
            <?php
            $is_couple = GenInc::is_couple($user_ID);
            $assoc_obj = LetterUI::getAlbumsPhotosByUid($user_ID);

            //echo '<pre>';
            //print_r($assoc_obj);
            //exit;
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
                        <a class="buttons text-center edit-letter" title="<?php _e('Edit this letter', ''); ?>" id="edit-letter-<?php echo $post_id; ?>"><i class="fa fa-pencil"></i></a>
                        <?php if (!key_exists($letter_label, $default_letter)) : ?>
                            <a class="buttons text-center delete-letter" title="<?php _e('Delete this letter', ''); ?>" id="delete-letter-<?php echo $post_id; ?>"><i class="fa fa-trash"></i></a>
                            <?php
                        else:
                            //unset($default_letter[$letter_label]);
                            array_push($disabledLbl, $letter_label);
                        endif;

                        if(!empty($letter_label) && $letter_label != 'other'){
                             array_push($disabledLbl, $letter_label);
                        }
                        ?>
                        <div class="articleItem">
                            <input type="hidden" id="selected_assoc_img<?php echo $post_id;?>" name="selected_assoc_img<?php echo $post_id;?>" value="<?php echo $assoc_images; ?>"/>
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

        </div>
    </div>
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
                                    <button title="<?php _e('Choose Asociative Images'); ?>" id="asociate-img" class="btn btn-default" type="button"><?php _e('Albums', ''); ?></button>
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
                                                    <?php //endif;  ?>
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
</div><!--//Accordian Item-->