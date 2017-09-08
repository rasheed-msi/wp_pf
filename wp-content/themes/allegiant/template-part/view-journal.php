<div id="journal-section" class="accordianItem">
    <div class="accordianItemHeader clearfix flexbox verticalAlign">
        <a href="#" class="buttons text-center"><i class="fa fa-pencil"></i></a>
        <div class="accordianItemIcons clearfix">
            <svg version="1.1" role="presentation" viewBox="0 0 1792 1792" class="fa-icon"><path fill="#ffffff" d="M888 1184l116-116-152-152-116 116v56h96v96h56zM1328 464q-16-16-33 1l-350 350q-17 17-1 33t33-1l350-350q17-17 1-33zM1408 1058v190q0 119-84.5 203.5t-203.5 84.5h-832q-119 0-203.5-84.5t-84.5-203.5v-832q0-119 84.5-203.5t203.5-84.5h832q63 0 117 25 15 7 18 23 3 17-9 29l-49 49q-14 14-32 8-23-6-45-6h-832q-66 0-113 47t-47 113v832q0 66 47 113t113 47h832q66 0 113-47t47-113v-126q0-13 9-22l64-64q15-15 35-7t20 29zM1312 320l288 288-672 672h-288v-288zM1756 452l-92 92-288-288 92-92q28-28 68-28t68 28l152 152q28 28 28 68t-28 68z"/></svg>
        </div>
        <div class="accordianItemHeaderText"><?php _e('Journals'); ?></div>
    </div>
    <div class="accordianItemContents">
        <div class="row articleRow articlePosts clearfix">
            <div class="loader"></div>
        </div><!--//Post Item-->
    </div>
    <!-- Modal -->
    <div id="edit-journal-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php _e('Edit Journal', ''); ?></h4>
                </div>
                <div class="modal-body">
                    <div style="padding: 3rem 1rem">
                        <!-- The form is placed inside the body of modal -->
                        <form id="journal-form" method="post" class="center-block">
                            <div class="form-group has-float-label">
                                <input type="hidden" name="journal-id" id="journal-id" />
                                <input type="hidden" name="journal-action" id="journal-action" />
                                <input class="form-control" id="journal-title" type="text" placeholder="Journal Heading" />
                                <label for="journal-title"><?php _e('Title'); ?></label>
                            </div>
                            <div class="form-group has-float-label">
                                <textarea class="form-control" id="journal-editor" name="journal-editor" placeholder="<?php _e('Journal Content'); ?>"></textarea>
                                <!--<label for="journal-editor">Your Journal</label>-->
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default cancel" data-dismiss="modal" aria-hidden="true"><?php _e('Cancel'); ?></button>
                    <button class="btn btn-primary edit-journal-btn" ><?php _e('Save'); ?></button>
                </div>
            </div>

        </div>
    </div>

    <div id="delete-journal-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php _e('Confirm', ''); ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="del-journal-id" name="del-journal-id" />
                    <p><?php _e('Do you really want to delete this journal ?', ''); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default cancel" data-dismiss="modal"><?php _e('Cancel', ''); ?></button>
                    <button type="button" class="btn btn-primary delete-btn" ><?php _e('Remove Journal', ''); ?></button>
                </div>
            </div>

        </div>
    </div>
</div><!--//Accordian Item-->