<?php 
/**
 * The Template for displaying all single posts
 *
 *
 * @package Ridizain
 * @since Ridizain 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					//get_the_category( int $post_id = false )
					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					$PostID =  get_the_ID();  
                     $cat = get_the_category($PostID);

                     //print_r($cat[0]->cat_ID);
					//exit;
                     if($cat[0]->cat_ID == 12376){
                     
    get_template_part('content-single-event-post', get_post_format());
        			}
        			else{

        				
					get_template_part( 'content-single-post', get_post_format() );
						}
					// Previous/next post navigation.
					ridizain_post_nav('','Previous Story','Next Story'); 	

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
