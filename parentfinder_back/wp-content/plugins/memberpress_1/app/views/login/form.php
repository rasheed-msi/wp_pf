<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<<<<<<< HEAD
<div class="mp_wrapper">
  <?php if(MeprUtils::is_user_logged_in()): ?>

    <?php if(!isset($_GET['action']) || $_GET['action'] != 'mepr_unauthorized'): ?>
=======
<div class="mp_wrapper mp_login_form">
  <?php if(MeprUtils::is_user_logged_in()): ?>

    <?php if(!isset($_GET['mepr-unauth-page']) && (!isset($_GET['action']) || $_GET['action'] != 'mepr_unauthorized')): ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
      <?php if(is_page($login_page_id) && isset($redirect_to) && !empty($redirect_to)): ?>
        <script type="text/javascript">
          window.location.href="<?php echo $redirect_to; ?>";
        </script>
<<<<<<< HEAD
        <div class="mepr-already-logged-in">
          <?php printf(__('You\'re already logged in. %1$sLogout.%2$s', 'memberpress'), '<a href="'. wp_logout_url($redirect_to) . '">', '</a>'); ?>
=======
      <?php else: ?>
        <div class="mepr-already-logged-in">
          <?php printf(_x('You\'re already logged in. %1$sLogout.%2$s', 'ui', 'memberpress'), '<a href="'. wp_logout_url($redirect_to) . '">', '</a>'); ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
        </div>
      <?php endif; ?>
    <?php else: ?>
      <?php echo $message; ?>
    <?php endif; ?>

  <?php else: ?>
    <?php echo $message; ?>
    <!-- mp-login-form-start --> <?php //DON'T GET RID OF THIS HTML COMMENT PLEASE IT'S USEFUL FOR SOME REGEX WE'RE DOING ?>
    <form name="mepr_loginform" id="mepr_loginform" class="mepr-form" action="<?php echo $login_url; ?>" method="post">
      <div class="mp-form-row mepr_username">
        <div class="mp-form-label">
<<<<<<< HEAD
          <label for="log"><?php echo ($mepr_options->username_is_email)?__('Username or E-mail', 'memberpress'):__('Username', 'memberpress'); ?></label>
          <?php /* <span class="cc-error"><?php _e('Username Required', 'memberpress'); ?></span> */ ?>
=======
          <?php $uname_or_email_str = MeprHooks::apply_filters('mepr-login-uname-or-email-str', _x('Username or E-mail', 'ui', 'memberpress')); ?>
          <?php $uname_str = MeprHooks::apply_filters('mepr-login-uname-str', _x('Username', 'ui', 'memberpress')); ?>
          <label for="log"><?php echo ($mepr_options->username_is_email)?$uname_or_email_str:$uname_str; ?></label>
          <?php /* <span class="cc-error"><?php _ex('Username Required', 'ui', 'memberpress'); ?></span> */ ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
        </div>
        <input type="text" name="log" id="user_login" value="<?php echo (isset($_POST['log'])?$_POST['log']:''); ?>" />
      </div>
      <div class="mp-form-row mepr_password">
        <div class="mp-form-label">
<<<<<<< HEAD
          <label for="pwd"><?php _e('Password', 'memberpress'); ?></label>
          <?php /* <span class="cc-error"><?php _e('Password Required', 'memberpress'); ?></span> */ ?>
=======
          <label for="pwd"><?php _ex('Password', 'ui', 'memberpress'); ?></label>
          <?php /* <span class="cc-error"><?php _ex('Password Required', 'ui', 'memberpress'); ?></span> */ ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
        </div>
        <input type="password" name="pwd" id="user_pass" value="<?php echo (isset($_POST['pwd'])?$_POST['pwd']:''); ?>" />
      </div>
      <div>
<<<<<<< HEAD
        <label><input name="rememberme" type="checkbox" id="rememberme" value="forever"<?php checked(isset($_POST['rememberme'])); ?> /> <?php _e('Remember Me', 'memberpress'); ?></label>
      </div>
      <div class="mp-spacer">&nbsp;</div>
      <div class="submit">
        <input type="submit" name="wp-submit" id="wp-submit" class="button-primary mepr-share-button " value="<?php _e('Log In', 'memberpress'); ?>" />
=======
        <label><input name="rememberme" type="checkbox" id="rememberme" value="forever"<?php checked(isset($_POST['rememberme'])); ?> /> <?php _ex('Remember Me', 'ui', 'memberpress'); ?></label>
      </div>
      <?php MeprHooks::do_action('mepr-login-form-before-submit'); ?>
      <div class="mp-spacer">&nbsp;</div>
      <div class="submit">
        <input type="submit" name="wp-submit" id="wp-submit" class="button-primary mepr-share-button " value="<?php _ex('Log In', 'ui', 'memberpress'); ?>" />
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
        <input type="hidden" name="redirect_to" value="<?php echo esc_html($redirect_to); ?>" />
        <input type="hidden" name="mepr_process_login_form" value="true" />
        <input type="hidden" name="mepr_is_login_page" value="<?php echo ($is_login_page)?'true':'false'; ?>" />
      </div>
    </form>
    <div class="mp-spacer">&nbsp;</div>
    <div class="mepr-login-actions">
<<<<<<< HEAD
      <a href="<?php echo $forgot_password_url; ?>"><?php _e('Reset Password', 'memberpress'); ?></a>
=======
      <a href="<?php echo $forgot_password_url; ?>"><?php _ex('Forgot Password', 'ui', 'memberpress'); ?></a>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
    </div>
    <!-- mp-login-form-end --> <?php //DON'T GET RID OF THIS HTML COMMENT PLEASE IT'S USEFUL FOR SOME REGEX WE'RE DOING ?>

  <?php endif; ?>
</div>

