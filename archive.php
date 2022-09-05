<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Ridizain
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * edited by "American Theatre" staffer Diep Tran to accommodate Byline plug-in to generate author pages (instead of using author.php in original theme)
*
 * @package Ridizain
 * @since Ridizain 1.0
 */

$qo = get_queried_object();

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">

				<h1 class="page-title">
					<?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'ridizain' ), get_the_date() );

						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'ridizain' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'ridizain' ) ) );

						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'ridizain' ), get_the_date( _x( 'Y', 'yearly archives date format', 'ridizain' ) ) );

 elseif ( is_tax('byline') )	 : // checks to see if this is a byline page
				             single_term_title('Stories by ') ;
                            

						else :
							_e( 'Archives', 'ridizain' );

						endif;
					?>
				

</h1>

<?php  echo '<div class="byline-description">' . term_description() . '</div>';?>
			</header><!-- .page-header -->

			<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endwhile;
					// Previous/next page navigation.
					ridizain_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
		</div><!-- #content -->
		<div class="load-more-btn" style="padding: 10px;
    border: 1px solid #000;
    display: inline-block;
    text-align: center;
    width: 22%;
    position: relative;
    left: 50%;">
            	<a id="load-more" data-page="1" data-cat="byline" href="#">Load More</a>
            </div>
	</section><!-- #primary -->

		<script>
		jQuery(document).ready(function($){
			jQuery('#load-more').on('click', function(e){
				e.preventDefault();

				var _this = jQuery(this);
				var curr_page = parseInt(_this.attr('data-page'));
				// var cat = _this.attr('data-cat');

				var data = {
					'action': 'load_more',
	                'page' : curr_page,
	                'tax' : '<?php echo $qo->taxonomy; ?>',
	                'tax_slug' : '<?php echo $qo->slug; ?>',
				};

				jQuery.ajax({
					url: '/wp-admin/admin-ajax.php',
					data : data,
	            	type : 'POST',
	            	beforeSend : function ( xhr ) {
		                console.log('Loading...');
		            },
		            success : function( resp ){

		                console.log(resp);
		                // return;
		                jQuery('#content').append(resp);
		            	// console.log(resp);

		            	var next_page = curr_page + 1;
		            	// console.log(curr_page, next_page);
		            	_this.attr('data-page', next_page);
		            }
				})
			});
		})
	</script>

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();