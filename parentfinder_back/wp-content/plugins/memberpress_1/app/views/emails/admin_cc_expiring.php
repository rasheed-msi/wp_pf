<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<<<<<<< HEAD
<div id="header" style="width: 680px; padding 0; margin: 0 auto; text-align: left;">
  <h1 style="font-size: 30px; margin-bottom: 0;"><?php _e('A Credit Card is Expiring on {$subscr_cc_month_exp}/{$subscr_cc_year_exp}', 'memberpress'); ?></h1>
  <h2 style="margin-top: 0; color: #999; font-weight: normal;"><?php echo '{$user_full_name}'; ?></h2>
</div>
<div id="body" style="width: 600px; background: white; padding: 40px; margin: 0 auto; text-align: left;">
  <div style="margin-bottom: 20px;"><?php _e('A member\'s Credit Card will expire before their next billing on {$blog_name}.', 'memberpress'); ?></div>
  <table style="clear: both;" class="transaction">
    <tr><th style="text-align: left;"><?php _e('Website:', 'memberpress'); ?></th><td>{$blog_name}</td></tr>
    <tr><th style="text-align: left;"><?php _e('Subscription:', 'memberpress'); ?></th><td>{$product_name}</td></tr>
    <tr><th style="text-align: left;"><?php _e('Subscription Num:', 'memberpress'); ?></th><td>{$subscr_num}</td></tr>
    <tr><th style="text-align: left;"><?php _e('Credit Card Num:', 'memberpress'); ?></th><td>{$subscr_cc_num}</td></tr>
    <tr><th style="text-align: left;"><?php _e('Credit Card Exp:', 'memberpress'); ?></th><td>{$subscr_cc_month_exp}/{$subscr_cc_year_exp}</td></tr>
    <tr><th style="text-align: left;"><?php _e('Next Billing:', 'memberpress'); ?></th><td>{$subscr_next_billing_at}</td></tr>
    <tr><th style="text-align: left;"><?php _e('Payment System:', 'memberpress'); ?></th><td>{$subscr_gateway}</td></tr>
=======
<div id="header" style="width: 680px; padding: 0px; margin: 0 auto; text-align: left;">
  <h1 style="font-size: 30px; margin-bottom: 0;"><?php _ex('A Credit Card is Expiring on {$subscr_cc_month_exp}/{$subscr_cc_year_exp}', 'ui', 'memberpress'); ?></h1>
  <h2 style="margin-top: 0; color: #999; font-weight: normal;"><?php echo '{$user_full_name}'; ?></h2>
</div>
<div id="body" style="width: 600px; background: white; padding: 40px; margin: 0 auto; text-align: left;">
  <div style="margin-bottom: 20px;"><?php _ex('A member\'s Credit Card will expire before their next billing on {$blog_name}.', 'ui', 'memberpress'); ?></div>
  <table style="clear: both;" class="transaction">
    <tr><th style="text-align: left;"><?php _ex('Website:', 'ui', 'memberpress'); ?></th><td>{$blog_name}</td></tr>
    <tr><th style="text-align: left;"><?php _ex('Subscription:', 'ui', 'memberpress'); ?></th><td>{$product_name}</td></tr>
    <tr><th style="text-align: left;"><?php _ex('Subscription Num:', 'ui', 'memberpress'); ?></th><td>{$subscr_num}</td></tr>
    <tr><th style="text-align: left;"><?php _ex('Credit Card Num:', 'ui', 'memberpress'); ?></th><td>{$subscr_cc_num}</td></tr>
    <tr><th style="text-align: left;"><?php _ex('Credit Card Exp:', 'ui', 'memberpress'); ?></th><td>{$subscr_cc_month_exp}/{$subscr_cc_year_exp}</td></tr>
    <tr><th style="text-align: left;"><?php _ex('Next Billing:', 'ui', 'memberpress'); ?></th><td>{$subscr_next_billing_at}</td></tr>
    <tr><th style="text-align: left;"><?php _ex('Payment System:', 'ui', 'memberpress'); ?></th><td>{$subscr_gateway}</td></tr>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
  </table>
  <table style="clear: both; width: 100%;" class="labels">
    <tr>
      <td style="vertical-align: top;">
        <fieldset style="border: none; border-top: 1px solid #dedede; margin: 20px 40px 20px 0;" class="billing">
<<<<<<< HEAD
          <legend style="display: block; font-weight: bold; color: #999;"><?php _e('Billed to', 'memberpress'); ?></legend>
=======
          <legend style="display: block; font-weight: bold; color: #999;"><?php _ex('Billed to', 'ui', 'memberpress'); ?></legend>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
          <address style="font-style: normal;">
            <div class="address_name" style="display: block; font-size: 115%;"><big>{$user_full_name}</big></div>
            <div class="address_email" style="display: block;">{$user_email} (<b>{$user_login}</b>)</div>
            <div class="address_address" style="display: block;">{$user_address}</div>
          </address>
        </fieldset>
      </td>
    </tr>
  </table>
</div>

