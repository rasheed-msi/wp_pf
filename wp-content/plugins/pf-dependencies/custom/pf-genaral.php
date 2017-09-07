<?php

//override wordpress logo
function pf_login_logo() { 
?> 
<style type="text/css"> 
body.login div#login h1 a {background-image: url('<?php echo PF_DEP_URL; ?>assets/img/parentfinder-logo.png');background-size: 320px auto;width: 320px;height: 56px;margin:0 auto 0px;} 
</style>
<?php 
} 
add_action( 'login_enqueue_scripts', 'pf_login_logo' );
add_action( 'login_enqueue_scripts', 'pf_login_logo' );

// home url in login page
add_filter('login_headerurl', 
    create_function(false,"return get_site_url();"));
 
// title in login page
add_filter('login_headertitle', function () {
    return get_bloginfo('name');
});