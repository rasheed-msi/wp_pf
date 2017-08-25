<?php

/**
 * Post Type : Letters
 */
class LetterConf {

    function __construct() {
        add_action('init', array($this, 'registerLetters'));
        add_action('add_meta_boxes', array($this, 'letterMetaBoxAdd'));
        add_action('admin_print_scripts', array($this, 'letterScriptForImages'));
        add_action('admin_print_styles', array($this, 'letterStyleForImages'));
        add_action('save_post', array($this, 'saveLetterMeta'), 10, 2);
        add_action('restrict_manage_posts', array($this, 'wpse45436_admin_posts_filter_restrict_manage_posts'));
        add_filter('parse_query', array($this, 'wpse45436_posts_filter'));
        add_filter('manage_letter_posts_columns', array($this, 'set_custom_edit_letter_columns'));
        add_action('manage_letter_posts_custom_column', array($this, 'custom_letter_column'), 10, 2);
    }

    function registerLetters() {
        $labels = array(
            'name' => _x('Letters', 'post type general name'),
            'singular_name' => _x('Letter', 'post type singular name'),
            'add_new' => _x('Add New', 'book'),
            'add_new_item' => __('Add New Letter'),
            'edit_item' => __('Edit Letter'),
            'new_item' => __('New Letter'),
            'all_items' => __('All Letters'),
            'view_item' => __('View Letter'),
            'search_items' => __('Search Letters'),
            'not_found' => __('No Letters found'),
            'not_found_in_trash' => __('No Letters found in the Trash'),
            'parent_item_colon' => '',
            'menu_name' => 'Letters'
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Holds Adoptive Parents/Birth Mothers Letters About Persons',
            'public' => true,
            'menu_position' => 5,
            'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments'),
            'has_archive' => true,
        );
        register_post_type('letter', $args);
    }

    function letterMetaBoxAdd() {
        add_meta_box('letter-meta', 'Letter Information', array($this, 'letterInfo'), 'letter', 'normal', 'high');
    }

    function letterInfo($post) {
        $letterMeta = get_post_meta($post->ID);
        $letterAbout = $letterMeta['letter_about_selection'][0];
        $letterIntro = $letterMeta['letter_is_intro'][0];
        $letterImages = maybe_unserialize($letterMeta['letter_images'][0]);

        /* letter intro */
        $letterIntroSel = array('yes' => 1, 'no' => 0);
        wp_nonce_field(plugin_basename(__FILE__), 'noncename_so_14445904');
        ?>
        <p>
            <label for="letter_is_intro"><b><?php _e('Is Intro:', ''); ?></b></label>
            <select name="letter_is_intro" id="letter_is_intro" class="widefat">
                <option value=""><?php _e('Select'); ?></option>
                <?php
                foreach ($letterIntroSel as $introKey => $introVal):
                    ?>
                    <option value="<?php echo $introVal; ?>" <?php selected($letterIntro, $introVal); ?>><?php echo ucfirst($introKey); ?></option>    
                    <?php
                endforeach;
                ?>

            </select>
        </p>
        <?php
        /* letter intro */

        /* letter about lable */
        $couple = array(
            'about_parent1' => __('About Parent 1', ''),
            'about_parent2' => __('About parent 2', ''),
            'about_them' => __('About them', ''),
            'agency_letter' => __('Agency Letter', ''),
        );

        $single = array(
            'agency_letter' => __('Agency Letter', ''),
            'expecting_mother_letter' => __('Expecting Mother Letter', ''),
            'about_parent1' => __('About Parent 1', ''),
        );
        ?>
        <p>
            <label for="letter_about_selection"><b><?php _e('Letter Label/Type:'); ?></b></label>
            <select name="letter_about_selection" id="letter_about_selection" class="widefat">
                <option value=""><?php _e('Select'); ?></option>
                <optgroup label="For Couple">
                    <?php foreach ($couple as $coKey => $co): ?>
                        <option value="<?php echo $coKey; ?>" <?php selected($letterAbout, $coKey); ?>><?php echo ucfirst($co); ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="For Single">
                    <?php foreach ($single as $sinKey => $sin): ?>
                        <option value="<?php echo $sinKey; ?>" <?php selected($letterAbout, $sinKey); ?>><?php echo ucfirst($sin); ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <option value="other" <?php selected($letterAbout, 'other') ?>><?php _e('Other', ''); ?></option>
            </select>
        </p>
        <?php
        /* letter about lable */

        /* associate images */
        ?>
        <div id="dynamic_form">
            <p><label for="letter_images[image_url][]"><b><?php _e('Associate Images:'); ?></b></label></p>
            <div id="field_wrap">
                <?php
                if (isset($letterImages['image_url'])) {
                    for ($i = 0; $i < count($letterImages['image_url']); $i++) {
                        ?>

                        <div class="field_row">

                            <div class="form_field">
                                <label><?php _e('Image URL:', ''); ?></label>
                                <input type="text"
                                       class="meta_image_url widefat"
                                       name="letter_images[image_url][]"
                                       value="<?php esc_html_e($letterImages['image_url'][$i]); ?>"
                                       />
                            </div>
                            <div class="image_wrap">
                                <img src="<?php esc_html_e($letterImages['image_url'][$i]); ?>" height="48" width="48" />
                            </div>

                            <div class="field_right">
                                <input class="button" type="button" value="Choose File" onclick="add_image(this)" />
                                <input class="button" type="button" value="Remove" onclick="remove_field(this)" />
                            </div>

                            <div class="clear" /></div> 
                    </div>
                    <?php
                } // endif
            } // endforeach
            ?>
        </div>
        <div style="display:none" id="master-row">
            <div class="field_row">
                <div class="form_field">
                    <label><?php _e('Image URL:', ''); ?></label>
                    <input class="meta_image_url widefat" value="" type="text" name="letter_images[image_url][]" />
                </div>
                <div class="image_wrap"></div> 
                <div class="field_right"> 
                    <input type="button" class="button button-secondary" value="Choose File" onclick="add_image(this)" />
                    <input class="button button-secondary" type="button" value="Remove" onclick="remove_field(this)" /> 
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div id="add_field_row">
            <input class="button" type="button" value="Add Field" onclick="add_field_row();" />
        </div>
        </div>

        <?php
        /* associate images */
    }

    /**
     * Print styles and scripts
     */
    function letterScriptForImages() {
        // Check for correct post_type
        global $post;
        
        if(!isset($post->post_type)){
            return;
        }
        
        if ('letter' != $post->post_type)// here you can set post type name
            return;

        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_register_script('pf-admin-letter', get_stylesheet_directory_uri() . '/includes/pf-letter/js/pf-admin-letter.js', array('jquery', 'media-upload', 'thickbox'));
        wp_enqueue_script('pf-admin-letter');
    }

    function letterStyleForImages() {
        wp_enqueue_style('pf-admin-letter', get_stylesheet_directory_uri() . '/includes/css/pf-admin-letter.css');
    }

    function saveLetterMeta($post_id, $post_object) {

        // Doing revision, exit earlier **can be removed**
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        
        if(!isset($post_object->post_type)){
            return;
        }

        // Doing revision, exit earlier
        if ('revision' == $post_object->post_type)
            return;

        // Verify authenticity
        if (!wp_verify_nonce($_POST['noncename_so_14445904'], plugin_basename(__FILE__)))
            return;

        // Correct post type
        if ('letter' != $_POST['post_type']) // here you can set post type name
            return;



        if (isset($_POST['letter_is_intro'])) {
            update_post_meta($post_id, 'letter_is_intro', $_POST['letter_is_intro']);
        }

        if ($_POST['letter_about_selection']) {
            update_post_meta($post_id, 'letter_about_selection', $_POST['letter_about_selection']);
        }


        if ($_POST['letter_images']) {
            // Build array for saving post meta
            $letterImages = array();
            for ($i = 0; $i < count($_POST['letter_images']['image_url']); $i++) {
                if ('' != $_POST['letter_images']['image_url'][$i]) {
                    $letterImages['image_url'][] = $_POST['letter_images']['image_url'][$i];
                }
            }

            if ($letterImages)
                update_post_meta($post_id, 'letter_images', $letterImages);
            else
                delete_post_meta($post_id, 'letter_images');
        }
        // Nothing received, all fields are empty, delete option
        else {
            delete_post_meta($post_id, 'letter_images');
        }
    }

    /**
     * First create the dropdown
     * make sure to change POST_TYPE to the name of your custom post type
     * 
     * @author Ohad Raz
     * 
     * @return void
     */
    function wpse45436_admin_posts_filter_restrict_manage_posts() {
        $type = 'post';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }

        //only add filter to post type you want
        if ('letter' == $type) {
            //change this to the list of values you want to show
            //in 'label' => 'value' format

            wp_dropdown_users(array(
                'name' => 'letter_author',
                'selected' => !empty($_GET['letter_author']) ? $_GET['letter_author'] : '',
                'include_selected' => true,
                'show_option_none' => 'Filter By User',
                'show' => 'display_name_with_login',
            ));
        }
    }

    /**
     * if submitted filter by post meta
     * 
     * make sure to change META_KEY to the actual meta key
     * and POST_TYPE to the name of your custom post type
     * @author Ohad Raz
     * @param  (wp_query object) $query
     * 
     * @return Void
     */
    function wpse45436_posts_filter($query) {
        global $pagenow;
        $type = 'post';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }
        if ('letter' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['letter_author']) && $_GET['letter_author'] != '') {
            $query->query_vars['author'] = $_GET['letter_author'];
        }
    }

    function set_custom_edit_letter_columns($columns) {
        $new = array();

        foreach ($columns as $key => $value) {
            if ($key == 'date') {  // when we find the date column
                $new['is_intro'] = __('Is Intro', '');  // put the tags column before it
            }
            $new[$key] = $value;
        }

        return $new;
    }

    // Add the data to the custom columns for the book post type:


    function custom_letter_column($column, $post_id) {
        switch ($column) {

            case 'is_intro' :
                $isIntro = get_post_meta($post_id, 'letter_is_intro', true);
                if ($isIntro == 0)
                    _e('No');
                elseif ($isIntro == 1)
                    _e('Yes');
                else
                    _e('NA');
                break;
        }
    }

}

new LetterConf;


/*
 function adminMenu() {
        add_options_page(
                'Parent Finder Settings', 'Parent Finder Settings', 'manage_options', 'parent_finder_settings', array($this, 'PFsettingsPage'));
    }

    function pFsettingsPage() {
        ?>
        <style> .pf-settings h1:before{vertical-align: unset;}</style>
        <div class="wrap pf-settings"><?php ?><h1 class="current wp-menu-image dashicons-before dashicons-admin-generic">Parent Finder Settings</h1><?php ?><form method="post" action="options.php">
                <?php settings_fields('my-cool-plugin-settings-group'); ?>
                <?php do_settings_sections('my-cool-plugin-settings-group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">New Option Name</th>
                        <td><input type="text" name="new_option_name" value="<?php echo esc_attr(get_option('new_option_name')); ?>" /></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Some Other Option</th>
                        <td><input type="text" name="some_other_option" value="<?php echo esc_attr(get_option('some_other_option')); ?>" /></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Options, Etc.</th>
                        <td><input type="text" name="option_etc" value="<?php echo esc_attr(get_option('option_etc')); ?>" /></td>
                    </tr>
                </table>

                <?php submit_button(); ?>

            </form>

            <fieldset>
                <legend>General Settings</legend>
                <form method="post" action=""> 
                    <input type="checkbox" name="like_fb_show" checked='checked' /> &nbsp; <span> Show Facebook Like Button </span>
                    <br /><br />
                    <input type="checkbox" name="like_gplus_show"  /> &nbsp; <span> Show Google+ Button </span>
                    <p><input type="submit" value="Save" class="button button-primary" name="submit" /></p>
                </form>
            </fieldset>
        </div>
        <?php ?></div><?php
    }
 */
 
