<?php  

   /* function  query_group_by_filter($groupby){
       global $wpdb;

       return $wpdb->postmeta . '.meta_key = "state"';
    }

 add_filter('posts_groupby', 'query_group_by_filter'); */

$PostID =  get_the_ID();   

$custom = get_post_custom();

if(isset($custom['to'])) {
	$postEndDate = date('Y-m-d', strtotime($custom['from'][0]));
}

if(isset($custom['from'])) {

	$postStartDate = date('Y-m-d', strtotime($custom['to'][0]));
}
//echo  $postEndDate; exit;
//20-10-29
//$today = date('Y-m-d', strtotime('-6 hours'));

$args = array(
    'post_type'=> 'events',
    'meta_key' => 'state',
   'orderby' => 'meta_value',
    'order'    => 'ASC',
    'meta_query'=>array(
            'relation'=>'AND',
            array(
                'key' => 'start_date',
                'value' => $postStartDate,
                'compare' => '>=',
                'type' => 'CHAR'
            ),
            array(
                'key' => 'end_date',
                'value' => $postStartDate,
                'compare' =>'>=',
                'type' => 'CHAR'
            )

        )
);              

$eventPosts = get_posts($args);

//$events_query = new WP_Query( $args );
//echo '<pre>'; print_r($events_query);exit;
//echo $postStartDate; exit;
?>


<!--[END] PAYWALL FXN -->

<?php ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) {
	ridizain_post_thumbnail();
	echo '<div class="feature-image-caption">' . get_post( get_post_thumbnail_id() )->post_excerpt . '</div>';}?>				

  
<!-- .feature-image -->

 
       
  
<header class="entry-header">


<div class="entry-meta">
			<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && ridizain_categorized_blog() ) : ?>
		
				<span class="cat-links"><?php hidden_category(' | ') ?></span>

<?php
			endif;
				if ( 'post' == get_post_type() )
					ridizain_posted_on();

				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'ridizain' ), __( '1 Comment', 'ridizain' ), __( '% Comments', 'ridizain' ) ); ?></span>
			<?php
				endif;

				edit_post_link( __( 'Edit', 'ridizain' ), '<span class="edit-link">', '</span>' );
			?>

</div>

		<?php if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"', '</a></h1>' );
			endif;
		?>
	<div class="entry-deck"><?php the_excerpt(); ?> </div>

	
<div class="entry-byline">	    By <?php the_author( _x( ', ', 'Used between list items, there is a space after the comma.', 'ridizain' ) ); ?></div>
  	
	</header><!-- .entry-header -->

	<div class="entry-content">
<?php
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'ridizain' ) );
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'ridizain' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>

		<?php 
if( $eventPosts ): $stateGroup = "";?>
	
	<?php foreach( $eventPosts as $post ): 
		
		setup_postdata( $post );
           
           $state = get_field('state');?>
           <div class ="events">
			<?php if ( $state != $stateGroup){ ?>
       <h4 style="text-align: left;">
	     <span style="color: #993300;">
	     	<strong><?php echo $state;?></strong></span>
	     </h4>
				
				<?php $stateGroup = $state;
			}
			
		     $eventDate = date('D M d', strtotime(get_field('start_date')));
		
		?>

		<p class="p1">
			<span class="s1"><b><?php the_field('companys_name') ?>, </b></span><?php the_field('city') ?>, <?php the_field('price') ?>, <a href="<?php the_field('reference_link') ?>"><i><?php the_field('reference_link') ?></i></a><i><br>
				 </i><?php the_field('author') ?>; dir: <?php the_field('director') ?>. <?php echo $eventDate;?>.<br>
				<i><?php the_field('venues_for_performance') ?>, </i></p>
	
	<?php $image = get_field('attach_photos');// (thumbnail, medium, large, full or custom size)
if( !empty( $image ) ): ?>
	
    <img src="<?php echo esc_url($image['url']); ?>"  alt="<?php echo esc_attr($image['alt']); ?>" />
	<?php endif; ?>
	<?php if ( $state != $stateGroup){ ?>
</div>
<?php } ?>
	<?php endforeach; ?>
	
	</ul>
	
	<?php wp_reset_postdata(); ?>

<?php endif; 
?>

	</div><!-- .entry-content -->

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>

</article><!-- #post-## -->

 

