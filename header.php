<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 *
 * @package Ridizain
 * @since Ridizain 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
   <meta name="description" content="<?php if ( is_single() ) {
        the_excerpt_rss('', true); 
    } else {
        echo "American Theatre provides news, features, artist interviews, and editorials about theatre in the US and abroad.";
    }
    ?>" />
	<title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<script type="text/javascript" src="validate.js"></script>
  <script type="text/javascript" src="/wp-content/themes/ridizain-child/functions.js"></script>
	<?php wp_head(); ?>
  
	<!--Load Vue
	<script src="https://unpkg.com/vue"></script>-->

	<!--Load the web component polyfill
	<script src="https://cdnjs.cloudflare.com/ajax/libs/webcomponentsjs/1.2.0/webcomponents-loader.js"></script>-->

	<!--Load your custom element
	<script src="https://s3.asoundstrategy.com/ad-widget/dist/ad-viewer.min.js"></script>-->
	
</head>

<body <?php body_class(); ?>>
 
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55119648-1', 'auto');
  ga('send', 'pageview');

</script>

<div id="page" class="hfeed site">

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Leaderboard Ad') ) : ?>
      <?php endif; ?>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Header Widget') ) : ?>
      <?php endif; ?>
    <?php if ( get_header_image() ) : ?>
	    <div id="site-header">
		    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			    <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php bloginfo( 'name' ); ?>">
		  

</a>

	    </div>
	<?php endif; ?>

<?php get_template_part( 'masthead' ); ?>
<div class="clearfix"></div>
<!-- Slider placeholder -->
<div id="main" class="site-main">
<div class="clearfix"></div>
<?php
if ( get_theme_mod( 'featured_content_location' ) == 'fullwidth' ) {
	if ( is_front_page() && ridizain_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
} ?>
<div id="main-content" class="main-content">

<?php
if ( get_theme_mod( 'featured_content_location' ) == 'default' ) {
	if ( is_front_page() && ridizain_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
} ?>