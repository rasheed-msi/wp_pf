<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<?php do_action('mrt_edit_user_profile', $mepr_current_user->ID); ?>
<div class="mp_wrapper">
  <div id="mepr-account-welcome-message"><?php echo MeprHooks::apply_filters('mepr-account-welcome-message', do_shortcode($welcome_message), $mepr_current_user); ?></div>

  <?php if( !empty($mepr_current_user->user_message) ): ?>
    <div id="mepr-account-user-message">
      <?php echo MeprHooks::apply_filters('mepr-user-message', wpautop(do_shortcode($mepr_current_user->user_message)), $mepr_current_user); ?>
    </div>
  <?php endif; ?>

  <?php MeprView::render('/shared/errors', get_defined_vars()); ?>

  <form class="mepr-account-form mepr-form" id="mepr_account_form" action="" method="post" >
    <input type="hidden" name="mepr-process-account" value="Y" />
    <?php mrt_display_user_register(); ?>
    
    <div class="mp-form-row mepr_email">
      <div class="mp-form-label">
        <label for="user_email"><?php _e('Email:*', 'memberpress');  ?></label>
        <span class="cc-error"><?php _e('Invalid Email', 'memberpress'); ?></span>
      </div>
      <input type="email" id="user_email" name="user_email" class="mepr-form-input" value="<?php echo $mepr_current_user->user_email; ?>" required />
    </div>
    <?php
      MeprUsersHelper::render_custom_fields();
      MeprHooks::do_action('mepr-account-home-fields', $mepr_current_user);
    ?>

    <div class="mepr_spacer">&nbsp;</div>

    <input type="submit" name="mepr-account-form" value="<?php _e('Save Profile', 'memberpress'); ?>" class="mepr-submit mepr-share-button" />
    <img src="<?php echo admin_url('images/loading.gif'); ?>" style="display: none;" class="mepr-loading-gif" />
    <?php MeprView::render('/shared/has_errors', get_defined_vars()); ?>
  </form>

  <div class="mepr_spacer">&nbsp;</div>

  <a href="<?php echo $account_url.$delim.'action=newpassword'; ?>"><?php _e('Change Password', 'memberpress'); ?></a>

  <?php MeprHooks::do_action('mepr_account_home', $mepr_current_user); ?>
</div>

