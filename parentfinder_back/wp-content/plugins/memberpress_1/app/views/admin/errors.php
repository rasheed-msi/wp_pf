<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<<<<<<< HEAD
<?php if(isset($errors) && $errors != null && count($errors) > 0): ?>
  <div class="error notice is-dismissible below-h2">
    <ul>
      <?php foreach($errors as $error): ?>
        <li><strong><?php _e('ERROR', 'memberpress'); ?></strong>: <?php print $error; ?></li>
=======
<?php if(isset($errors) && is_array($errors) && !empty($errors)): ?>
  <div class="error notice is-dismissible below-h2">
    <ul>
      <?php foreach($errors as $error): ?>
        <li><strong><?php _e('ERROR:', 'memberpress'); ?></strong> <?php print $error; ?></li>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
<<<<<<< HEAD
<?php if( isset($message) and !empty($message) ): ?>
=======
<?php if(isset($message) && !empty($message)): ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
  <div id="message" class="updated notice notice-success is-dismissible below-h2">
    <p><?php echo $message; ?></p>
  </div>
<?php endif; ?>
