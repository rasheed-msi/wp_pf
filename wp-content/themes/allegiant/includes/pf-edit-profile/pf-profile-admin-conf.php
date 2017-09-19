<?php

/**
 * PF Admin Dashboard changes
 * @author Rasheed P.K <rasheed.pk@cairsolutions.com>
 */
class PFProfileAdminConf {

    function __construct() {
        add_action('edit_user_profile', array($this, 'pf_af_profile_fields'));
        add_action('edit_user_profile_update', array($this, 'pf_af_profile_fields_update'));
        add_filter('user_profile_update_errors', array($this, 'pf_af_profile_fields_update_error'), 10, 3);
    }

    function pf_af_profile_fields_update_error($errors, $update, $user) {
        if (!$user->roles)
            $roles = is_array($user->role) ? $user->role : array($user->role);
        else
            $roles = $user->roles;

        if (in_array('adoptive_family', $roles)) {
            $profile_no = isset($_POST['profile_no']) && is_numeric($_POST['profile_no']) ? $_POST['profile_no'] : '';
            $profile_yr = isset($_POST['profile_year']) && is_numeric($_POST['profile_year']) ? $_POST['profile_year'] : '';
            if (empty($profile_no) || empty($profile_yr)) {
                $errors->add('pf_error', __('<strong>ERROR</strong>: Profile No / Profile Year not be empty'));
            }
        }
        return $errors;
    }

    function pf_af_profile_fields_update($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            $user = get_userdata($user_id);
            if (in_array('adoptive_family', $user->roles)) {
                global $wpdb;
                $profile_no = isset($_POST['profile_no']) && is_numeric($_POST['profile_no']) ? $_POST['profile_no'] : '';
                $profile_yr = isset($_POST['profile_year']) && is_numeric($_POST['profile_year']) ? $_POST['profile_year'] : '';
                if (!empty($profile_no) && !empty($profile_yr)) {
                    $wpdb->update('pf_profiles', array('profile_no' => $profile_no, 'profile_year' => $profile_yr), array('wp_user_id' => $user_id), array('%d', '%d'), array('%d')
                    );
                }
            } else
                return;
        } else
            return;
    }

    function pf_af_profile_fields($user) {

        if (in_array('adoptive_family', $user->roles)) {
            ?>
            <h3><?php _e('Adoptive Family Profile Information', ''); ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="profile_no"><?php _e('Profile No', ''); ?></label></th>
                    <td>
                        <input type="text" name="profile_no" id="profile_no" placeholder="" value="<?php echo esc_attr(get_metadata('profile', $user->ID, 'profile_no')); ?>" class="regular-text" /><br />
                        <span class="description"></span>
                    </td>
                </tr>
                <tr>
                    <th><label for="profile_year"><?php _e('Profile Year', ''); ?></label></th>
                    <td>
                        <input type="text" name="profile_year" id="profile_year" value="<?php echo esc_attr(get_metadata('profile', $user->ID, 'profile_year')); ?>" class="regular-text" /><br />
                        <span class="description"></span>
                    </td>
                </tr>
            </table>
            <?php
        }
    }
}

new PFProfileAdminConf;
