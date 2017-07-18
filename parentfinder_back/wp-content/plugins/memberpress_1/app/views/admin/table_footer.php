<div class="alignleft">
<<<<<<< HEAD
  <a href="<?php echo admin_url('admin-ajax.php?action=' . $action . '&' . $_SERVER['QUERY_STRING']); ?>"><?php _e('Export as CSV', 'memberpress'); ?></a>
=======
  <a href="<?php echo admin_url('admin-ajax.php?action=' . $action . '&' . $_SERVER['QUERY_STRING']); ?>"><?php printf(__('Export table as CSV (%d records)', 'memberpress'), $itemcount); ?></a>
  <?php MeprHooks::do_action('mepr-control-table-footer', $action, $totalitems); ?>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
</div>
