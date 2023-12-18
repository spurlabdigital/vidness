<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@300;700&family=Poppins:wght@300&display=swap" rel="stylesheet">
<link href='<?php echo get_template_directory_uri(); ?>/css/animations.css' rel='stylesheet' text='text/css'>
<link href='<?php echo get_template_directory_uri(); ?>/css/slick.css' rel='stylesheet' text='text/css'>
<link href='<?php echo get_template_directory_uri(); ?>/css/slick-theme.css' rel='stylesheet' text='text/css'>
<link href='<?php echo get_template_directory_uri(); ?>/css/jquery-ui.min.css' rel='stylesheet' text='text/css'>

<link href='<?php echo get_template_directory_uri(); ?>/images/logo.svg' rel='preload' as='image'>

<link rel="icon" type="image/png" href="<?= get_template_directory_uri(); ?>/images/favicon.png" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
<script type="text/javascript">
    function toggleSideMenu() {
        jQuery('#sideMenu').toggleClass('displayed');
    }
</script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<div id="main">
		