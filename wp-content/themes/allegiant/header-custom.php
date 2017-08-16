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
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/style.css" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri() ?>/custom-theme/css/custom-style.css" rel="stylesheet">


        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body >

        <header id="header" class="header">
            <div class="container">
                <?php do_action('cpotheme_header'); ?>
                <div class='clear'></div>
            </div>
        </header>
