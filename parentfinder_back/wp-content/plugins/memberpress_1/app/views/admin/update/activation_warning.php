<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<<<<<<< HEAD
<?php  /* if(defined('MEMBERPRESS_LICENSE_KEY') and isset($error)): ?>
  <div class="error" style="padding: 10px;"><?php printf(__('Error with MEMBERPRESS_LICENSE_KEY: %s', 'memberpress'), $error); ?></div>
<?php else: ?>
  <div class="error" style="padding: 10px;"><?php printf(__('<b>MemberPress hasn\'t been activated yet.</b> Go to the MemberPress %1$sactivation page%2$s to activate it.', 'memberpress'), '<a href="'.admin_url('admin.php?page=memberpress-updates').'">','</a>'); ?></div>
<?php endif; */ ?>
=======
<?php if(defined('MEMBERPRESS_LICENSE_KEY') and isset($error)): ?>
  <div class="error" style="padding: 10px;"><?php printf(__('Error with MEMBERPRESS_LICENSE_KEY: %s', 'memberpress'), $error); ?></div>
<?php else: ?>
  <div class="error" style="padding: 10px;"><?php printf(__('<b>MemberPress doesn\'t have a valid license key installed.</b> Go to the MemberPress %1$sactivation page%2$s to activate your license or go to %3$smemberpress.com%4$s to get one.', 'memberpress'), '<a href="'.admin_url('admin.php?page=memberpress-updates').'">','</a>','<a href="https://www.memberpress.com">','</a>'); ?></div>
<?php endif; ?>

>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
