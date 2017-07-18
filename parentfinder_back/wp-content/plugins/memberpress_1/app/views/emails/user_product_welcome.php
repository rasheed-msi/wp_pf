<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<<<<<<< HEAD
<div id="header" style="width: 680px; padding 0; margin: 0 auto; text-align: left;">
  <h1 style="font-size: 30px; margin-bottom: 4px;"><?php _e('Thanks for Purchasing {$product_name}', 'memberpress'); ?></h1>
</div>
<div id="body" style="width: 600px; background: white; padding: 40px; margin: 0 auto; text-align: left;">
  <div id="receipt">
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _e('You can login here: {$login_page}', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _e('Using this username and password:', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;">
      <table style="clear: both;" class="transaction">
        <tr><th style="text-align: left;"><?php _e('Username:', 'memberpress'); ?></th><td>{$username}</td></tr>
        <tr><th style="text-align: left;"><?php _e('Password:', 'memberpress'); ?></th><td><?php _e('*** Password you set during signup ***', 'memberpress'); ?></td></tr>
      </table>
    </div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _e('Cheers!', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _e('The {$blog_name} Team', 'memberpress'); ?></div>
=======
<div id="header" style="width: 680px; padding: 0px; margin: 0 auto; text-align: left;">
  <h1 style="font-size: 30px; margin-bottom: 4px;"><?php _ex('Thanks for Purchasing {$product_name}', 'ui', 'memberpress'); ?></h1>
</div>
<div id="body" style="width: 600px; background: white; padding: 40px; margin: 0 auto; text-align: left;">
  <div id="receipt">
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _ex('You can login here: {$login_page}', 'ui', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _ex('Using this username and password:', 'ui', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;">
      <table style="clear: both;" class="transaction">
        <tr><th style="text-align: left;"><?php _ex('Username:', 'ui', 'memberpress'); ?></th><td>{$username}</td></tr>
        <tr><th style="text-align: left;"><?php _ex('Password:', 'ui', 'memberpress'); ?></th><td><?php _ex('*** Password you set during signup ***', 'ui', 'memberpress'); ?></td></tr>
      </table>
    </div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _ex('Cheers!', 'ui', 'memberpress'); ?></div>
    <div class="section" style="display: block; margin-bottom: 24px;"><?php _ex('The {$blog_name} Team', 'ui', 'memberpress'); ?></div>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
  </div>
</div>

