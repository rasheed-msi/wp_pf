<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<div id="mepr_vat_customer_type_row" class="mp-form-row mepr_custom_field mepr_mepr_vat_customer_type">
<<<<<<< HEAD
  <label for="mepr_vat_customer_type"><?php _e('Customer Type:', 'memberpress'); ?></label>
  <div id="mepr_vat_customer_type" class="mepr-radios-field">
    <span class="mepr-radios-field-row">
      <input type="radio" name="mepr_vat_customer_type" id="mepr_vat_customer_type-consumer" value="consumer" class="mepr-form-radios-input" <?php checked('consumer',$vat_customer_type); ?> />
      <label for="mepr_vat_customer_type-consumer" class="mepr-form-radios-label"><?php _e('Consumer', 'memberpress'); ?></label>
    </span>
    <span class="mepr-radios-field-row">
      <input type="radio" name="mepr_vat_customer_type" id="mepr_vat_customer_type-business" value="business" class="mepr-form-radios-input" <?php checked('business',$vat_customer_type); ?> />
      <label for="mepr_vat_customer_type-business" class="mepr-form-radios-label"><?php _e('Business', 'memberpress'); ?></label>
=======
  <label for="mepr_vat_customer_type"><?php _ex('Customer Type:', 'ui', 'memberpress'); ?></label>
  <div id="mepr_vat_customer_type" class="mepr-radios-field">
    <span class="mepr-radios-field-row">
      <input type="radio" name="mepr_vat_customer_type" id="mepr_vat_customer_type-consumer" value="consumer" class="mepr-form-radios-input" <?php checked('consumer',$vat_customer_type); ?> />
      <label for="mepr_vat_customer_type-consumer" class="mepr-form-radios-label"><?php _ex('Consumer', 'ui', 'memberpress'); ?></label>
    </span>
    <span class="mepr-radios-field-row">
      <input type="radio" name="mepr_vat_customer_type" id="mepr_vat_customer_type-business" value="business" class="mepr-form-radios-input" <?php checked('business',$vat_customer_type); ?> />
      <label for="mepr_vat_customer_type-business" class="mepr-form-radios-label"><?php _ex('Business', 'ui', 'memberpress'); ?></label>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
    </span>
  </div>
</div>
<div id="mepr_vat_number_row" class="mp-form-row mepr_custom_field mepr_vat_number">
  <div class="mp-form-label">
<<<<<<< HEAD
    <label for="mepr_vat_number"><?php _e('VAT Number:', 'memberpress'); ?></label>
    <span class="cc-error"><?php _e('Invalid VAT Number', 'memberpress'); ?></span>
=======
    <label for="mepr_vat_number"><?php _ex('VAT Number:', 'ui', 'memberpress'); ?></label>
    <span class="cc-error"><?php _ex('Invalid VAT Number', 'ui', 'memberpress'); ?></span>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
  </div>
  <input type="text" name="mepr_vat_number" id="mepr_vat_number" class="mepr-form-input valid" value="<?php echo $vat_number; ?>" />
</div>

