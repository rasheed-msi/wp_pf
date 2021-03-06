<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<h3><?php _e('Membership Information', 'memberpress'); ?></h3>

<table class="form-table mepr-form">
  <tbody>
    <tr>
      <th>
        <label for="mepr-geo-country"><?php _e('Signup Location', 'memberpress'); ?></label>
      </th>
      <td>
        <?php
          if($geo_country = get_user_meta($user->ID, 'mepr-geo-country', true)) {
            $countries = require(MEPR_I18N_PATH.'/countries.php');
            printf($countries[$geo_country]);
          }
          else {
            _e('Unknown', 'memberpress');
          }
        ?>
        <p class="description"><?php _e('Detected on user\'s initial signup', 'memberpress'); ?></p>
      </td>
    </tr>
  <?php
    MeprUsersHelper::render_editable_custom_fields($user);

    if(MeprUtils::is_mepr_admin()) { //Allow admins to see
    ?>
      <tr>
        <td colspan="2">
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&search='.urlencode($user->user_email)).'&search-field=email'; ?>" class="button"><?php _e("View Member's Transactions", "memberpress");?></a>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <a href="<?php echo admin_url('admin.php?page=memberpress-subscriptions&search='.urlencode($user->user_email)).'&search-field=email'; ?>" class="button"><?php _e("View Member's Subscriptions", "memberpress");?></a>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <a class="button mepr-resend-welcome-email" href="#" user-id="<?php echo $user->ID; ?>" mepr-nonce="<?php echo wp_create_nonce('mepr-resend-welcome-email'); ?>"><?php _e('Resend MemberPress Welcome Email', 'memberpress'); ?></a>&nbsp;&nbsp;<img src="<?php echo admin_url('images/loading.gif'); ?>" alt="<?php _e('Loading...', 'memberpress'); ?>" class="mepr-resend-welcome-email-loader" />&nbsp;&nbsp;<span class="mepr-resend-welcome-email-message">&nbsp;</span>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <h4><?php _e('Custom MemberPress Account Message', 'memberpress'); ?></h4>
          <?php wp_editor($user->user_message, MeprUser::$user_message_str); ?>
        </td>
      </tr>
    <?php
    }

    MeprHooks::do_action('mepr_extra_profile_fields', $user);
  ?>
  </tbody>
</table>

