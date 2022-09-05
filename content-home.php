<?php
/**
 * The default template for displaying content. Modified by American Theatre staffer Diep Tran.
 *
 * Used for home blog feed only.
 *
 *
 * @package Ridizain`
 * @since Ridizain 1.0.03
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php ridizain_post_thumbnail(); ?>

	<header class="entry-header">
	
		<div class="entry-meta">
			<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && ridizain_categorized_blog() ) : ?>
		
			<span class="cat-links"><?php hidden_category(', ') ?></span>
	
			<?php
				endif;

				edit_post_link( __( 'Edit', 'ridizain' ), '<span class="edit-link">', '</span>' );
			?>
		</div><!-- .entry-meta -->

<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );	?>
<div class="entry-byline">	    By <?php the_author( _x( ', ', 'Used between list items, there is a space after the comma.', 'ridizain' ) ); ?></div>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
