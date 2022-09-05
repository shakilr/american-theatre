<?php 
/**
 * The default template for displaying content. Modified by "American Theatre" staffer Diep Tran to accommodate Wordpress Jetpack infinite scroll.
 *
 * Originally used for both single and index/archive/search, now used for home/index/archive/search.
 *
 *
 * @package Ridizain
 * @since Ridizain 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <?php if ( is_home() ) :  ?>
  
  	<?php ridizain_post_thumbnail(); ?>

  	<header class="entry-header">

		<?php if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

		<div class="entry-meta">
      
      
			<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && ridizain_categorized_blog() ) : ?>
		
			<span class="cat-links"><?php hidden_category(', ') ?></span>

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
		</div><!-- .entry-meta -->
		
	</header><!-- .entry-header -->
  
  <?php else : ?>

	<header class="entry-header">

		<?php if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

		   
		<div class="entry-meta">
      	  <div class="index-image">
    	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php /*insert images from post even when there's no featured image*/ if ( has_post_thumbnail() ) {
    			the_post_thumbnail( 'thumbnail' );
			} else {
    				$attachments = get_children( array(
       			 				'post_parent' => get_the_ID(),
       		 					'post_status' => 'inherit',
        						'post_type' => 'attachment',
        						'post_mime_type' => 'image',
        						'order' => 'ASC',
        						'orderby' => 'menu_order ID',
        						'numberposts' => 1)
   				 					);
    				foreach ( $attachments as $thumb_id => $attachment ) {
       			 echo wp_get_attachment_image($thumb_id, 'thumbnail');
    						}
			}?></a>
			</div> 
      
			<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && ridizain_categorized_blog() ) : ?>
		
			<span class="cat-links"><?php hidden_category(', ') ?></span>

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
		</div><!-- .entry-meta -->
		
	</header><!-- .entry-header -->
  <?php endif; ?>
  

<?php if ( is_search() || is_archive() || is_home() ) : ?>

	<div class="entry-summary">
			
    
		<?php the_excerpt(); ?>
	
	</div><!-- .entry-summary -->

	<?php else : ?>
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
	</div><!-- .entry-content -->
	<?php endif; ?>

</article><!-- #post-## -->
