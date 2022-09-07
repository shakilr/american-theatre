<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 *
 * @package Ridizain
 * @since Ridizain 1.0
 */
?>
   
	
		</div><!-- #main -->
</div><!-- #page -->
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Billboard Ad') ) : ?>
      <?php endif; ?>
		<footer id="colophon" class="site-footer" role="contentinfo">



			<?php get_sidebar( 'footer' ); ?>

			<div class="site-info">
				<p>©2017 <a href="http://www.tcg.org"  target="_blank">Theatre Communications Group</a>. All rights reserved.</p>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	<?php wp_footer(); ?>
<!-- JavaScript Ad Code (Footer) -->
<script type="text/javascript">
jQuery(document).ready(function(){

//     jQuery.getJSON("https://api.ipify.org?format=jsonp&callback=?",
//       function(json) {
//         var myIp = json.ip;
//         jQuery("div").each(function(){
//         jQuery("#"+this.id).load("https://tcg.addyshack.com/index.cfm?e=view-ad&gid="+this.id+"&ipAddress="+myIp);
			
// 		//console.log("ids: "+this.id);	
			
//       });
//     }
//     );
		
});
</script>

<script>
	var galleries = {};
 
		jQuery('.gallery').each(function(i,item){
        var id = jQuery(this).attr('id');
        var imgs = Array();
        var children = jQuery(this).find('.gallery-item');
        jQuery.each(children,function(){
            var imgSrc = jQuery(this).find('img').attr('src'); 
            var caption = jQuery(this).find('.wp-caption-text').html(); 
            var imgItem = imgSrc + "::" + caption;
            imgItem = imgItem.trim()
                        .replace(/[\n\r]+/g, '')
                        .replace(/\s{2,10}/g, '')
                        .replace('undefined','');
            imgs.push(imgItem);
        })
        galleries[id] = imgs;
    });
    
	</script>

<script id="carousel-template" type="text/x-handlebars-template">
        <div class="carousel-wrap" data-gallery="{{gallery}}">
        {{#each imgObj}}        
        <div class="boxed-style clearfix {{hiddenOrNot @index}}" data-index="box-index-{{@index}}">      
        <div class="box-style col-lg-8 col-md-8 col-sm-8 col-xs-12" style="background-image:url('{{imgSrc}}')">
            <div class="triangle-left-large hidden-xs"></div>
            <div class="triangle-top-large visible-xs"></div>
        </div>       
        <div class="box-style-copy col-lg-4 col-md-4 col-sm-4 col-xs-12">{{txt}}</div>         
        </div>
        {{/each}}
        <a href="#" class="carousel-previous visible-xs">Previous</a>
        <a href="#" class="carousel-next visible-xs">Next</a>        
        <div class="carousel-controls">
        {{#each imgObj}}       
            <a class="trig"
                data-index="box-index-{{@index}}" 
                href="#"><span class="fa fa-circle" aria-hidden="true"></span></a>
        {{/each}}
        </div> </div>
</script>

</body>
</html>
