<?php
if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');}

class MeprPowerPressCtrl extends MeprBaseCtrl {
  public function load_hooks() {
<<<<<<< HEAD
    add_filter( 'powerpress_admin_capabilities', array( $this, 'powerpress_caps' ) );
=======
    add_filter('powerpress_admin_capabilities',array($this,'powerpress_caps'));
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
  }

  public function powerpress_caps($caps) {
    $products = get_posts(array('posts_per_page'=>-1,'post_type'=>MeprProduct::$cpt));

<<<<<<< HEAD
    $caps['mp_active'] = __('MemberPress Active Member', 'memberpress');

    // Add Dynamic MemberPress capabilities into the mix
    foreach( $products as $product ) {
      $caps["mp_membership_authorized_{$product->ID}"] = sprintf(__('MemberPress: %s', 'memberpress'), $product->post_title);
=======
    $caps['mepr-active'] = __('MemberPress Active Member', 'memberpress');

    // Add Dynamic MemberPress capabilities into the mix
    foreach($products as $product) {
      $caps["mepr-membership-auth-{$product->ID}"] = sprintf(__('MemberPress: %s', 'memberpress'), $product->post_title);
>>>>>>> ed07bd83ad0d23e91ee1e0587e1da517e839c19a
    }

    return $caps;
  }

} //End class

