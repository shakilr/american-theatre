<?php
/**
 * The template for displaying featured posts on the front page. Modified by Diep Tran to include author's byline.
 *
 *
 * @package Ridizain
 * @since Ridizain 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>">
	<?php
		// Output the featured image.
		if ( has_post_thumbnail() ) :
			if ( 'grid' == get_theme_mod( 'featured_content_layout' ) ) {
				the_post_thumbnail();
			} else {
				the_post_thumbnail( 'ridizain-full-width' );
			}
		endif;
	?>
	</a>

	<header class="entry-header">
		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && ridizain_categorized_blog() ) : ?>
		<div class="entry-meta">
			<span class="cat-links"><?php hidden_category(' | ') ?></span>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php 
		    the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h1>' );
      	?>
	
	<div class="entry-byline">	    By <?php the_author( _x( ', ', 'Used between list items, there is a space after the comma.', 'ridizain' ) ); ?></div>
  	
	</header><!-- .entry-header -->


</article><!-- #post-## -->
