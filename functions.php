<?php
function generate_setup(){
	add_image_size( 'sk-thumbnail', 300, 200, true );

}
add_action( 'after_setup_theme', 'generate_setup' );
/*additional widgets for ads and for TCG icon. Added by Diep Tran. June 2014*/
if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name' => 'Leaderboard Ad',
    'id'   => 'leaderboard-ad',
    'description'   => 'Area at the top of the site for leaderboard ad',
    'before_widget' => '<div id="leaderboard-ad" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));
}

// Stay logged in for longer periods
add_filter('auth_cookie_expiration', 'keep_me_logged_in');
function keep_me_logged_in($expirein)
{
  return 31556926; // 1 year in seconds
}

if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name' => 'Header Widget',
    'id'   => 'header-widget',
    'description'   => 'Widget area beside the AMERICAN THEATRE banner, reserved for TCG icon',
    'before_widget' => '<div id="header-widget" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));
}

if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name' => 'Billboard Ad',
    'id'   => 'billboard-ad',
    'description'   => 'Area at the foot of the page for billboard ad',
    'before_widget' => '<div id="billboard-ad" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));
}

if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name' => 'Inline Ad',
    'id'   => 'inline-Ad',
    'description'   => 'Inline ad for stories',
    'before_widget' => '<div id="inline-ad" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));
}

// Ajax Load More 

function load_more()
{

  // print_r($_POST);
  // exit();

  $per_page = get_option('posts_per_page');
  $page_no = $_POST['page'];
  // $cat = $_POST['cat'];

  $offset = $per_page * $page_no;

  $args = array(
    'post_type' => 'post',
    'posts_per_page' => $per_page,
    'offset' => $offset,
    'tax_query' => array(
      array(
        'taxonomy' => $_POST['tax'],
        'field' => 'slug',
        'terms' => array($_POST['tax_slug']),
      ),
    ),
  );

  $q = new WP_Query($args);

  if ($q->have_posts()) :
    while ($q->have_posts()) : $q->the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header">

          <?php if (is_single()) :
            the_title('<h1 class="entry-title">', '</h1>');
          else :
            the_title('<h1 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h1>');
          endif;
          ?>


          <div class="entry-meta">
            <div class="index-image">
              <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php /*insert images from post even when there's no featured image*/ if (has_post_thumbnail()) {
                                                                                            the_post_thumbnail('thumbnail');
                                                                                          } else {
                                                                                            $attachments = get_children(
                                                                                              array(
                                                                                                'post_parent' => get_the_ID(),
                                                                                                'post_status' => 'inherit',
                                                                                                'post_type' => 'attachment',
                                                                                                'post_mime_type' => 'image',
                                                                                                'order' => 'ASC',
                                                                                                'orderby' => 'menu_order ID',
                                                                                                'numberposts' => 1
                                                                                              )
                                                                                            );
                                                                                            foreach ($attachments as $thumb_id => $attachment) {
                                                                                              echo wp_get_attachment_image($thumb_id, 'thumbnail');
                                                                                            }
                                                                                          } ?></a>
            </div>

            <?php if (in_array('category', get_object_taxonomies(get_post_type())) && ridizain_categorized_blog()) : ?>

              <span class="cat-links"><?php hidden_category(', ') ?></span>

            <?php
            endif;
            if ('post' == get_post_type())
              ridizain_posted_on();

            if (!post_password_required() && (comments_open() || get_comments_number())) :
            ?>
              <span class="comments-link"><?php comments_popup_link(__('Leave a comment', 'ridizain'), __('1 Comment', 'ridizain'), __('% Comments', 'ridizain')); ?></span>


            <?php
            endif;

            edit_post_link(__('Edit', 'ridizain'), '<span class="edit-link">', '</span>');
            ?>
          </div><!-- .entry-meta -->

        </header><!-- .entry-header -->

        <div class="entry-summary">
          <?php the_excerpt(); ?>

        </div><!-- .entry-summary -->

      </article><!-- #post-## -->
<?php
    endwhile;
  endif;

  exit();
}
add_action('wp_ajax_load_more', 'load_more');
add_action('wp_ajax_nopriv_load_more', 'load_more');



/*Function to remove default positions for sharing and relating post Jetpack widgets. Added by Diep Tran. June 2014*/

/* function jptweak_remove_share() {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
    if ( class_exists( 'Jetpack_Likes' ) ) {
        remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
    }
}
 
add_action( 'loop_start', 'jptweak_remove_share' );

 

function jetpackme_remove_rp() {
    $jprp = Jetpack_RelatedPosts::init();
    $callback = array( $jprp, 'filter_add_target_to_dom' );
    remove_filter( 'the_content', $callback, 40 );
}
add_filter( 'wp', 'jetpackme_remove_rp', 20 );

function jetpackme_related_posts_headline( $headline ) {
$headline = sprintf(
            '<h3 class="jp-relatedposts-headline"><em>%s</em></h3>',
            esc_html( 'Related Stories' )
            );
return $headline;
}
add_filter( 'jetpack_relatedposts_filter_headline', 'jetpackme_related_posts_headline' ); */


/*
Plugin Name: AdSanity Size Filters
Plugin URI: http://adsanity.com
Description: Predefined Ad Sizes
Version: 0.1
Author: Pixel Jar
Author URI: http://pixeljar.net
*/

/**
 * Copyright (c) 2011 brandondove. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

/**
 * 
 */
/*class adsanity_custom_ad_sizes {
	
	
	function __construct() {
		
		// limit ad sizes to only ones you specify
		add_filter( 'adsanity_ad_sizes', array( &$this, 'your_ad_sizes' ) );
		
		// Add a size to the built-in file sizes
		// add_filter( 'adsanity_ad_sizes', array( &$this, 'more_ad_sizes' ) );
	}
	
	function your_ad_sizes( $sizes ) {
		return array(
			'1000x300'	=> '1000x300 - Billboard',
			'1000x140'	=> '1000x140 - Leaderboard',
			'275x750'	=> '275x750 - Wide Skyscraper',
			'275x250'	=> '275x250 - Wide Pillow',
			'230x600'	=> '230x600 - Skyscraper',
			'230x250'	=> '230x250 - Pillow'
		);
	}
	

	
}
new adsanity_custom_ad_sizes;*/

add_theme_support('infinite-scroll', array(
  'container' => 'content',
  'type'           => 'click',
));




















function loadDynamicContenst()
{
  $s_name = $_REQUEST['statName'];
  $v_name = $_REQUEST['venuName'];
  $state2 = $string = str_replace(" ", "-",$s_name);
  $limit = '';
  if($v_name == 'theatreworks' && $s_name == 'colorado'){
    $limit = ' LIMIT 4';
  }
  $from = get_field('events_start_date', 'option');
  $to = get_field('events_end_date', 'option');

  $get_post_id = array();
  global $wpdb;
  
  $get_post_id_q = 'SELECT B.post_id from `wp_postmeta` A, `wp_postmeta` B 
  where A.`meta_key` = "state" and (A.`meta_value` like "%'.$s_name.'%" or A.`meta_value` like "%'.$state2.'%") and B.`meta_key` = "companys_name" and B.`meta_value` LIKE "%'.$v_name.'%" and B.`post_id` = A.`post_id` '.$limit.'';
  
  $get_post_id = $wpdb->get_results($get_post_id_q);
  
//echo '<pre>'; print_r($get_post_id); echo '</pre>';

  $parr = array();
  $pmarr = array();
  $get_post_table_data = array();
  $i=0;
  $post_data = array();
  $postmeta_data = array();
  $author = '';
  $director = '';
  $companys_name = '';
  $city = '';
  $price = '';
  $reference_link = '';
  $start_date = '';
  $end_date = '';
  $type_of_event = '';
  $venues_for_performance = '';
  $htmlContenst = '';
  $post_title = '';
  $state = '';
  $post_id = '';
  $f_image = '';

  foreach($get_post_id as $post_id){
   // echo "SELECT `ID`,`post_content`,`post_title` from `wp_posts` where `ID` = $post_id->post_id  and post_type = 'events'"; exit;
   $pdq = "SELECT `ID`,`post_content`,`post_title` from `wp_posts` where `ID` = $post_id->post_id  and post_type = 'events' and post_status='publish'";
    $get_post_table_data = $wpdb->get_results($pdq);
    $parr = json_decode(json_encode($get_post_table_data), true);

//echo '<pre>'; print_r($parr); echo '</pre>';


    foreach($parr as $a){
      $id = $a['ID'];
      $post_data[$id]['id'] = $id;
      $post_data[$id]['title'] = $post_title = $a['post_title'];
      $post_data[$id]['description'] = $a['post_content'];
      
    }
  
    $get_postmeta_table_data = $wpdb->get_results("SELECT * from `wp_postmeta` where `post_id` = $post_id->post_id ");
    $id = $post_id->post_id;
    $pmarr = json_decode(json_encode($get_postmeta_table_data), true);
   
    foreach ($pmarr as $key => $val) {

      if (in_array('start_date', $val)) {
        $start_date = new DateTime($val['meta_value']);
        $start_date_f = new DateTime($val['meta_value']);
        $start_date_f = $start_date_f->format('Y-m-d');
        $post_data[$id]['start_date'] = $start_date = $start_date->format('M d Y');
      }

      if (in_array('end_date', $val)) {
        $end_date_f = new DateTime($val['meta_value']);
        $end_date_f = $end_date_f->format('Y-m-d');
        $end_date = new DateTime($val['meta_value']);
        $post_data[$id]['end_date'] = $end_date = $end_date->format('M d Y');
      }
        if (in_array('companys_name', $val)) {
          $post_data[$id]['companys_name'] = $companys_name = $val['meta_value'];
        }

        if (in_array('post_id', $val)) {
          $post_data[$id]['id'] = $val['post_id'];
        }
        
        if (in_array('state', $val)) {
          $post_data[$id]['state'] = $state = $val['meta_value'];
        }

        if (in_array('director', $val)) {
          $post_data[$id]['director'] = $director = $val['meta_value'];
        }

        if (in_array('author', $val)) {
          $post_data[$id]['author'] = $author = $val['meta_value'];
        }

        if (in_array('venues_for_performance', $val)) {
          $post_data[$id]['venues_for_performance'] = $venues_for_performance = $val['meta_value'];
        }

        if (in_array('city', $val)) {
          $post_data[$id]['city'] = $city = $val['meta_value'];
        }
        
        if (in_array('price', $val)) {
          $post_data[$id]['price'] = $price = $val['meta_value'];
        }

        if (in_array('reference_link', $val)) {
          $post_data[$id]['reference_link'] = $reference_link = $val['meta_value'];
        }

        if (in_array('type_of_event', $val)) {
          $post_data[$id]['type_of_event'] = $type_of_event = $val['meta_value'];
        }
    }
  }
  //echo "<pre>"; print_r($post_data); exit;
   usort($post_data, 'date_compare');
//echo "<pre>"; print_r($post_data); exit;
  // echo get_the_post_thumbnail_url()

  foreach ($post_data as $key => $value) {
   // echo $key.'<br>';
    $start_date_f = date("Y-m-d",strtotime($value['start_date']));
    $end_date_f = date("Y-m-d",strtotime($value['end_date']));
    if($start_date_f >= $from && $start_date_f <= $to) {
      if(!empty($value['author']) || !empty($value['director']) || !empty($value['type_of_event']) || !empty($value['venues_for_performance'])){
        $htmlContenst .= '<div class="panel" id="content_1" data-id="'.$id.'">';
        $htmlContenst .= '<div class="row details">';
        $htmlContenst .= '<div class="col-md-6 description">';
        $htmlContenst .= '<p style="font-weight: bold;font-style: italic;">' . $value['title'] . '</p>';
        $htmlContenst .= '<p><span>By: </span>' . $value['author'] . '</p>';
        $htmlContenst .= '<p><span>Directed by: </span>' . $value['director'] . '</p>';
        $htmlContenst .= '<p><span>Event Date(s): </span>' . $value['start_date'] . '-' . $value['end_date'] . '</p>';
        $htmlContenst .= '<p><span>Type of Event: </span>' . $value['type_of_event'] . '</p>';
        $htmlContenst .= '<p><span>Venue: </span>' . $value['venues_for_performance'] . '</p>';
        if(!empty($value['city'])){
          $htmlContenst .= '<p><span>City: </span>' . $value['city'] . '</p>';
        }
        $htmlContenst .= '<p><span>Price: </span>' . $value['price'] . '</p>';
        $htmlContenst .= '<p><span>Reference Link: </span><a href="' . $value['reference_link'] . '" target="_blank">' . $value['reference_link'] . '</a></p>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '<div class="col-md-6 event-img">';
       $f_image = get_the_post_thumbnail_url($value['id']);
       //echo $value['id']."<br>";
        if (!empty($f_image)) {
           //echo $f_image."<br>";
         $htmlContenst .= '<img src="' . $f_image . '" alt="" />';
        }
        $htmlContenst .= '</div>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '<div class="event-detail event-description">';
        $htmlContenst .= '<p><span>Description: </span>' . $value['description'] . '</p>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '</div>';
      }
    }
  }
  //echo $htmlContenst;
  echo substr($htmlContenst, 0, -1);
  die();
}

add_action('wp_ajax_loadDynamicContenst', 'loadDynamicContenst');
add_action('wp_ajax_nopriv_loadDynamicContenst', 'loadDynamicContenst');





































function loadDynamicContenst1()
{
  $s_name = $_REQUEST['statName'];
  $v_name = $_REQUEST['venuName'];
  $state2 = $string = str_replace(" ", "-",$s_name);
  $limit = '';
  if($v_name == 'theatreworks' && $s_name == 'colorado'){
    $limit = ' LIMIT 4';
  }
  $from = get_field('pre_events_start_date', 'option');
  $to = get_field('pre_events_end_date', 'option');

  $get_post_id = array();
  global $wpdb;
  $get_post_id = $wpdb->get_results('SELECT B.post_id from `wp_postmeta` A, `wp_postmeta` B 
  where A.`meta_key` = "state" and (A.`meta_value` like "%'.$s_name.'%" or A.`meta_value` like "%'.$state2.'%") 
  and B.`meta_key` = "companys_name" and B.`meta_value` LIKE "%'.$v_name.'%" and B.`post_id` = A.`post_id` '.$limit.'');
  

  $parr = array();
  $pmarr = array();
  $get_post_table_data = array();
  $i=0;
  $post_data = array();
  $postmeta_data = array();
  $author = '';
  $director = '';
  $companys_name = '';
  $city = '';
  $price = '';
  $reference_link = '';
  $start_date = '';
  $end_date = '';
  $type_of_event = '';
  $venues_for_performance = '';
  $htmlContenst = '';
  $post_title = '';
  $state = '';
  $post_id = '';
  $f_image = '';
  

  foreach($get_post_id as $post_id){
   // echo "SELECT `ID`,`post_content`,`post_title` from `wp_posts` where `ID` = $post_id->post_id  and post_type = 'events'"; exit;
    $the_q = "SELECT `ID`,`post_content`,`post_title` from `wp_posts` where `ID` = $post_id->post_id  and post_type = 'events' and post_status='publish'";
    $get_post_table_data = $wpdb->get_results($the_q);
	
	//print_r($the_q);
	
    $parr = json_decode(json_encode($get_post_table_data), true);

    foreach($parr as $a){
      $id = $a['ID'];
      $post_data[$id]['id'] = $id;
      $post_data[$id]['title'] = $post_title = $a['post_title'];
      $post_data[$id]['description'] = $a['post_content'];
      
    }
  
    $get_postmeta_table_data = $wpdb->get_results("SELECT * from `wp_postmeta` where `post_id` = $post_id->post_id");
    $id = $post_id->post_id;
    $pmarr = json_decode(json_encode($get_postmeta_table_data), true);
   
    foreach ($pmarr as $key => $val) {

      if (in_array('start_date', $val)) {
        $start_date = new DateTime($val['meta_value']);
        $start_date_f = new DateTime($val['meta_value']);
        $start_date_f = $start_date_f->format('Y-m-d');
        $post_data[$id]['start_date'] = $start_date = $start_date->format('M d Y');
      }

      if (in_array('end_date', $val)) {
        $end_date_f = new DateTime($val['meta_value']);
        $end_date_f = $end_date_f->format('Y-m-d');
        $end_date = new DateTime($val['meta_value']);
        $post_data[$id]['end_date'] = $end_date = $end_date->format('M d Y');
      }
        if (in_array('companys_name', $val)) {
          $post_data[$id]['companys_name'] = $companys_name = $val['meta_value'];
        }

        if (in_array('post_id', $val)) {
          $post_data[$id]['id'] = $val['post_id'];
        }
        
        if (in_array('state', $val)) {
          $post_data[$id]['state'] = $state = $val['meta_value'];
        }

        if (in_array('director', $val)) {
          $post_data[$id]['director'] = $director = $val['meta_value'];
        }

        if (in_array('author', $val)) {
          $post_data[$id]['author'] = $author = $val['meta_value'];
        }

        if (in_array('venues_for_performance', $val)) {
          $post_data[$id]['venues_for_performance'] = $venues_for_performance = $val['meta_value'];
        }

        if (in_array('city', $val)) {
          $post_data[$id]['city'] = $city = $val['meta_value'];
        }
        
        if (in_array('price', $val)) {
          $post_data[$id]['price'] = $price = $val['meta_value'];
        }

        if (in_array('reference_link', $val)) {
          $post_data[$id]['reference_link'] = $reference_link = $val['meta_value'];
        }

        if (in_array('type_of_event', $val)) {
          $post_data[$id]['type_of_event'] = $type_of_event = $val['meta_value'];
        }
    }
  }
  //echo "<pre>"; print_r($post_data); exit;
   usort($post_data, 'date_compare');
//echo "<pre>"; print_r($post_data); exit;
  // echo get_the_post_thumbnail_url()

  foreach ($post_data as $key => $value) {
    //echo $key.'<br>';
    //echo $value;
   
   $start_date_f = date("Y-m-d",strtotime($value['start_date']));
   $end_date_f = date("Y-m-d",strtotime($value['end_date']));
    if($start_date_f >= $from && $start_date_f <= $to) {
      if(!empty($value['author']) || !empty($value['director']) || !empty($value['type_of_event']) || !empty($value['venues_for_performance'])){
        $htmlContenst .= '<div class="panel status-'.$value['post_status'].'" id="content_1">';
        $htmlContenst .= '<div class="row details">';
        $htmlContenst .= '<div class="col-md-6 description">';
        $htmlContenst .= '<p style="font-weight: bold;font-style: italic;">' . $value['title'] . '</p>';
        $htmlContenst .= '<p><span>By: </span>' . $value['author'] . '</p>';
        $htmlContenst .= '<p><span>Directed by: </span>' . $value['director'] . '</p>';
        $htmlContenst .= '<p><span>Event Date(s): </span>' . $value['start_date'] . '-' . $value['end_date'] . '</p>';
        $htmlContenst .= '<p><span>Type of Event: </span>' . $value['type_of_event'] . '</p>';
        $htmlContenst .= '<p><span>Venue: </span>' . $value['venues_for_performance'] . '</p>';
        if(!empty($value['city'])){
          $htmlContenst .= '<p><span>City: </span>' . $value['city'] . '</p>';
        }
        $htmlContenst .= '<p><span>Price: </span>' . $value['price'] . '</p>';
        $htmlContenst .= '<p><span>Reference Link: </span><a href="' . $value['reference_link'] . '" target="_blank">' . $value['reference_link'] . '</a></p>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '<div class="col-md-6 event-img">';
       $f_image = get_the_post_thumbnail_url($value['id']);
       //echo $value['id']."<br>";
        if (!empty($f_image)) {
           //echo $f_image."<br>";
         $htmlContenst .= '<img src="' . $f_image . '" alt="" />';
        }
        $htmlContenst .= '</div>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '<div class="event-detail event-description">';
        $htmlContenst .= '<p><span>Description: </span>' . $value['description'] . '</p>';
        $htmlContenst .= '</div>';
        $htmlContenst .= '</div>';
      }
    }
  }
  //echo $htmlContenst;
  echo substr($htmlContenst, 0, -1);
  die();
}

add_action('wp_ajax_loadDynamicContenst1', 'loadDynamicContenst1');
add_action('wp_ajax_nopriv_loadDynamicContenst1', 'loadDynamicContenst1');


function date_compare($a, $b)
{
    $t1 = strtotime($a['start_date']);
    $t2 = strtotime($b['start_date']);
    return $t1 - $t2;
} 

function getCSVdata()
{
  
  $get_post_id = array();
  global $wpdb;
  $get_post_id = $wpdb->get_results("SELECT `post_id`,`meta_id` from `wp_postmeta` where `meta_key` = 'state'");
  $get_all_values = array();

  $author = array();
  $director = array();
  $companys_name = '';
  $city = array();
  $price = array();
  $reference_link = array();
  $start_date = array();
  $end_date = array();
  $type_of_event = array();
  $venues_for_performance = array();
  $htmlContenst = array();
  $post_title = array();
  $state = array();
  $post_id = array();
  $f_image = array();
  $j=$i= 0;
  $csvData = array();
  
  $c = 0;
  
  $from = get_field('events_start_date', 'option');
  $to = get_field('events_end_date', 'option');

  foreach ($get_post_id as $post_id) {
    $get_all_values = $wpdb->get_results("SELECT PM.*,P.post_title, P.post_content, P.ID from `wp_postmeta` as PM 
    right join `wp_posts` as P on P.ID = PM.post_id where `post_id` = '$post_id->post_id' and P.post_type = 'events'");
    $array = json_decode(json_encode($get_all_values), true);
    
    foreach ($array as $key => $val) {
      if (in_array('state', $val)) {
        $state = $val['meta_value'];
      }

      if (in_array('director', $val)) {
        $director = $val['meta_value'];
      }

      if (in_array('post_title', $val)) {
        $post_title = $val['post_title'];
      }

      if (in_array('author', $val)) {
        $author = $val['meta_value'];
      }

      if (in_array('venues_for_performance', $val)) {
        $venues_for_performance = $val['meta_value'];
      }

      if (in_array('companys_name', $val)) {
        $companys_name = $val['meta_value'];
      }

      if (in_array('city', $val)) {
        $city = $val['meta_value'];
      }
      if (in_array('price', $val)) {
        $price = $val['meta_value'];
      }

      if (in_array('reference_link', $val)) {
        $reference_link = $val['meta_value'];
      }

      if (in_array('start_date', $val)) {
        $start_date = new DateTime($val['meta_value']);
        $start_date_f = new DateTime($val['meta_value']);
        $start_date_f = $start_date_f->format('Y-m-d');
        $start_date = $start_date->format('D M y');
      }

      if (in_array('end_date', $val)) {
        $end_date_f = new DateTime($val['meta_value']);
        $end_date_f = $end_date_f->format('Y-m-d');
        $end_date = new DateTime($val['meta_value']);
        $end_date = $end_date->format('D M y');
      }

      if (in_array('type_of_event', $val)) {
        $type_of_event = $val['meta_value'];
      }
  }

    if ($start_date_f >= $from && $end_date_f <= $to) {
      $csvData[$i]['ID'] = $val['ID'];
      $csvData[$i]['post_id'] = $val['post_id'];
      $csvData[$i]['meta_id'] = $val['meta_id'];
      $csvData[$i]['title'] = $val['post_title'];
      $csvData[$i]['state'] = $state;
      $csvData[$i]['author'] = $author;
      $csvData[$i]['director'] = $director;
      $csvData[$i]['companys_name'] = $companys_name;
      $csvData[$i]['city'] = $city;
      $csvData[$i]['event_date'] =  $start_date . '-' . $end_date;
      $csvData[$i]['type_of_event'] = $type_of_event;
      $f_image = get_the_post_thumbnail_url($i);
      if (!empty($f_image)) {
        $csvData[$i]['image'] = $f_image;
      }
      $csvData[$i]['venues_for_performance'] = $venues_for_performance;
      $csvData[$i]['price'] = $price;
      $csvData[$i]['reference_link'] = $reference_link;
      $csvData[$i]['description'] = $val['post_content'];
      $i++;
      print_r($csvData);
    }
  }

  header('Content-Type: text/csv; charset=uft-8');
  header('Content-Disposition: attachment; filename="data.csv"');
  $output = fopen('php://output', 'w');

  fputcsv($output, array('State','Title', 'Director', 'Author', 'Company Name', 'City', 'Event date', 'Type of event', 'Venues for performance', 'Price', 'Reference link', 'Image', 'Description'));
  for($i = 0 ; $i<sizeof($csvData); $i++){
    fputcsv($output, array($csvData[$i]['state'], $csvData[$i]['title'], $csvData[$i]['director'], $csvData[$i]['author'], $csvData[$i]['companys_name'], $csvData[$i]['city'], $csvData[$i]['event_date'], $csvData[$i]['type_of_event'], $csvData[$i]['venues_for_performance'], $csvData[$i]['price'], $csvData[$i]['reference_link'], $csvData[$i]['image'], $csvData[$i]['description']));
  }

  fclose($output);
}

add_action('wp_ajax_getCSVdata', 'getCSVdata');
add_action('wp_ajax_nopriv_getCSVdata', 'getCSVdata');


function filter_jetpack_infinite_scroll_js_settings($settings)
{
  $settings['text'] = __('MORE STORIES', 'l18n');

  return $settings;
}
add_filter('infinite_scroll_js_settings', 'filter_jetpack_infinite_scroll_js_settings');


function hidden_category($separator)
{
  $first_time = 1;
  foreach ((get_the_category()) as $category) {
    if ($category->cat_ID != '1078' && $category->cat_ID != '1099' && $category->cat_ID != '1101' && $category->cat_ID != '1079' && $category->cat_ID != '1084' && $category->cat_ID != '1086' && $category->cat_ID != '1088' && $category->cat_ID != '1089' && $category->cat_ID != '1090' && $category->cat_ID != '1092' && $category->cat_ID != '1068') {
      if ($first_time == 1) {
        echo '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__("View all posts in %s"), $category->name) . '" ' . '>'  . $category->name . '</a>';
        $first_time = 0;
      } else {
        echo $separator . '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__("View all posts in %s"), $category->name) . '" ' . '>' . $category->name . '</a>';
      }
    }
  }
}
add_filter('category_description', 'do_shortcode');


if (function_exists('acf_add_options_page')) {
  acf_add_options_page(
    array(
      'page_title'  =>  'Event Filters',
      'menu_title'  =>  'Event Filters',
      'menu_slug'   =>  'posts-filters',
      'capability'  =>  'edit_posts',
      'icon_url'    =>  'dashicons-admin-post'

    )
  );
}
//////////////////

add_filter('use_block_editor_for_post', '__return_false', 10);
add_theme_support( 'block-templates' );
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' ); 
?>