<?php /* Template Name: 1222 Events*/ ?>


<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/duotone.css" integrity="sha384-R3QzTxyukP03CMqKFe0ssp5wUvBPEyy9ZspCB+Y01fEjhMwcXixTyeot+S40+AjZ" crossorigin="anonymous"/>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css" integrity="sha384-eHoocPgXsiuZh+Yy6+7DsKAerLXyJmu2Hadh4QYyt+8v86geixVYwFqUvMU8X90l" crossorigin="anonymous"/>
<style>
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
		border: 16px solid #f3f3f3; /* Light grey */
		border-top: 16px solid #5cb85c; /* Blue */
		border-radius: 50%;
		width: 20px;
		height: 20px;
		animation: spin 2s linear infinite;
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
	
	.row.not_empty.item_3, .row.not_empty.item_4 {
    display: none !important;
}
</style>
<?php
get_header(); 
$args 	= array();

$states = array();
$venues = array();

$from 	= get_field('pre_events_start_date', 'option');
$to 	= get_field('pre_events_end_date', 'option');

$args = [
	'post_type'		=>'events',
	'post_status' 	=> 'publish',
	'posts_per_page' => -1,

];

$args['meta_query'][] = array(
	'type'    	=> 'DATE',
	'key' 		=> 'start_date',
	'value' 	=> [$from,$to],
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

	while( $query->have_posts() )
 {
	$query->the_post();


	if ( ! in_array( get_post_meta( $post->ID, 'state', true ), $states ) ) {
		$states[] = get_post_meta( $post->ID, 'state', true );
		$data_s[]['state'] = get_post_meta( $post->ID, 'state', true );
	}
	$skey = array_search(get_post_meta( $post->ID, 'state', true ), $states );

	if ( $skey !== false) {
		if ( ! in_array( get_post_meta( $post->ID, 'companys_name', true ), array_column($venue, 'title') ) ) {
			$venue[]['title'] = get_post_meta( $post->ID, 'companys_name', true );
			$data_s[$skey]['venue'][]['title'] = get_post_meta( $post->ID, 'companys_name', true );
		}
	}

	if ( is_array( array_column($venue, 'title')  )) {
		$vkey = @array_search(get_post_meta( $post->ID, 'companys_name', true ),  array_column($data_s[$skey]['venue'], 'title') );
		if ( $vkey !== false) {
			$data_s[$skey]['venue'][$vkey]['posts'][] = get_the_ID();
		}	

	}
	
}

$prices = array_column($data_s, 'title');
array_multisort($data_s);
// echo '<pre>';
// print_r($data_s);
// print_r($prices);

$last_char = '';
$aplha_output = '';
foreach( $data_s as $alpha_char )  { 

	if ( ! $last_char || $last_char !== $alpha_char['state'][0] ) {
		$last_char = $alpha_char['state'][0];
		// echo $alpha_char['state'][0];
		$aplha_output .= '<li style="display:inline-block; margin-right:15px">';

		$aplha_output .= "<a class=\"alphaBtn\" href=\"#tab-{$alpha_char['state'][0]}\">";
		$aplha_output .= $alpha_char['state'][0];
		$aplha_output .= '</a>';
		
		$aplha_output .= '</li>';
	}
	
}

if ( $aplha_output ) {
	echo "<ul class=\"pagination\" style=\"margin-left: 175px;\">{$aplha_output}</ul>";
}


foreach( $data_s as $state )  :
	

	if ( $last_char !== $state['state'][0] ) {
		echo $last_char;
		echo '</div>';
	}
	
	if ( ! $last_char || $last_char !== $state['state'][0] ) {
		$last_char = $state['state'][0];
		echo '<div class="events events-' . $last_char . '" id="tab-' . $last_char . '" style="margin-left:255px">';
	}

?>
	
		<h4 class="state_name" style="text-align: left;">
			<span style="color: #993300; float:left;">
				<strong><?php echo str_replace('-',' ',$state['state']); ?></strong>
			</span>
		</h4>

		<?php
			foreach( $state['venue'] as $key => $location ) :
				$id = str_replace( ' ', '', strtolower($state['state']) ) . '-' . $key;
		?>
			<div class="row not_empty">
				<div class="col-md-12">
					<div class="event-detail" id="accordion">
						<button type="button" class="accordion" data-id="acc_<?php echo $id; ?>"><?php echo $location['title']; ?></button>

						<?php if ( count( $location['posts'] )) : ?>
							<div class="panel" style="display:none; "id="acc_<?php echo $id; ?>">
						
								<?php foreach( $location['posts'] as $post ) : ?>
									<div class="panel status-" id="content_1">
										<div class="row details">
											<div class="col-md-6 description">
												<p style="font-weight: bold;font-style: italic;"><?php echo get_the_title($post);?></p>
												<p><span>By: </span>Deneen Reynolds-Knott</p>
												<p><span>Directed by: </span>Tiffany Nichole Greene</p>
												<p><span>Event Date(s): </span>Sep 17 2021-Sep 26 2021</p>
												<p><span>Type of Event: </span>Theatre Performance</p>
												<p><span>Venue: </span>Outdoor — ASF Grounds</p>
												<p><span>City: </span>Montgomery</p>
												<p><span>Price: </span>$40</p>
												<p><span>Reference Link: </span><a href="https://asf.net/shoebox-picnic-roadside" target="_blank">https://asf.net/shoebox-picnic-roadside</a></p>
											</div>
											<div class="col-md-6 event-img">
												<img src="https://www.americantheatre.org/wp-content/uploads/shoebox.jpeg" alt="">
											</div>
										</div>
									</div>
								<?php endforeach; ?>

							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		
	
<?php endforeach;
get_footer();