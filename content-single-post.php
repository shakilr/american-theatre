<?php



/* function  query_group_by_filter($groupby){
    global $wpdb;

    return $wpdb->postmeta . '.meta_key = "state"';
 }

add_filter('posts_groupby', 'query_group_by_filter'); */

$PostID =  get_the_ID();

$custom = get_post_custom();
//var_dump( $custom ); exit;;

if(isset($custom['post_event_date'])) {
//  $postCreationDate = date('Y-m-d', strtotime($custom['post_creation_date'][0]));
    $postEventDate = $custom['post_event_date'][0];
}

//echo  $postEventDate; exit;
//20-10-29$postEventDate
//$today = date('Y-m-d', strtotime('-6 hours'));
if ($postEventDate != ""){
    $args = array(
        'post_type'   => 'events',
        'posts_per_page' => -1,
        'tag'         => $postEventDate,
        'meta_query' => array(
            'd_rank_clause' => array(
                'key' => 'state',
                'compare' => 'EXISTS',
            ),
            'p_date_clause' => array(
                'key' => 'companys_name',
                'compare' => 'EXISTS',
            ),
        ),
        'orderby' => array(
            'd_rank_clause' => 'ASC',
            'p_date_clause' => 'ASC',
        ),


    );


//$t = wp_get_post_tags($PostID);
//print_r($t); exit;
    $eventPosts = get_posts($args);
}
else{
    $eventPosts = "";
}
//echo '<pre>'; print_r($eventPosts); exit;

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
        <div class="entry-deck"><?php $post_excerpt = get_the_excerpt();
            if($post_excerpt !='') {the_excerpt(); }?> </div>


        <div class="entry-byline">      By <?php the_author( _x( ', ', 'Used between list items, there is a space after the comma.', 'ridizain' ) ); ?></div>

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
        if( $eventPosts ): $stateGroup = ""; $count =0; $divClose = "";?>

        <?php
        $all_states = array();
        foreach( $eventPosts as $_state ){
            $state_ = get_post_meta($_state->ID, 'state', true);
            array_push( $all_states, $state_ );
        }
        $all_states = array_unique( $all_states );

        ?>

        <!--  <input type="text" name="search_state" id="search_state" value="" placeholder="enter state">-->
        <form method="GET">
            <select name="state" id="list-state">
                <option value="">Select State</option>
                <?php foreach( $all_states as $mystate ):
                    if($mystate == $_GET['state']){
                        $selected = 'selected';
                    } else{
                        $selected = '';
                    }
                    ?>
                    <option value="<?php echo $mystate ?>" <?php echo $selected; ?>> <?php echo $mystate; ?> </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" id="state-submit" style="display:none;">Search</button>
        </form>
        <input type="hidden" name="start_date" id="start_date" value="<?php echo $postStartDate; ?>" >

        <?php
        foreach( $eventPosts as $post ):
        setup_postdata($post);
        $state = get_field('state');

        //        if (($_GET['state'] && $state == $_GET['state']) || !$_GET['state']){
        //
        //        }
        if ( $state != $stateGroup){

        ?>
    </div>

    <div class="events events-<?php echo $state; ?>">
        <h4 style="text-align: left;">
                     <span style="color: #993300;">
                        <strong><?php echo $state;?></strong></span>
        </h4>

        <?php

        $stateGroup = $state;
        }

        $eventDate = date('D M d', strtotime(get_field('start_date')));
        $eventEndDate = date('D M d', strtotime(get_field('end_date')));
        ?>

        <div class="row">
            <div class="col-md-6">
                <div class="event-detail">
                    <p style="font-weight: bold;font-style: italic;"><?php the_title() ?></p>
                    <?php if( get_field('author') ): ?>
                    <p><span>By: </span><?php the_field('author') ?></p>
                    <?php endif; ?>
                    <?php if( get_field('director') ): ?>
                    <p><span>Directed by: </span><?php the_field('director') ?></p>
                    <?php endif; ?>
                    <?php if( get_field('companys_name') ): ?>
                    <p><?php the_field('companys_name') ?></p>
                    <?php endif; ?>
                    <?php if( get_field('city') ): ?>
                    <p><?php the_field('city') ?></p>
                    <?php endif; ?>
                    <p><span>Event Date(s): </span><?php echo $eventDate . ' - ' . $eventEndDate;?></p>
                    <?php if( get_field('type_of_event') ): ?>
                    <p><span>Type of Event: </span><?php the_field('type_of_event') ?></p>
                    <?php endif; ?>
                    <?php if( get_field('venues_for_performance') ): ?>
                    <p><span>Venue: </span><?php the_field('venues_for_performance') ?></p>
                    <?php endif; ?>
                    <?php if( get_field('price') ): ?>
                    <p><span>Price: </span><?php the_field('price') ?></p>
                    <?php endif; ?>
                    <?php if( get_field('reference_link') ): ?>
                    <p><span>Reference Link: </span><a href="<?php the_field('reference_link') ?>" target="_blank"><?php the_field('reference_link') ?></a></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 event-img">
                <?php
                $f_image = get_the_post_thumbnail_url( get_the_ID() );
                if (!empty($f_image)){
                    ?>
                    <img src="<?php echo $f_image; ?>" alt="" />
                    <?php
                };   $oldstate = $stateGroup ;  ?>
                <?php //if ($oldstate!= $stateGroup) { echo "</div>"; } ?>
            </div>
            <div class="event-detail event-description">
                <p><span>Description: </span><?php the_content() ?></p>
            </div>
        </div>

        <?php
        endforeach;
        ?>
        <?php wp_reset_postdata(); ?>

        <?php endif; ?>

    </div><!-- .entry-content -->

    <?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>

</article><!-- #post-## -->

<?php if($_GET['state']){ ?>
    <style>
        .events:not(.events-<?php echo $_GET['state']; ?>) {
            display: none;
    </style>
<?php } ?>
<Script>

    jQuery( '#list-state' ).change(function(){
        jQuery( "#state-submit" ).click();
    });

    jQuery("#search_state").keyup(function(){

        // var state    = jQuery('#search_state').val();
        // var start_date = jQuery('#start_date').val();
        //var ajax_url = "<?php //admin_url('admin-ajax.php'); ?>//";
        //
        //var str = '&state=' + state + '&start_date=' + start_date + '';
        //jQuery.ajax({
        //    type: "POST",
        //    dataType: "html",
        //    url: ajax_url,
        //    data: {
        //        action : 'events_details_callback',
        //        state: state,
        //        start_date: start_date
        //    },
        //    success: function (data) {
        //        console.log(data);
        //        var $data = jQuery(data);
        //        if ($data.length) {
        //            jQuery("#ajax-posts").append($data);
        //
        //        }
        //
        //    },
        //
        //
        //});
        //return false;
    });

</Script>