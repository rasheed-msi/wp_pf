<?php

/**
 * Post Type : Journals
 */
class JournalUI {

    function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'pf_journal_scripts'));
        add_action('wp_ajax_delete_journal', array($this, 'pf_journal_del_func'));
        add_action('wp_ajax_nopriv_delete_journal', array($this, 'pf_journal_del_func'));
        add_action('wp_ajax_edit_journal', array($this, 'pf_journal_edit_func'));
        add_action('wp_ajax_nopriv_edit_journal', array($this, 'pf_journal_edit_func'));
        add_action('wp_ajax_show_journal_section', array($this, 'pf_journal_show_journal_section_func'));
        add_action('wp_ajax_nopriv_show_journal_section', array($this, 'pf_journal_show_journal_section_func'));
    }

    function pf_journal_scripts() {
        wp_enqueue_style('pf-float-label', get_template_directory_uri() . '/includes/common/css/bootstrap-float-label.min.css');
        wp_enqueue_style('pf-journal', get_template_directory_uri() . '/includes/pf-journal/css/pf-journal.css', array('pf-float-label'), '5.8.3');
        wp_enqueue_script('tinymce_js', includes_url('js/tinymce/') . 'wp-tinymce.php', array('jquery'), false, true);
        wp_enqueue_script('pf-isotop', get_template_directory_uri() . '/includes/common/js/isotope.pkgd.min.js', array('jquery'), '1.1.8', true);
        wp_register_script('pf-journal', get_template_directory_uri() . '/includes/pf-journal/js/pf-journal.js', array('jquery', 'tinymce_js', 'pf-isotop'), '1.1.10', true);
        wp_localize_script('pf-journal', 'journal_obj', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('pf-journal');
    }

    function pf_journal_del_func() {
        $journal_id = isset($_POST['journal-id']) && is_numeric($_POST['journal-id']) ? $_POST['journal-id'] : 0;
        if ($journal_id != 0) {
            global $wpdb;
            $status = $wpdb->update($wpdb->posts, array('post_status' => 'trash'), array('ID' => $journal_id));
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

    function pf_journal_edit_func() {
        $journalAction = $_POST['jornal_action'];
        $journalTitle = wp_strip_all_tags($_POST['journal_title']);
        $journalContent = $_POST['jornal_content'];
        $author_id = get_current_user_id();
        $return_array = array();

        switch ($journalAction) {
            case 'add':
                $journal_post = array(
                    'post_title' => $journalTitle,
                    'post_content' => $journalContent,
                    'post_status' => 'publish',
                    'post_author' => $author_id,
                    'post_type' => 'journal'
                );

                // Insert the post into the database
                $status = wp_insert_post($journal_post);
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
                $journal_id = $_POST['journal_id'];

                if (is_numeric($journal_id) && $journal_id != 0) {

                    $journalObj = get_post($journal_id);
                    if ($journalObj->ID && $journalObj->post_author == $author_id) {
                        $update_obj = array(
                            'ID' => $journalObj->ID,
                            'post_title' => $journalTitle,
                            'post_content' => $journalContent,
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

    function pf_journal_show_journal_section_func() {
        global $user_ID;
        ob_start();
        ?>
        <div class="col-xs-12"><a id="add-journal" class="btn btn-default add-journal"><?php _e('Add new journal'); ?></a></div>
        <?php
        $journal_args = array('post_type' => 'journal', 'author' => $user_ID, 'post_status' => 'publish', 'orderby' => 'date');
        $journal_query = new WP_Query($journal_args);
        if ($journal_query->have_posts()) : while ($journal_query->have_posts()) : $journal_query->the_post();
                ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost author-journal-container journal-<?php the_ID(); ?>">
                    <div class="articleItem">
                        <div class="articleItemButtons clearfix text-right">
                            <a class="edit-journal" title="<?php _e('Edit this journal', ''); ?>" id="edit-post-<?php the_ID(); ?>"><i class="fa fa-pencil"></i></a>
                            <a class="delete-journal" title="<?php _e('Delete this journal', ''); ?>" id="delete-post-<?php the_ID(); ?>"><i class="fa fa-trash"></i></a>
                        </div>
                        <div class="articleItemHead clearfix noBg"><span class="pull-left " id="post-title-<?php the_ID(); ?>"><?php the_title(); ?></span><span class="pull-right postDate"><?php echo get_the_date('F d, Y'); ?></span></div>
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
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        echo json_encode(array('status' => 200, 'content' => $content));
        die();
    }

}

new JournalUI;
