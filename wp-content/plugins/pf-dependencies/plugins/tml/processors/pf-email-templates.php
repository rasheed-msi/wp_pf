<?php
if (!class_exists('PF_EmailTemplates')) :

    class PF_EmailTemplates {

        protected $options_key = 'pf_theme_my_login_email';
        protected $options = array();

        function __construct() {
            $this->loadOptions();
//            add_action('wp_enqueue_scripts', array($this, 'wpEnqueueScripts'));
            add_action('admin_menu', array($this, 'adminMenu'), 10);
            add_action('admin_init', array($this, 'adminInit'));
            add_action('load-tml_page_theme_my_login_email', array($this, 'loadTmlJsFunc'), 11);
        }

//        public function wpEnqueueScripts() {
//            wp_register_script('pf-tml-custom-email-admin', PF_DEP_PLUGIN_URL . 'assets/js/tml.js', array('jquery'), 5.1, true);
//        }

        public function adminMenu() {
            add_submenu_page(
                    'theme_my_login', __('Theme My Login Custom E-mail Settings', 'pf-txt-domain'), __('PF E-mail', 'pf-txt-domain'), 'manage_options', $this->options_key, array($this, 'settingsPage')
            );
            
            add_meta_box('agency_approved', __('Agency approved', 'pf-txt-domain'), array($this, 'agencyApproved'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('video_add_approval', __('Video Add Approval', 'pf-txt-domain'), array($this, 'videoAddApproval'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('video_add_notification', __('Video Add Notification', 'pf-txt-domain'), array($this, 'videoAddNotification'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('photo_delete_notification', __('Photo Delete Notification', 'pf-txt-domain'), array($this, 'photoDeleteNotification'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('photo_add_approval', __('Photo Add Approval', 'pf-txt-domain'), array($this, 'photoAddApproval'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('photo_add_notification', __('Photo Add Notification', 'pf-txt-domain'), array($this, 'photoAddNotification'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('blog_delete_notification', __('Blog Delete Notification', 'pf-txt-domain'), array($this, 'blogDeleteNotification'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('blog_edit_notification', __('Blog Edit Notification', 'pf-txt-domain'), array($this, 'blogEditNotification'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('blog_edit_approval', __('Blog Edit Approval', 'pf-txt-domain'), array($this, 'blogEditApproval'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('blog_add_notification', __('Blog Add Notification', 'pf-txt-domain'), array($this, 'blogAddNotification'), 'tml_page_' . $this->options_key, 'normal');
            add_meta_box('blog_addition_approval', __('Blog Addition Approval', 'pf-txt-domain'), array($this, 'blogAdditionApproval'), 'tml_page_' . $this->options_key, 'normal');
        }

        public function adminInit() {
            register_setting($this->options_key, $this->options_key, array($this, 'saveSettings'));
        }

        public function loadTmlJsFunc() {
            if (function_exists('wp_tiny_mce')) {
                wp_tiny_mce();
                wp_register_script('pf-tml-custom-email-admin', PF_DEP_PLUGIN_URL . '/tml/js/pf-email-templates.js', array('jquery'), 5.1, true);
                wp_enqueue_script('pf-tml-custom-email-admin');
                wp_localize_script('pf-tml-custom-email-admin', 'pf_tml_page', 'tml_email');
            }
        }

        public function saveSettings($settings) {
            $settings['new_agency']['admin_disable'] = isset($settings['new_agency']['admin_disable']) ? (bool) $settings['new_agency']['admin_disable'] : false;
            $settings = Theme_My_Login_Common::array_merge_recursive($this->getOptions(), $settings);
            return $settings;
        }

        public function settingsPage() {
            global $current_screen;
            wp_tiny_mce();
            wp_enqueue_script('postbox');
            wp_enqueue_script('pf-tml-custom-email-admin', PF_DEP_PLUGIN_URL . '/tml/js/pf-email-templates.js', array('jquery'), 5.1, true);
            wp_localize_script('pf-tml-custom-email-admin', 'pf_tml_page', 'pf_email');
            ?>
            <div class="wrap">
                <h2><?php esc_html_e('Parent Finder Custom E-mail Settings', 'pf-txt-domain'); ?></h2>
                    <?php settings_errors(); ?>

                <form method="post" action="options.php">
                    <?php
                    settings_fields($this->options_key);
                    wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
                    wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
                    ?>
                    <div id="<?php echo $this->options_key; ?>" class="metabox-holder">
                    <?php do_meta_boxes($current_screen->id, 'normal', null); ?>
                    </div>
            <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }

        public function agencyApproved() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency approved by admin.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_new_agency_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[new_agency][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_new_agency_mail_from_name" value="<?php echo $this->getOption(array('new_agency', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_new_agency_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[new_agency][mail_from]" type="text" id="<?php echo $this->options_key; ?>_new_agency_mail_from" value="<?php echo $this->getOption(array('new_agency', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_new_agency_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[new_agency][mail_content_type]" id="<?php echo $this->options_key; ?>_new_agency_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('new_agency', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('new_agency', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_new_agency_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[new_agency][title]" type="text" id="<?php echo $this->options_key; ?>_new_agency_title" value="<?php echo $this->getOption(array('new_agency', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_new_agency_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[new_agency][message]" id="<?php echo $this->options_key; ?>_new_agency_message" class="large-text" rows="10"><?php echo $this->getOption(array('new_agency', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function videoAddApproval() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon video added by the user for approval.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <!-- <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_video_approval_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[video_approval][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_video_approval_mail_from_name" value="<?php echo $this->getOption(array('video_approval', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_video_approval_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[video_approval][mail_from]" type="text" id="<?php echo $this->options_key; ?>_video_approval_mail_from" value="<?php echo $this->getOption(array('video_approval', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>-->
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_video_approval_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[video_approval][mail_content_type]" id="<?php echo $this->options_key; ?>_video_approval_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('video_approval', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('video_approval', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_video_approval_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[video_approval][title]" type="text" id="<?php echo $this->options_key; ?>_video_approval_title" value="<?php echo $this->getOption(array('video_approval', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_video_approval_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[video_approval][message]" id="<?php echo $this->options_key; ?>_video_approval_message" class="large-text" rows="10"><?php echo $this->getOption(array('video_approval', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function videoAddNotification() {
            ?>
            <p class="description">
            <?php _e('This e-mail will be sent to agency upon video added by the user.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>video_notify_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[video_notify][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>video_notify_from_name" value="<?php echo $this->getOption(array('video_notify', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>video_notify_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[video_notify][mail_from]" type="text" id="<?php echo $this->options_key; ?>video_notify_from" value="<?php echo $this->getOption(array('video_notify', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>video_notify_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[video_notify][mail_content_type]" id="<?php echo $this->options_key; ?>video_notify_content_type">
                            <option value="plain"<?php selected($this->getOption(array('video_notify', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('video_notify', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_video_notify_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[video_notify][title]" type="text" id="<?php echo $this->options_key; ?>_video_notify_title" value="<?php echo $this->getOption(array('video_notify', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_video_notify_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[video_notify][message]" id="<?php echo $this->options_key; ?>_video_notify_message" class="large-text" rows="10"><?php echo $this->getOption(array('video_notify', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function photoDeleteNotification() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency upon user deletes photo from their album.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_photo_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[delete_photo][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_delete_photo_mail_from_name" value="<?php echo $this->getOption(array('delete_photo', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_photo_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[delete_photo][mail_from]" type="text" id="<?php echo $this->options_key; ?>_delete_photo_mail_from" value="<?php echo $this->getOption(array('delete_photo', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_photo_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[delete_photo][mail_content_type]" id="<?php echo $this->options_key; ?>_delete_photo_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('delete_photo', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('delete_photo', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_photo_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[delete_photo][title]" type="text" id="<?php echo $this->options_key; ?>_delete_photo_title" value="<?php echo $this->getOption(array('delete_photo', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_photo_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[delete_photo][message]" id="<?php echo $this->options_key; ?>_delete_photo_message" class="large-text" rows="10"><?php echo $this->getOption(array('delete_photo', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function photoAddApproval() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon user add photo to their album', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_approval_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[photo_approval][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_photo_approval_mail_from_name" value="<?php echo $this->getOption(array('photo_approval', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_approval_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[photo_approval][mail_from]" type="text" id="<?php echo $this->options_key; ?>_photo_approval_mail_from" value="<?php echo $this->getOption(array('photo_approval', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_approval_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[photo_approval][mail_content_type]" id="<?php echo $this->options_key; ?>_photo_approval_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('photo_approval', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('photo_approval', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_approval_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[photo_approval][title]" type="text" id="<?php echo $this->options_key; ?>_photo_approval_title" value="<?php echo $this->getOption(array('photo_approval', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_approval_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[photo_approval][message]" id="<?php echo $this->options_key; ?>_photo_approval_message" class="large-text" rows="10"><?php echo $this->getOption(array('photo_approval', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function photoAddNotification() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency approved by admin.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_notify_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[photo_notify][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_photo_notify_mail_from_name" value="<?php echo $this->getOption(array('photo_notify', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_notify_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[photo_notify][mail_from]" type="text" id="<?php echo $this->options_key; ?>_photo_notify_mail_from" value="<?php echo $this->getOption(array('photo_notify', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_notify_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[photo_notify][mail_content_type]" id="<?php echo $this->options_key; ?>_photo_notify_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('photo_notify', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('photo_notify', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_notify_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[photo_notify][title]" type="text" id="<?php echo $this->options_key; ?>_photo_notify_title" value="<?php echo $this->getOption(array('photo_notify', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_photo_notify_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[photo_notify][message]" id="<?php echo $this->options_key; ?>_photo_notify_message" class="large-text" rows="10"><?php echo $this->getOption(array('photo_notify', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function blogDeleteNotification() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency delete there blog.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_blog_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[delete_blog][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_delete_blog_mail_from_name" value="<?php echo $this->getOption(array('delete_blog', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_blog_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[delete_blog][mail_from]" type="text" id="<?php echo $this->options_key; ?>_delete_blog_mail_from" value="<?php echo $this->getOption(array('delete_blog', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_blog_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[delete_blog][mail_content_type]" id="<?php echo $this->options_key; ?>_delete_blog_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('delete_blog', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('delete_blog', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_blog_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[delete_blog][title]" type="text" id="<?php echo $this->options_key; ?>_delete_blog_title" value="<?php echo $this->getOption(array('delete_blog', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_delete_blog_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[delete_blog][message]" id="<?php echo $this->options_key; ?>_delete_blog_message" class="large-text" rows="10"><?php echo $this->getOption(array('delete_blog', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function blogEditNotification() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency approved by admin.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_notify_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_notify][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_blog_notify_mail_from_name" value="<?php echo $this->getOption(array('blog_notify', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_notify_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_notify][mail_from]" type="text" id="<?php echo $this->options_key; ?>_blog_notify_mail_from" value="<?php echo $this->getOption(array('blog_notify', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_notify_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[blog_notify][mail_content_type]" id="<?php echo $this->options_key; ?>_blog_notify_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('blog_notify', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('blog_notify', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_notify_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_notify][title]" type="text" id="<?php echo $this->options_key; ?>_blog_notify_title" value="<?php echo $this->getOption(array('blog_notify', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_notify_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[blog_notify][message]" id="<?php echo $this->options_key; ?>_blog_notify_message" class="large-text" rows="10"><?php echo $this->getOption(array('blog_notify', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function blogEditApproval() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency approved by admin.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_approval_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_approval][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_blog_approval_mail_from_name" value="<?php echo $this->getOption(array('blog_approval', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_approval_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_approval][mail_from]" type="text" id="<?php echo $this->options_key; ?>_blog_approval_mail_from" value="<?php echo $this->getOption(array('blog_approval', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_approval_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[blog_approval][mail_content_type]" id="<?php echo $this->options_key; ?>_blog_approval_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('blog_approval', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('blog_approval', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_approval_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_approval][title]" type="text" id="<?php echo $this->options_key; ?>_blog_approval_title" value="<?php echo $this->getOption(array('blog_approval', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_approval_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[blog_approval][message]" id="<?php echo $this->options_key; ?>_blog_approval_message" class="large-text" rows="10"><?php echo $this->getOption(array('blog_approval', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function blogAddNotification() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency approved by admin.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_add][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_blog_add_mail_from_name" value="<?php echo $this->getOption(array('blog_add', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_add][mail_from]" type="text" id="<?php echo $this->options_key; ?>_blog_add_mail_from" value="<?php echo $this->getOption(array('blog_add', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[blog_add][mail_content_type]" id="<?php echo $this->options_key; ?>_blog_add_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('blog_add', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('blog_add', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_add][title]" type="text" id="<?php echo $this->options_key; ?>_blog_add_title" value="<?php echo $this->getOption(array('blog_add', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[blog_add][message]" id="<?php echo $this->options_key; ?>_blog_add_message" class="large-text" rows="10"><?php echo $this->getOption(array('blog_add', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }
        public function blogAdditionApproval() {
            ?>
            <p class="description">
                <?php _e('This e-mail will be sent to agency upon agency approved by admin.', 'pf-txt-domain'); ?>
            <?php _e('Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'pf-txt-domain'); ?>
            <?php _e('If any field is left empty, the default will be used instead.', 'pf-txt-domain'); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_approval_mail_from_name"><?php _e('From Name', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_add_approval][mail_from_name]" type="text" id="<?php echo $this->options_key; ?>_blog_add_approval_mail_from_name" value="<?php echo $this->getOption(array('blog_add_approval', 'mail_from_name')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_approval_mail_from"><?php _e('From E-mail', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_add_approval][mail_from]" type="text" id="<?php echo $this->options_key; ?>_blog_add_approval_mail_from" value="<?php echo $this->getOption(array('blog_add_approval', 'mail_from')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_approval_mail_content_type"><?php _e('E-mail Format', 'theme-my-login'); ?></label></th>
                    <td>
                        <select name="<?php echo $this->options_key; ?>[blog_add_approval][mail_content_type]" id="<?php echo $this->options_key; ?>_blog_add_approval_mail_content_type">
                            <option value="plain"<?php selected($this->getOption(array('blog_add_approval', 'mail_content_type')), 'plain'); ?>><?php _e('Plain Text', 'theme-my-login'); ?></option>
                            <option value="html"<?php selected($this->getOption(array('blog_add_approval', 'mail_content_type')), 'html'); ?>><?php _e('HTML', 'theme-my-login'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_approval_title"><?php _e('Subject', 'theme-my-login'); ?></label></th>
                    <td><input name="<?php echo $this->options_key; ?>[blog_add_approval][title]" type="text" id="<?php echo $this->options_key; ?>_blog_add_approval_title" value="<?php echo $this->getOption(array('blog_add_approval', 'title')); ?>" class="large-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="<?php echo $this->options_key; ?>_blog_add_approval_message"><?php _e('Message', 'theme-my-login'); ?></label></th>
                    <td>
                        <p class="description"><?php _e('Available Variables', 'theme-my-login'); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
                        <textarea name="<?php echo $this->options_key; ?>[blog_add_approval][message]" id="<?php echo $this->options_key; ?>_blog_add_approval_message" class="large-text" rows="10"><?php echo $this->getOption(array('blog_add_approval', 'message')); ?></textarea></p>
                    </td>
                </tr>
            </table>
            <?php
        }

        public function getOption($option, $default = false) {
            if (!is_array($option))
                $option = array($option);
            return self::_getOption($option, $default, $this->options);
        }

        private function _getOption($option, $default, &$options) {
            $key = array_shift($option);
            if (!isset($options[$key]))
                return $default;
            if (!empty($option))
                return self::_getOption($option, $default, $options[$key]);
            return $options[$key];
        }

        public function getOptions() {
            return $this->options;
        }
                
        public static function defaultOptions() {
		return array(
			'new_agency' => array(
				'mail_from' => '',
				'mail_from_name' => '',
				'mail_content_type' => '',
				'title' => '',
				'message' => '',
				'admin_mail_to' => '',
				'admin_mail_from' => '',
				'admin_mail_from_name' => '',
				'admin_mail_content_type' => '',
				'admin_title' => '',
				'admin_message' => ''
			),
			'retrieve_pass' => array(
				'mail_from' => '',
				'mail_from_name' => '',
				'mail_content_type' => '',
				'title' => '',
				'message' => ''
			),
			'reset_pass' => array(
				'admin_mail_to' => '',
				'admin_mail_from' => '',
				'admin_mail_from_name' => '',
				'admin_mail_content_type' => '',
				'admin_title' => '',
				'admin_message' => ''
			)
		);
	}
                
        public function loadOptions() {
            if ( method_exists( $this, 'default_options' ) )
                    $this->options = (array) $this->defaultOptions();

            if ( ! $this->options_key )
                    return;

            $options = get_option( $this->options_key, array() );
            $options = wp_parse_args( $options, $this->options );

            $this->options = $options;
        }


       public static function pfGetEmailTemplateByKey($key, $placeHolder = array(), $userId) {
            $optionKey = '';
            switch ($key) {
                case 'new_user':
                case 'retrieve_pass':
                case 'reset_pass':
                    $optionKey = 'theme_my_login_email';
                    break;
                case 'new_agency':
                case 'video_approval':
                case 'video_notify':
                case 'delete_photo':
                case 'photo_approval':
                case 'photo_notify':
                case 'delete_blog':
                case 'blog_notify':
                case 'blog_approval':
                case 'blog_add':
                case 'blog_add_approval':
                    $optionKey = 'pf_theme_my_login_email';
                    break;
                    deault:
                    $optionKey = 'theme_my_login_email';
                    break;
            }

            $options = get_option($optionKey, array());
            $selectedOpt = $options[$key];
            $selectedOpt['message'] = Theme_My_Login_Common::replace_vars($selectedOpt['message'], $userId, $placeHolder);
            return $selectedOpt;
        }
    }

    new PF_EmailTemplates;
endif;

