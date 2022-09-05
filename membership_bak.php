<?php
   /*
   Template Name: Membership
   */
   
   /*created by AT staffer Diep Tran*/
   ?>
<?php
   get_header(); ?>
   
<section id="primary" class="content-area">
   <div id="content" class="site-content" role="main">
      <?php if ( have_posts() ) : ?>
      <header class="archive-header">
		<?php	if(!is_user_logged_in()) :   ?>	
			<h1 class="archive-title"><?php printf( __( '%s Access Denied', 'ridizain' ), single_cat_title( '', false ) ); ?></h1>
	
			<span>
			Dear reader: We appreciate your support! 
			<br>
			You’ve reached your limit of free stories, so please log in with your TCG membership ID to read this story.	
			</span>
		<?php endif; ?>	
		 
          <!-- .archive-header -->
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
	</header>	
	
			<style>
				.rateCode {
					background-color: #346c9b;
					color: #fff;
					font-size: 16px;
					padding: 7px;
					font-weight: bold;
					text-align: center;
				}
				.price {
					background-color: #F5F3F4;
					color: #346c9b;
					font-size: 16px;
					font-weight: bold;
					text-align: center;
					padding: 12px;
				}
				.buttonDiv{
					width: 130px;
				}	
				.mainDiv{
					width: 750px;
				}
				.listDiv{
					float: left;
					margin-left: 70px;
				}
				    
				.entry-header, .cleanlogin-form-bottom{
					display: none;
				}	
				.titleH1 {
					padding: 0 0 10px 0;
					margin: 10px 0 0 0;
					clear: both;
					font-family: arial, sans-serif;
					font-weight: bold;
					font-size: 20px;
					color: #666;
					line-height: 15px;
				}
				.titleH2 {
					letter-spacing: normal;
					font-size: 20px;
					color: #dd9933 ;
				}	
				.description {
					float: right;
					border-left: 1px solid #ccc8c8;
					padding-left: 15px;
					font-size: 14px; 					
					font-family: Arial; 
				}
				.clearfix {
					width: 770px;
					background-color: transparent;
					border-radius: 0px;
					border-bottom: 2px solid #ededed;
					padding-left: 0px;
					margin: 1em 1px 1em 5px;
				}
				.joinButton{
					margin-top: 48px;
					margin-left: 144px;
					margin-bottom: 20px;
				}				
			</style>	
			
			<?php	if(!is_user_logged_in()) :   ?>	
				
				<span>
					Don't have a membership yet - no problem. 
					<br>
					Chose a membership option that fits your needs below
				</span><br><br>
				<h1 class="titleH1">Membership Listing</h1>

				<h3 class="titleH2">Individual Membership</h2>								
				<div class="mainDiv">
					<div class="listDiv">
						  <a href="http://www.tcg.org/Default.aspx?TabID=1600&productId=36&rateCode=1%20YR%20STD&level2=INDIVIDUAL" target="_blank"><br>
							<div class="buttonDiv">
								<div class="rateCode">1 Year</div>
								<div class="price">$50.00 USD</div>
							</div>
						  </a>
						  <a href="http://www.tcg.org/Default.aspx?TabID=1600&productId=36&rateCode=2%20YR%20STD&level2=INDIVIDUAL" target="_blank"><br>
							<div class="buttonDiv">
								<div class="rateCode">2 Year</div>
								<div class="price">$80.00 USD</div>
							</div>
						  </a>
						  <a href="http://www.tcg.org/Default.aspx?TabID=1600&productId=36&rateCode=STUDENT&level2=INDIVIDUAL" target="_blank"><br>
							<div class="buttonDiv">
								<div class="rateCode">Student Rate</div>
								<div class="price">$25.00 USD</div>
							</div>
						  </a><br>						 
					   </div>
					<div style="float: right;width: 50%;">
					  <span class="description">Connect with the world of professional theatre by joining the TCG network! Stay up to date in the world of not-for-profit theatre and engage with the theatre community at large. Through a TCG Individual Membership, you will have access to resources that will provide an inside perspective on the national theatre landscape and receive 10 issues per year of AMERICAN THEATRE magazine PLUS unlimited access to AmericanTheatre.org.</span><br>
					  <input type="submit" value="Join" class="joinButton" onclick="window.open('http://www.tcg.org/Default.aspx?TabID=1600&productId=36&rateCode=1%20YR%20STD&level2=INDIVIDUAL', '_blank');">
				   </div>
				</div>				
				<div class="clearfix"></div>
				
				
	  
	 
			<?php endif; ?>	
     
   </div>
   <!-- #content -->
</section>
<!-- #primary -->
<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();

