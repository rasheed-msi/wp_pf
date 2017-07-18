<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<<<<<<< HEAD
<div id="header" style="width: 680px; padding 0; margin: 0 auto; text-align: left;">
  <h1 style="font-size: 30px; margin-bottom:4px;"><?php _e('Please complete your signup', 'memberpress'); ?></h1>
</div>
<div id="body" style="width: 600px; background: white; padding: 40px; margin: 0 auto; text-align: left;">
  <div id="receipt">
    <div class="section" style="display: block; margin-bottom: 24px;"><?php printf(__('Hi %s,', 'memberpress'), '{$user_first_name}'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php printf(__('We just saw that you weren\'t able to complete your signup for %1$s on %2$s.', 'memberpress'), '{$product_name}', '{$blog_name}'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php printf(__('We\'d be really sad if you missed out so we just wanted to drop you a line to let you to know that it\'s easy to <strong>%1$scomplete your signup today%2$s</strong>.', 'memberpress'), '<a href="{$subscr_renew_url}">', '</a>'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _e('Cheers!', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _e('The {$blog_name} Team', 'memberpress'); ?></div>
=======
<div id="header" style="width: 680px; padding: 0px; margin: 0 auto; text-align: left;">
  <h1 style="font-size: 30px; margin-bottom:4px;"><?php _ex('Please complete your signup', 'ui', 'memberpress'); ?></h1>
</div>
<div id="body" style="width: 600px; background: white; padding: 40px; margin: 0 auto; text-align: left;">
  <div id="receipt">
    <div class="section" style="display: block; margin-bottom: 24px;"><?php printf(_x('Hi %s,', 'ui', 'memberpress'), '{$user_first_name}'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php printf(_x('We just saw that you weren\'t able to complete your signup for %1$s on %2$s.', 'ui', 'memberpress'), '{$product_name}', '{$blog_name}'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php printf(_x('We\'d be really sad if you missed out so we just wanted to drop you a line to let you to know that it\'s easy to <strong>%1$scomplete your signup today%2$s</strong>.', 'ui', 'memberpress'), '<a href="{$subscr_renew_url}">', '</a>'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _ex('Cheers!', 'ui', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _ex('The {$blog_name} Team', 'ui', 'memberpress'); ?></div>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
  </div>
</div>

