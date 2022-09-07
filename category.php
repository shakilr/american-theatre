<?php

/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 *
 * @package Ridizain
 * @since Ridizain 1.0
 */

if ($_SERVER['REQUEST_URI'] == '/category/onstage-now/') {

?>

	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/duotone.css" integrity="sha384-R3QzTxyukP03CMqKFe0ssp5wUvBPEyy9ZspCB+Y01fEjhMwcXixTyeot+S40+AjZ" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css" integrity="sha384-eHoocPgXsiuZh+Yy6+7DsKAerLXyJmu2Hadh4QYyt+8v86geixVYwFqUvMU8X90l" crossorigin="anonymous" />
	<style>
		html {
			scroll-behavior: smooth !important;
		}

		.accordion {
			background-color: #fff;
			color: #444;
			cursor: pointer;
			padding: 18px;
			width: 100%;
			border: 1px solid black;
			text-align: left;
			outline: none;
			font-size: 15px;
			transition: 0.4s;
		}

		div#accordion {
			margin-bottom: 10px;
		}

		.active,
		.accordion:hover {
			background-color: #fff;
			border: 1px solid black;
			color: #444;
		}

		.events .row {
			display: inline !important;
			flex-wrap: wrap;
		}

		.panel {
			padding: 0 18px;
			/*display: none;*/
			background-color: white;
			overflow: hidden;
		}

		.smt {
			margin-top: 10px;
		}

		button:hover,
		button:focus,
		.contributor-posts-link:hover,
		input[type="button"]:hover,
		input[type="button"]:focus,
		input[type="reset"]:hover,
		input[type="reset"]:focus,
		input[type="submit"]:hover,
		input[type="submit"]:focus {
			background-color: #fff !important;
			border: 1px solid black !important;
			color: #444 !important;
		}

		.description {
			float: left;
			width: 50%;
			margin-top: 20px;
		}

		.event-img {
			float: right;
			width: 50%;
			margin-top: 20px;
		}

		.accordion::after {
			content: '+';
			float: right;
		}

		.active.accordion::after {
			content: '-';
			float: right;
		}

		#infinite-handle {
			display: none !important;
		}

		.btn-success {
			color: #fff;
			background-color: #5cb85c;
			border-color: #4cae4c;
			padding: 10px;
		}

		.loader {
			border: 16px solid #f3f3f3;
			/* Light grey */
			border-top: 16px solid #5cb85c;
			/* Blue */
			border-radius: 50%;
			width: 20px;
			height: 20px;
			animation: spin 2s linear infinite;
		}

		@keyframes spin {
			0% {
				transform: rotate(0deg);
			}

			100% {
				transform: rotate(360deg);
			}
		}

		.events .event-description {
			display: block !important;
			clear: both;
			margin-top: 40px;
		}
	</style>
	<?php
	get_header(); ?>
	<section id="primary" class="content-area">
		<?php
		$args 	= array();

		$states = array();
		$venues = array();

		$from = get_field('events_start_date', 'option');
		$to = get_field('events_end_date', 'option');

		$args = [
			'post_type'		=> 'events',
			'post_status' 	=> 'publish',
			'posts_per_page' => -1,

		];

		$args['meta_query'][] = array(
			'type'    	=> 'DATE',
			'key' 		=> 'start_date',
			'value' 	=> [$from, $to],
			'compare' 	=> 'BETWEEN',
		);

		$args['meta_query'][] = array(
			'key' 		=> 'state',
			'value' => '',
			'compare' 	=> '!=',
		);

		$args['meta_query'][] = array(
			'key' 		=> 'companys_name',
			'value' => '',
			'compare' 	=> '!=',
		);

		$data_s  = $states = $venue =  [];
		$query = new WP_Query($args);

		while ($query->have_posts()) {
			$query->the_post();


			$temp_state = get_post_meta($post->ID, 'state', true);
			$temp_value = str_replace( '  ', ' ', trim(get_post_meta($post->ID, 'companys_name', true)));

			if (!in_array($temp_state, $states)) {
				$states[] = $temp_state;
				$data_s[]['state'] = $temp_state;
			}
			$skey = array_search($temp_state, $states);

			if ($skey !== false) {
				if (!in_array($temp_value, array_column($venue, 'title'))) {
					$venue[]['title'] = $temp_value;
					$data_s[$skey]['venue'][]['title'] = $temp_value;
				}
			}

			if (is_array(array_column($venue, 'title'))) {
				$vkey = @array_search($temp_value,  array_column($data_s[$skey]['venue'], 'title'));
				if ($vkey !== false) {
					$data_s[$skey]['venue'][$vkey]['posts'][] = [ 
						'title' => get_the_title(get_the_ID()), 
						'id' => get_the_ID()
					];
				}
				sort($data_s[$skey]['venue'][$vkey]['posts']);
			}
		}

		$prices = array_column($data_s, 'title');
		array_multisort($data_s);

// 		echo '<pre>';
// 			print_r($data_s);

// 			die();

		$last_char = '';
		$aplha_output = '';
		foreach ($data_s as $alpha_char) {

			if (!$last_char || $last_char !== $alpha_char['state'][0]) {
				$last_char = $alpha_char['state'][0];
				// echo $alpha_char['state'][0];
				$aplha_output .= '<li style="display:inline-block; margin-right:15px">';

				$aplha_output .= "<a class=\"alphaBtn\" href=\"#tab-{$alpha_char['state'][0]}\">";
				$aplha_output .= $alpha_char['state'][0];
				$aplha_output .= '</a>';

				$aplha_output .= '</li>';
			}
		}

		if ($aplha_output) {
			echo "<div align=\"center\"> <ul class=\"pagination\" style=\"margin-left: 175px;\">{$aplha_output}</ul> </div>";
		} ?>
		<div id="content" class="site-content" role="main">
			<header class="archive-header" style="float:left">
				<h1 class="archive-title"><?php printf(__('%s', 'ridizain'), single_cat_title('', false)); ?></h1>
				<!-- <h1 class="archive-title">
					<?php $from = date("Y", strtotime($from));
					$to = date("Y", strtotime($to));
					echo $from;
					echo "-";
					echo $to; ?>
					<?php //print_r($from); exit;
					?>
				</h1> -->
				<div class="taxonomy-description" data-uw-styling-context="true">
					<p data-uw-styling-context="true">
						<?php echo category_description(get_category_by_slug('onstage-now')->term_id); ?>
				</div>


				<?php
				$term_description = term_description();
				if (!empty($term_description)) :
					printf('<div class="taxonomy-description">%s</div>', $term_description);
				endif;
				?>
			</header>
			<?php
			foreach ($data_s as $state) :


				if ($last_char !== $state['state'][0]) {
					// echo $last_char;
					echo '</div>';
				}

				if (!$last_char || $last_char !== $state['state'][0]) {
					$last_char = $state['state'][0];
					echo '<div class="events events-' . $last_char . '" id="tab-' . $last_char . '" style="margin-left:255px">';
				}

			?>

				<h4 class="state_name" style="text-align: left;">
					<span style="color: #993300; float:left;">
						<strong><?php echo str_replace('-', ' ', $state['state']); ?></strong>
					</span>
				</h4>

				<?php
				foreach ($state['venue'] as $key => $location) :
					$id = str_replace(' ', '', strtolower($state['state'])) . '-' . $key;
				?>
					<div class="row not_empty">
						<div class="col-md-12">
							<div class="event-detail" id="accordion">
								<button type="button" class="accordion" data-id="acc_<?php echo $id; ?>"><?php echo $location['title']; ?></button>

								<?php if (count($location['posts'])) : ?>
									<div class="panel" style="display:none; " id="acc_<?php echo $id; ?>">

										<?php
											foreach ($location['posts'] as $postdata ) :
												global $post;
												$post = $postdata['id'];
											setup_postdata($post);
										?>
											<div class="panel status-" id="content_1">
												<div class="row details">
													<div class="col-md-6 description">
														<p style="font-weight: bold;font-style: italic;">
															<?php the_title(); ?>
														</p>
														<p>
															<span>By: </span>
															<?php echo get_post_meta(get_the_ID(), 'author', true); ?>
														</p>
														<p>
															<span>Directed by: </span>
															<?php echo get_post_meta(get_the_ID(), 'director', true); ?>
														</p>
														<p>
															<span>Event Date(s): </span>
															<?php echo get_post_meta(get_the_ID(), 'start_date', true); ?>
														</p>
														<p>
															<span>Type of Event: </span>
															<?php echo get_post_meta(get_the_ID(), 'type_of_event', true); ?>
														</p>
														<p>
															<span>Venue: </span>
															<?php echo get_post_meta(get_the_ID(), 'venues_for_performance', true); ?>
														</p>
														<p>
															<span>City: </span>
															<?php echo get_post_meta(get_the_ID(), 'city', true); ?>
														</p>
														<p>
															<span>Price: </span>
															<?php echo get_post_meta(get_the_ID(), 'price', true); ?>
														</p>
														<p>
															<span>Reference Link: </span>
															<a href="<?php echo get_post_meta(get_the_ID(), 'reference_link', true); ?>" target="_blank"><?php echo get_post_meta($post, 'reference_link', true); ?></a>
														</p>
													</div>
													<div class="col-md-6 event-img">
														<?php if (has_post_thumbnail()) { ?>
															
															<a href="<?php echo get_the_permalink(); ?>">
																<?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'full') ; ?>
														</a>
														<?php } ?>
														<!-- <img src="https://www.americantheatre.org/wp-content/uploads/shoebox.jpeg"> -->
													</div>
												</div>
												<div class=" row event-detail event-description">
													<p><span>Description: </span>
														<?php echo get_the_content(); ?></p>
												</div>
											</div>
										<?php endforeach; wp_reset_postdata(); ?>

									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>


			<?php endforeach; ?>
		</div><!-- #content -->

	</section><!-- #primary -->
	<script>
		jQuery(document).ready(function() {
			// jQuery('.panel').hide();
			jQuery(document).on('click', '#accordion button', function() {
				let idssss = jQuery(this).data('id');
				jQuery(this).closest('.event-detail').find('#' + idssss).toggle();
			});
		});
	</script>
	<?php
// 	get_sidebar('content');
// 	get_sidebar();
	get_footer();
	?>

<?php

} else {

	get_header();


?>
	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if (have_posts()) : ?>

				<header class="archive-header">
					<h1 class="archive-title"><?php printf(__('Category Archives: %s', 'ridizain'), single_cat_title('', false)); ?></h1>

					<?php
					// Show an optional term description.
					$ridizain_term_desc = term_description();
					if (!empty($ridizain_term_desc)) :
						printf('<div class="taxonomy-description">%s</div>', $ridizain_term_desc);
					endif;
					?>
				</header><!-- .archive-header -->

			<?php
				// Start the Loop.
				while (have_posts()) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part('content', get_post_format());

				endwhile;
				// Previous/next page navigation.
				ridizain_paging_nav();

			else :
				// If no content, include the "No posts found" template.
				get_template_part('content', 'none');

			endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
	get_sidebar('content');
	get_sidebar();
	get_footer();
}
