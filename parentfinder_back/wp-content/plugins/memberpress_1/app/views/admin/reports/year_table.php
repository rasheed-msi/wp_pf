<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<table class="widefat" style="margin-top:25px;">
  <thead>
    <tr>
      <th width="15%"><?php _e('Date', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Pending', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Failed', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Complete', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Refunded', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Collected', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Refunded', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Tax', 'memberpress'); ?></th>
      <th width="12%"><?php _e('Net Total', 'memberpress'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $records = MeprReports::get_yearly_data('transactions', $curr_year, $curr_product);
    $pTotal = $fTotal = $cTotal = $rTotal = $revTotal = $refTotal = $taxTotal = 0;
    $row_index = 0;

    foreach($records as $r) {
      $revenue = (float)MeprReports::get_revenue($r->month, false, $curr_year, $curr_product);
      $taxes = (float)MeprReports::get_taxes($r->month, false, $curr_year, $curr_product);
      $refunds = (float)MeprReports::get_refunds($r->month, false, $curr_year, $curr_product);
      $collected = (float)MeprReports::get_collected($r->month, false, $curr_year, $curr_product);
<<<<<<< HEAD
=======
      $all = (float)($revenue + $refunds + $taxes);
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
      $alternate = ( $row_index++ % 2 ? '' : 'alternate' );
      $r->day = '';
    ?>
      <tr class="<?php echo $alternate; ?>">
        <td>
<<<<<<< HEAD
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&product='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year); ?>">
=======
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&membership='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year); ?>">
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
            <?php echo MeprReports::make_table_date($r->month, 1, $curr_year, 'm/Y'); ?>
          </a>
        </td>
        <td>
<<<<<<< HEAD
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&product='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&search=pending'); ?>">
=======
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&membership='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&status=pending'); ?>">
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
            <?php echo $r->p; $pTotal += $r->p; ?>
          </a>
        </td>
        <td>
<<<<<<< HEAD
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&product='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&search=failed'); ?>">
=======
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&membership='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&status=failed'); ?>">
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
            <?php echo $r->f; $fTotal += $r->f; ?>
          </a>
        </td>
        <td>
<<<<<<< HEAD
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&product='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&search=complete'); ?>">
=======
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&membership='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&status=complete'); ?>">
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
            <?php echo $r->c; $cTotal += $r->c; ?>
          </a>
        </td>
        <td>
<<<<<<< HEAD
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&product='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&search=refunded'); ?>">
            <?php echo $r->r; $rTotal += $r->r; ?>
          </a>
        </td>
        <td style="color:green;"><?php echo MeprAppHelper::format_currency(($revenue + $refunds + $taxes),true,false); $revTotal += $revenue; ?></td>
        <td style="color:red;"><?php echo MeprAppHelper::format_currency($refunds,true,false); $refTotal += $refunds; ?></td>
        <td style="color:orange;"><?php echo MeprAppHelper::format_currency($taxes,true,false); $taxTotal += $taxes; ?></td>
        <td style="color:navy;"><?php echo MeprAppHelper::format_currency($revenue,true,false); ?></td>
      </tr>
    <?php
    }
=======
          <a href="<?php echo admin_url('admin.php?page=memberpress-trans&membership='.$curr_product.'&month='.$r->month.'&day='.$r->day.'&year='.$curr_year.'&status=refunded'); ?>">
            <?php echo $r->r; $rTotal += $r->r; ?>
          </a>
        </td>
        <td <?php if(!empty($all)) { echo 'style="color:green;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($all,true,false); $revTotal += $revenue; ?></td>
        <td <?php if(!empty($refunds)) { echo 'style="color:red;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($refunds,true,false); $refTotal += $refunds; ?></td>
        <td <?php if(!empty($taxes)) { echo 'style="color:orange;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($taxes,true,false); $taxTotal += $taxes; ?></td>
        <td <?php if(!empty($revenue)) { echo 'style="color:navy;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($revenue,true,false); ?></td>
      </tr>
    <?php
    }
    $allTotal = (float)($revTotal + $refTotal + $taxTotal);
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
    ?>
    </tbody>
    <tfoot>
      <tr>
        <th><?php _e('Totals', 'memberpress'); ?></th>
        <th><?php echo $pTotal; ?></th>
        <th><?php echo $fTotal; ?></th>
        <th><?php echo $cTotal; ?></th>
        <th><?php echo $rTotal; ?></th>
<<<<<<< HEAD
        <th style="color:green;"><?php echo MeprAppHelper::format_currency(($revTotal + $refTotal + $taxTotal),true,false); ?></th>
        <th style="color:red;"><?php echo MeprAppHelper::format_currency($refTotal,true,false); ?></th>
        <th style="color:orange;"><?php echo MeprAppHelper::format_currency($taxTotal,true,false); ?></th>
        <th style="color:navy;"><?php echo MeprAppHelper::format_currency($revTotal,true,false); ?></th>
=======
        <th <?php if(!empty($allTotal)) { echo 'style="color:green;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($allTotal,true,false); ?></th>
        <th <?php if(!empty($refTotal)) { echo 'style="color:red;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($refTotal,true,false); ?></th>
        <th <?php if(!empty($taxTotal)) { echo 'style="color:orange;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($taxTotal,true,false); ?></th>
        <th <?php if(!empty($revTotal)) { echo 'style="color:navy;font-weight:bold;"'; } ?>><?php echo MeprAppHelper::format_currency($revTotal,true,false); ?></th>
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
      </tr>
  </tfoot>
</table>
<div>&nbsp;</div>
<div>
  <a class="button" href="<?php echo admin_url( "admin-ajax.php?action=mepr_export_report&export=yearly&{$_SERVER['QUERY_STRING']}" ); ?>"><?php _e('Export as CSV', 'memberpress'); ?></a>
  <?php MeprHooks::do_action('mepr-report-footer','yearly'); ?>
</div>

