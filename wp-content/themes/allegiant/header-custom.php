<!DOCTYPE html>
<html lang="en" ng-app="appParentfinder">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard | Parentfinder</title>

        <?php wp_head(); ?>
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/bootstrap.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/animate.css" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/style.css?ver=1.2" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/custom-style.css" rel="stylesheet">

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
                                <span><a href="<?php echo wp_logout_url( home_url() ); ?>" class="buttons">Logout</a></span>
                                <span><a href="<?php echo site_url('dashboard'); ?>" class="buttons buttonGreen">Dashboard</a></span>
                            <?php else: ?>
                                <span><a href="<?php echo site_url('register-options'); ?>" class="buttons">Register</a></span>
                                <span><a href="<?php echo site_url('login'); ?>" class="buttons buttonGreen">Login</a></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
