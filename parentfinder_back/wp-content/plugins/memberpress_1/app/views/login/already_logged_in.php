<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<?php if(is_page($login_page_id) && isset($redirect_to) && !empty($redirect_to) &&
         (!isset($_GET['action']) || $_GET['action'] != 'mepr_unauthorized')): ?>
  <script type="text/javascript">
    window.location.href="<?php echo $redirect_to; ?>";
  </script>
<?php endif; ?>

<div class="mepr-already-logged-in">
<<<<<<< HEAD
  <?php printf(__('You\'re already logged in. %1$sLogout.%2$s', 'memberpress'), '<a href="'. wp_logout_url($redirect_to) . '">', '</a>'); ?>
=======
  <?php printf(_x('You\'re already logged in. %1$sLogout.%2$s', 'ui', 'memberpress'), '<a href="'. wp_logout_url($redirect_to) . '">', '</a>'); ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
</div>

