<?php
/*
Template Name: Issue
*/

/*created by AT staffer Diep Tran*/
?>



<?php

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="archive-header">
				<h1 class="archive-title"><?php printf( __( '%s Issue', 'ridizain' ), single_cat_title( '', false ) ); ?></h1>

<div class="issue-info">			
		<div class="cover">

			<?php /*code to insert category images*/
$cat_id = get_query_var('cat');
$cat_data = get_option("category_$cat_id");
if (isset($cat_data['img'])){
echo '<img src="'.$cat_data['img'].'">';
}
?>
    <?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>      
     </div>

		<div class="cover-info">
       <p><span>Subscribe to <i>American Theatre</i>.</span></p>
				<div class="button-container">
				<a href="http://www.tcg.org/Membership/IndividualMembership.aspx" target="_blank" class="issue-button">Subscribe</a></div>
			<p><span>Purchase the print edition here:</span></p>
				<div class="button-container">
				<a href="http://www.tcg.org/Publications/AmericanTheatreMagazine/OrderBackIssues.aspx" target="_blank" class="issue-button">Purchase issue</a></div>
			<p><span>Visit our archives</span><p>
				<div class="button-container">
				<a href="http://www.americantheatre.org/archive" target="_blank" class="issue-button">Visit Archive</a>		
				</div>
		</div>
</div>


<div class="divider"><h2><span>In This Issue</span></h2></div>

</header><!-- .archive-header -->

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
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();