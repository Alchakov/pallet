<?php  
	get_header(); 
	the_post();
	do_action('aside_page_start');				
?>

<article class="node">
	<header class="node__header">								
		<?php the_title("<h1>", "</h1>");?>  		
	</header>
	<section class="content clearfix">
		<?php the_content();?>   
	</section>
</article>

<?php
	do_action('aside_page_end');
	get_footer();
?>