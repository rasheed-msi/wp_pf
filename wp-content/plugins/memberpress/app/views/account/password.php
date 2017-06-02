<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<div class="mp_wrapper">
  <?php MeprView::render('/shared/errors', get_defined_vars()); ?>

  <form action="" class="mepr-newpassword-form mepr-form" method="post" novalidate>
    <input type="hidden" name="plugin" value="mepr" />
    <input type="hidden" name="action" value="updatepassword" />

    <div class="mp-form-row mepr_new_password">
      <label for="mepr-new-password"><?php _e('New Password', 'memberpress'); ?></label>
      <input type="password" name="mepr-new-password" class="mepr-form-input" required />
    </div>
    <div class="mp-form-row mepr_confirm_password">
      <label for="mepr-confirm-password"><?php _e('Confirm New Password', 'memberpress'); ?></label>
      <input type="password" name="mepr-confirm-password" class="mepr-form-input" required />
    </div>

    <div class="mepr_spacer">&nbsp;</div>

    <input type="submit" name="new-password-submit" value="<?php _e('Update Password', 'memberpress'); ?>" class="mepr-submit" />
    <?php _e('or', 'memberpress'); ?>
    <a href="<?php echo $mepr_options->account_page_url(); ?>"><?php _e('Cancel', 'memberpress'); ?></a>
    <img src="<?php echo admin_url('images/loading.gif'); ?>" style="display: none;" class="mepr-loading-gif" />
    <?php MeprView::render('/shared/has_errors', get_defined_vars()); ?>
  </form>

  <?php MeprHooks::do_action('mepr_account_password', $mepr_current_user); ?>
</div>

