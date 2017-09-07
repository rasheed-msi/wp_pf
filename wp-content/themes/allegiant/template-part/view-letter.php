<?php
global $user_ID;
?>
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
//            echo '<pre>';
//            print_r($assoc_obj);
//            exit;
            $parent1 = GenInc::parent1($user_ID);
            if ($is_couple == 1) {
                $parent2 = GenInc::parent2($user_ID);
                $default_letter = array(
                    'about_parent1' => __('About ', '') . (!empty($parent1) ? $parent1 : __('Parent 1', '') ),
                    'about_parent2' => __('About ', '') . (!empty($parent2) ? $parent2 : __('Parent 2', '') ),
                    'about_them' => __('About them', ''),
                    'agency_letter' => __('Agency Letter', ''),
                );
            } else {
                $default_letter = array(
                    'agency_letter' => __('Agency Letter', ''),
                    'expecting_mother_letter' => __('Expecting Mother Letter', ''),
                    'about_parent1' => __('About ', '') . (!empty($parent1) ? $parent1 : __('Parent 1', '') ),
                );
            }

            $letter_args = array('post_type' => 'letter', 'author' => $user_ID, 'post_status' => 'publish', 'meta_key' => 'letter_about_selection', 'orderby' => 'menu_order', 'order' => 'ASC');

            $letter_query = new WP_Query($letter_args);

            $disabledLbl = array();
            if ($letter_query->have_posts()) : while ($letter_query->have_posts()) : $letter_query->the_post();
                    $post_id = get_the_ID();
                    $letter_label = get_post_meta($post_id, 'letter_about_selection', true);
                    $letter_intro = get_post_meta($post_id, 'letter_is_intro', true);
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
                        ?>
                        <div class="articleItem">
                            <?php if ($letter_intro == 1): ?>
                                <input type="hidden" id="is_intro_selected" name="is_intro_selected" value="<?php echo $post_id; ?>"/>
                            <?php endif; ?>
                            <div class="articleItemHead clearfix noBg"  id="letter-title-<?php echo $post_id; ?>"><?php the_title(); ?></div>
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
                                        if (in_array($letter_value, $disabledLbl)):
                                            $disabled = 'disabled="disabled"';
                                        endif;
                                        ?> 
                                        <option <?php echo $disabled; ?> value="<?php echo $letter_key; ?>"><?php echo $letter_value; ?></option>
                                    <?php endforeach; ?>
                                    <option value="other"><?php _e('Other'); ?></option>
                                </select>
                                <label for="letter-label"><?php _e('Letter Label'); ?></label>
                            </div>
                            <div id="letter-title-group" class="form-group has-float-label hide">
                                <input type="hidden" name="letter-id" id="letter-id" />
                                <input type="hidden" name="letter-action" id="letter-action" />
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
                            <div class="form-group checkbox">
                                <button title="<?php _e('Choose Asociative Images'); ?>" id="asociate-img" class="btn btn-default" type="button"><?php _e('Associative Image', ''); ?></button>
                            </div>

                            <div id="associate-img-container" class="panel-group hide">
                                <div class="panel-group-heading">
                                    <strong class="panel-group-title"><?php _e('Albums', ''); ?></strong>
                                </div>
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
                                                <div class="col-xs-3"><a href=""><img class="thumbnail" src="<?php echo PF_PHOTOS_BUCKET_PATH . '/' . $albumId . '/album/' . $singel_item->pf_photo_id . '/thumb/' . $singel_item->pf_photo_id . '/' . $singel_item->cloud_filename; ?>" alt="<?php echo strip_tags($singel_item->Uri); ?>" /></a></div>  
                                            <?php //endif; ?>
                                            <?php
                                        else:
                                            //if (@getimagesize(PF_PHOTOS_SERVER_PATH . '/' . $singel_item->filename . '_t.' . $singel_item->Ext)):
                                                ?>
                                                <div class="col-xs-3"><a class="thumbnail" href=""><img src="<?php echo PF_PHOTOS_SERVER_PATH . '/' . $singel_item->filename . '_t.' . $singel_item->Ext; ?>"  /></a></div> <!--alt="<?php echo $singel_item->Uri; ?>"-->
                                            <?php //endif;
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
            endif;
            ?>
        </div>
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