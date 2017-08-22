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
            <?php
            $is_couple = GenInc::is_couple($user_ID);

            if ($is_couple == 1) {
                $default_letter = array(
                    'about_parent1' => __('About Parent 1', ''),
                    'about_parent2' => __('About parent 2', ''),
                    'about_them' => __('About them', ''),
                    'agency_letter' => __('Agency Letter', ''),
                );
            } else {
                $default_letter = array(
                    'agency_letter' => __('Agency Letter', ''),
                    'expecting_mother_letter' => __('Expecting Mother Letter', ''),
                    'about_parent1' => __('About Parent 1', ''),
                );
            }

            $letter_args = array('post_type' => 'letter', 'author' => $user_ID, 'post_status' => 'publish', 'meta_key' => 'letter_about_selection', 'orderby' => 'meta_value', 'order' => 'ASC');

            $letter_query = new WP_Query($letter_args);

//            echo '<pre>';print_r($letter_query);exit;
            if ($letter_query->have_posts()) : while ($letter_query->have_posts()) : $letter_query->the_post();
                    ?>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-letter-container letter-<?php the_ID(); ?>">
                        <a class="buttons text-center edit-letter" title="<?php _e('Edit this letter', ''); ?>" id="edit-post-<?php the_ID(); ?>"><i class="fa fa-pencil"></i></a>
                        <a class="buttons text-center delete-letter" title="<?php _e('Delete this letter', ''); ?>" id="delete-post-<?php the_ID(); ?>"><i class="fa fa-trash"></i></a>
                        <div class="articleItem">
                            <div class="articleItemHead clearfix noBg"  id="letter-title-<?php the_ID(); ?>"><?php the_title(); ?></div>
                            <div class="articleItemContents noPad" id="post-content-<?php the_ID(); ?>">
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
                            <div class="form-group has-float-label">
                                <input type="hidden" name="letter-id" id="letter-id" />
                                <input type="hidden" name="letter-action" id="letter-action" />
                                <input class="form-control" id="letter-title" type="text" placeholder="Letter Heading" />
                                <label for="letter-title">Title</label>
                            </div>
                            <div class="form-group has-float-label">
                                <textarea class="form-control" id="letter-editor" name="letter-editor" placeholder="Letter Content"></textarea>
                                <!--<label for="letter-editor">Your Letter</label>-->
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default cancel" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <button class="btn btn-primary edit-letter-btn" >Save</button>
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