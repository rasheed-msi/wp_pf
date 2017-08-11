<?php

class Agency_Approvals {

    function __construct() {
        add_shortcode('agency_approval_form', array(&$this, 'agency_approval_form_func'));
        add_action('wp_ajax_agency_approval', array(&$this, 'agency_approval_callback'));
        add_action('wp_ajax_nopriv_agency_approval', array(&$this, 'agency_approval_callback'));
    }

    function agency_approval_form_func() {
        wp_enqueue_script('jquery');
        ?>
        <form id="approve_data" name="approve_data"  method="post" class="form_advanced" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
            <input  type="hidden" name="agencyId" value="<?php echo ''; ?>" />            
            <input  type="hidden" name="action" value="agency_approval" />            
            <?php wp_nonce_field( 'agency_approval', 'agency_approval_nonce' );?>
            <div class="form_advanced_wrapper approve_data_wrapper">
                <table class="form_advanced_table" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="caption">
                                <?php _e('Enable auto-activation for Photos:'); ?> 
                            </td>
                            <td class="value">
                                <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_checkbox ">
                                    <input class="form_input_checkbox bx-def-font" type="checkbox" name="approve_media[]" value="photo">
                                </div>
                                <i class="warn sys-icon exclamation-sign" float_info=" "></i>
                                <div class="clear_both"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="caption">
                                <?php _e('Enable auto-activation for Videos:'); ?>
                            </td>
                            <td class="value">
                                <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_checkbox ">
                                    <input class="form_input_checkbox bx-def-font" type="checkbox" name="approve_media[]" value="video">
                                </div>
                                <i class="warn sys-icon exclamation-sign" float_info=" "></i>
                                <div class="clear_both"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="caption">
                                <?php _e('Enable auto-activation for Journals:'); ?>
                            </td>
                            <td class="value">
                                <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_checkbox ">
                                    <input class="form_input_checkbox bx-def-font" type="checkbox" name="approve_media[]" value="journal">
                                </div>
                                <i class="warn sys-icon exclamation-sign" float_info=" "></i>
                                <div class="clear_both"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="caption">
                                <?php _e('Enable auto-activation for Profiles:'); ?>
                            </td>
                            <td class="value">
                                <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_checkbox ">
                                    <input class="form_input_checkbox bx-def-font" type="checkbox" name="approve_media[]" value="profiles">
                                </div>
                                <i class="warn sys-icon exclamation-sign" float_info=" "></i>
                                <div class="clear_both"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="caption">
                                <?php _e('Enable auto-activation for Edited Profiles:'); ?>
                            </td>
                            <td class="value">
                                <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_checkbox ">
                                    <input class="form_input_checkbox bx-def-font" type="checkbox" name="approve_media[]" value="editedprofiles">
                                </div>
                                <i class="warn sys-icon exclamation-sign" float_info=" "></i>
                                <div class="clear_both"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="caption">
                                &nbsp;
                            </td>
                            <td class="value">
                                <div class="clear_both"></div>
                                <div class="input_wrapper input_wrapper_submit ">
                                    <div class="button_wrapper">
                                        <input class="form_input_submit bx-btn" type="submit" name="save" value="Save">
                                    </div>
                                </div>
                                <i class="warn sys-icon exclamation-sign" float_info=" "></i>
                                <div class="clear_both"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <?php
    }
    
    function agency_approval_callback(){
        
        if(!isset($_POST['agency_approval_nonce']) || !wp_verify_nonce($_POST['agency_approval_nonce'], 'agency_approval')){
            wp_redirect(home_url());die();
        }
        
        global $user_id;
        update_user_meta($user_id, 'pf_agency_settings', $_POST['approve_media']);
        
    }

}

new Agency_Approvals;
