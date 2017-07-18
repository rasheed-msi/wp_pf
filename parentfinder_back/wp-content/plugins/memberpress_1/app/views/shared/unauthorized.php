<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<?php $mepr_options = MeprOptions::fetch(); ?>
<<<<<<< HEAD
<p><?php printf(__('You\'re unauthorized to view this page. Why don\'t you %s and try again.', 'memberpress'), "<a href=\"" . $mepr_options->login_page_url() . "\">" . __('Login', 'memberpress') . "</a>"); ?></p>
=======
<p><?php printf(_x('You\'re unauthorized to view this page. Why don\'t you %s and try again.', 'ui', 'memberpress'), "<a href=\"" . $mepr_options->login_page_url() . "\">" . _x('Login', 'ui', 'memberpress') . "</a>"); ?></p>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
