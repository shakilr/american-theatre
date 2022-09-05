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
// print_r($prices);

foreach( $data_s as $state ) 
{
	echo "<h2>{$state['state']}</h2>";

	foreach( $state['venue'] as $location ) {
		echo "<h3>&nbsp;&nbsp;&nbsp;-{$location['title']}</h3>";

		foreach( $location['posts'] as $post ) {
			echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--' . get_the_title($post).'</p>';
		}
	}
}
	// if ( array_key_exists( get_post_meta( $post->ID, 'state', true ), $data_s ) ) {
	// 	$data_s[get_post_meta( $post->ID, 'state', true )][get_post_meta( $post->ID, 'companys_name', true )] = [];
	// }

// 	if ( $state_key = array_search(get_post_meta( $post->ID, 'state', true ), array_column($data_s, 'state') ) ) {


	
// 		// if ( $venue_key = array_search(get_post_meta( $post->ID, 'companys_name', true ), array_column($data_s, 'venue') ) ) {
// 		echo '<pre>';

// 		print_r($data_s[$state_key] );
// 			// if ( ! in_array( get_post_meta( $post->ID, 'companys_name', true ), $data_s[$state_key]['venue']  )) {
// 			// // echo '<pre>';
// 			// // print_r($data_s);
// 			// $data_s[$state_key]['venue'][] = get_post_meta( $post->ID, 'companys_name', true );
// 			// } else {
			
// 			// 	$data_s[$state_key]['venue'] = [];
// 			// }
// 	}


//  }

/*foreach($query as $single){
 $venues .= $single->venue;
//  $states .= $single->state;
print_r($single->venue);
}*/
// exit;
// echo '<pre>';
// // echo 'Count:'.$query->found_posts.'<br/>';
// print_r($data_s);
// echo '</pre>';


exit;

$getData = array();
$from = get_field('pre_events_start_date', 'option');
$to = get_field('pre_events_end_date', 'option');

//print_r($from); echo '<br/> end date:';
//print_r($to);
//exit;

$getStates = $wpdb->get_results('SELECT post_id,meta_value from `wp_postmeta` where `meta_key` = "state" and meta_value != "" GROUP BY post_id  ORDER BY meta_value  ');
// echo '<pre>'; print_r($getStates); exit();

$alphaArray = array();
foreach($getStates as $key => $states){
	$value = $states->meta_value;
	$post_id = $states->post_id;
	if (!in_array(strtolower($value[0]), $alphaArray)){
		$alphaArray[] = strtolower($value[0]);
	}	

	$getCompanyDetails = $wpdb->get_results('SELECT meta_key,meta_value from `wp_postmeta` where `meta_key` = "companys_name" and post_id  = "'.$post_id.'"');
	
	$getdate = $wpdb->get_results('SELECT m.meta_key,m.meta_value from `wp_postmeta` m INNER join wp_posts p on p.ID = m.post_id
	where  `meta_key` = "start_date" and  post_id  = "'.$post_id.'" and p.post_status = "publish" ');
	
	//$s_date = date("Y-m",strtotime($getdate[0]->meta_value));
	$s_date = "2021-08-01";

	if($s_date >= $from && $s_date <= $to) {
	//print_r($from .' to '.$to.' & the date is: '.$s_date);
	//exit;		
		$j = 0;
		foreach($getCompanyDetails as $company){
			if (array_key_exists($value, $getData)) {
				$getData[$value][$j]['name'].= ",".$company->meta_value;
			}else {
				$getData[$value][$j]['name'] = $company->meta_value;
			}		
			$j++;
		}
	}
}
//echo '<pre>'; print_r($getData); exit();
//echo "<pre>"; print_r($alphaArray); exit;
// foreach($getData as $state => $stateValue){
// 	$new = explode(",",$stateValue[0]['name']);
// 	natcasesort($new);
// 	echo "<pre>"; print_r(array_unique($new)); 
// 	foreach($new as $v){
// 	  //echo $v.'<br>';
// 	}
// }
// exit;
//echo "<pre>"; print_r($getData);
//$result = array_map(fn($v) => array_combine($keys, $v), $getData);
//echo "<pre>"; print_r($result);

//exit;


// $args = array(
// 	'post_type'   => 'events',
// 	'posts_per_page' => -1,
// 	'meta_key' => 'state',
// 	'orderby'  => 'meta_value',
// 	'order'    => 'ASC',
// 	'meta_query' => array(
// 		'relation' => 'AND',
// 		array(
// 			'key' => 'start_date',
// 			'value' => $from,
// 			'compare' => '>=',
// 			'type' => 'date'
// 		),
// 		array(
// 			'key' => 'end_date',
// 			'value' => $to,
// 			'compare' => '<=',
// 			'type' => 'date'
// 		)
// 	)
// );

// global $wpdb;


//exit;

//$eventPosts = get_posts($args);

//echo "<pre>"; print_r($eventPosts); echo "</pre>"; exit;
// $all_states = array();
// foreach ($eventPosts as $_state) {
// 	$state_ = get_post_meta($_state->ID, 'state', true);
// 	array_push($all_states, $state_);
// }
// $all_states = array_unique($all_states);
//echo "<pre>"; print_r($all_states);exit;

$qo = get_queried_object();


get_header(); ?>

<section id="primary" class="content-area">
	<div align="center">  
		<?php  
			echo '<ul class="pagination" style="margin-left: 175px;">';  
			$i = 1;
			foreach($alphaArray as $alphabet){  
				echo '<li style="display:inline-block; margin-right:15px"><a id="alphaBtn-'.$i.'" class="alphaBtn" href="#tab-' . $alphabet . '">'.ucfirst($alphabet).'</a></li>';  
				//echo '<li style="display:inline-block; margin-right:15px" class="azbutton" id = "tab-' . $alphabet . '">'.ucfirst($alphabet).'</li>';  
				$i++;
			}  
			echo '</ul>';  
		?>  
	</div> 
	<div id="content" class="site-content" role="main">

		<?php if (have_posts()) : ?>

			
			<header class="archive-header" style="float:left">
				<h1 class="archive-title"><?php printf(__('%s', 'ridizain'), single_cat_title('', false)); ?></h1>
				<h1 class="archive-title">
					<?php $from = date("Y",strtotime($from)); $to = date("Y",strtotime($to));  echo $from; echo "-"; echo $to; ?>
					<?php //print_r($from); exit;?>
				</h1>
              <div class="taxonomy-description" data-uw-styling-context="true"><p data-uw-styling-context="true">
				  <?php echo category_description( get_category_by_slug( 'onstage-now' )->term_id ); ?> 
			  </div>
				

				<?php
				$term_description = term_description();
				if (!empty($term_description)) :
					printf('<div class="taxonomy-description">%s</div>', $term_description);
				endif;
				?>
			</header>
			
			<div style="float:right">
			<div class="loader" style="display:none"></div>
				<!-- <input type="submit" value="Export" class="btn btn-success" data-id="acc_<?php echo $i; ?>" data-state="<?php echo $state; ?>" data-venue="<?php the_field('companys_name') ?>">
				<a href="javascript:void(0)" id="dlbtn" style="display: none;">
					<button type="button" id="mine">Export</button>
				</a> -->
			</div>

			<?php
			$j = 1;
			$tmp = '';
			foreach($getData as $state => $stateValue){
			//$arrUniqueVenu = array();
			//$i = 1;
			//$getStates = $wpdb->get_results('SELECT * from `wp_postmeta` where `meta_key` = "state" and meta_value != "" GROUP BY meta_value ORDER BY meta_value  ');
           //echo "<pre>"; print_r($getStates); echo "</pre>"; exit;
			//foreach($getStates as $key => $states){
				//echo $states->meta_value."<br>";
				//$value = $states->meta_value;
                
                //echo "<pre>"; print_r($getCompanyDetails);exit;

            
			//foreach ($eventPosts as $post) {
				//setup_postdata($post);
				//$state = get_field('state');
				//if ($state != $stateGroup) {
					//echo "<pre>"; print_r($post); echo "</pre>";
					// echo "<br>";
					$tbs = strtolower($state[0]);
					if(in_array($tbs,$alphaArray)){
						$tbs = $tbs;
					}
					?>
	</div>

	

	<div class="events events-<?php echo $state; ?>" id="tab-<?= $tbs ?>" style="margin-left:255px">
		<h4 class="state_name" style="text-align: left;">
			<span style="color: #993300; float:left;">
				<strong><?php echo str_replace('-',' ',$state); ?></strong>
			</span>
		</h4>
	<?php
					//$stateGroup = $state;
				//}


				// if (in_array(get_field('companys_name'), $arrUniqueVenu)) {
				// 	continue;
				// }
				// $arrUniqueVenu[] = get_field('companys_name');
				//$getCompanyDetails = $wpdb->get_results('SELECT * from `wp_postmeta` where `meta_key` = "companys_name" and meta_value LIKE "%'.$value.'%" GROUP BY meta_value ORDER BY meta_value  ');
				//foreach($getCompanyDetails as $company) {
						
				$new = explode(",",$stateValue[0]['name']);
				$lower_input = array_map('strtolower', $new);
				// $result = array_unique($lower_input);
				// natcasesort($result);
$result[] = '';
foreach($lower_input as $single){
	$single = trim($single);
	if( empty($single) ){ continue;}
	$result[] .= $single;
}
// echo '<pre>';
// print_r($result);
// echo '</pre>';

				$n = 1;

				foreach(array_unique($result, SORT_STRING) as $v){
					// $v = trim($v);
					// echo '<pre>';
					// print_r($result);
					// echo '</pre><hr><pre>';
					// print_r(array_unique($result));
					// if($v === $v){  $d_none = "display:none !important;";}
					// echo '</pre>';
					$i = rand(1,200000);
$n++;
		
	?>

	<div class="row not_empty item_<?php echo $n;?> <?php echo $v?>">
		<div class="col-md-12">
			<div class="event-detail" id="accordion">
			<?php if(get_field('companys_name') == 'Chance Theater' || get_field('companys_name') == 'Arena Stage' || get_field('companys_name') == 'Jobsite Theater' || get_field('companys_name') == 'Centenary Stage Company'){
				echo '';
			}else{ 			//	echo "========="; the_field('start_date');echo "=========";the_field('end_date');				?>
<button type="button" class="accordion" data-id="acc_<?php echo $i; ?>" data-state="<?php echo $state; ?>"><?php echo $v; ?></button>
				<?php 			} ?>
				<!-- first -->
				<div class="panel" style="display: none" id="acc_<?php echo $i; ?>">
				</div>

				<!-- second -->
			</div>
		</div>
		<!-- third -->
	</div>

<?php
}
$j++;
			}
?>

<!-- waseh code ends here -->

<?php
			// Start the Loop.
			//while (have_posts()) : the_post();

			/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
			// get_template_part('content', get_post_format());

			//endwhile;
		// Previous/next page navigation.
		// ridizain_paging_nav();

		else :
		// If no content, include the "No posts found" template.
		// get_template_part('content', 'none');

		endif;
?>


	</div><!-- #content -->

</section><!-- #primary -->

<script>
	jQuery(document).ready(function($) {

		jQuery(document).on('click', 'a[href^="#"]', function(e) {
			// target element id
			var id = jQuery(this).attr('href');
			
			// target element
			var $id = jQuery(id);
			if ($id.length === 0) {
				return;
			}
			
			// prevent standard hash navigation (avoid blinking in IE)
			e.preventDefault();
			
			// top position relative to the document
			var pos = $id.offset().top-350;
			
			// animated top scrolling
			jQuery('body, html').animate({scrollTop: pos});
		});

		jQuery('#load-more').on('click', function(e) {
			e.preventDefault();

			var _this = jQuery(this);
			var curr_page = parseInt(_this.attr('data-page'));
			// var cat = _this.attr('data-cat');

			var data = {
				'action': 'load_more',
				'page': curr_page,
				'tax': '<?php echo $qo->taxonomy; ?>',
				'tax_slug': '<?php echo $qo->slug; ?>',
			};

			jQuery.ajax({
				url: '/wp-admin/admin-ajax.php',
				data: data,
				type: 'POST',
				beforeSend: function(xhr) {
					console.log('Loading...');
				},
				success: function(resp) {

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
	});
</script>

<?php
get_sidebar('content');
get_sidebar();
get_footer();
?>

<script>
	jQuery(".accordion").click(function() {
		console.log('clicked');
		var venuName = jQuery(this).text();
		var statName = jQuery(this).attr('data-state');
		var divID = jQuery(this).attr('data-id');

		jQuery.get(ajaxurl,{
			'action': 'loadDynamicContenst1',
			'venuName': venuName,
			'statName': statName
		},
		function(msg) {
			console.log(msg);
			jQuery('#' + divID).html(msg);
		});
	});

	jQuery('.btn-success').click(function() {
		var venuName = jQuery(this).attr('data-venue');
		var statName = jQuery(this).attr('data-state');
		var divID = jQuery(this).attr('data-id');
		jQuery('.loader').show();

		jQuery.get(ajaxurl, {
				'action': 'getCSVdata'
			},
			
			function(msg) {
				console.log(msg);
				jQuery('.loader').hide();
				setTimeout(function() {
					var dlbtn = document.getElementById("dlbtn");
					var file = new Blob([msg], {type: 'text/csv'});
					dlbtn.href = URL.createObjectURL(file);
					dlbtn.download = 'theatre_shows.csv';
					jQuery( "#mine").click();
				}, 2000);
			});
	});
	// beforeSend: function (){
	// 			alert('abc');
	// 		},

	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var a = jQuery(this).attr('data-id');
			jQuery('#' + a).toggle();
			var panel = this.nextElementSibling;
			if (panel.style.display === "block") {
				panel.style.display = "none !important";
			} else {
				panel.style.display = "block !important";
			}
		});
	}

	jQuery(".events").each(function() {
		if (jQuery(this).children(".not_empty").length == 0) {
			jQuery(this).hide();
		}
	});

	// window.addEventListener("scroll", function(){  console.log(scrollY)  })
	// jQuery(document).ready(function($) {
	// 	jQuery('.alphaBtn').click(function(){
	// 		window.addEventListener("scroll", function(){  console.log(scrollY)  })
	// 	});
	// });
</script>

<style>
	.panel {
		display: block;
	}

	.hide {
		display: none;
	}
</style>