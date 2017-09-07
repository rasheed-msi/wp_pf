<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="dev1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <?php wp_head(); ?>

        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        <?php if (isset($_GET['PayerID']) && $_GET['PayerID'] != '1'): ?>
            <style type="text/css">
                #pmpro_user_fields_show {
                    display: none;
                }
            </style>
            
        <?php endif; ?>

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="<?php echo mrt_body_style(); ?>">
        

        <header class="header">
            <div class="container">
                <div class="flexbox verticalAlign">
                    <a href="<?php echo home_url(); ?>" class="logo"><img src="<?php echo MRT_URL_IMAGE_UPLOADS; ?>/img_logo_view.png" alt="" /></a>
                    <div class="headerContents flexFullChild clearfix">
                        <div class="buttonsGroup clearfix">
                            <?php if (is_user_logged_in()): ?>
                                <span><a href="<?php echo wp_logout_url(home_url() . '?applogout=1'); ?>" class="buttons">Logout</a></span>
                                <?php if (State::has_membership_access()): ?>
                                    <span><a href="<?php echo site_url('dashboard'); ?>" class="buttons buttonGreen">Dashboard</a></span>
                                <?php else: ?>
                                    <span><a href="<?php echo site_url('membership-account/membership-levels'); ?>" class="buttons buttonGreen">Membership</a></span>
                                <?php endif; ?>

                            <?php else: ?>
                                <span><a href="<?php echo site_url('register-options'); ?>" class="buttons">Register</a></span>
                                <span><a href="<?php echo site_url('login'); ?>" class="buttons buttonGreen">Login</a></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
