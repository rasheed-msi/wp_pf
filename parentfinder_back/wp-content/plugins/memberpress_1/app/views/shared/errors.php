<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<<<<<<< HEAD
<div class="mp_wrapper">
<?php if(isset($errors) && $errors != null && count($errors) > 0): ?>
  <div class="mepr_error">
    <ul>
      <?php foreach($errors as $error): ?>
        <li><strong><?php _e('ERROR', 'memberpress'); ?></strong>: <?php print $error; ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
<?php if( isset($message) and !empty($message) ): ?>
  <div class="mepr_updated"><?php echo $message; ?></div>
<?php endif; ?>
</div>

=======
<?php if(isset($errors) && $errors != null && count($errors) > 0): ?>
<div class="mp_wrapper">
  <div class="mepr_error" id="mepr_errors">
    <ul>
      <?php foreach($errors as $error): ?>
        <li><strong><?php _ex('ERROR', 'ui', 'memberpress'); ?></strong>: <?php print $error; ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<?php if( isset($message) and !empty($message) ): ?>
<div class="mp_wrapper">
  <div class="mepr_updated"><?php echo $message; ?></div>
</div>
<?php endif; ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
