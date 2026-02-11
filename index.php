<?php
	get_header();
	do_action('aside_page_start');
	$part = $post->post_type;
?> 
  
<div class="node">	
	<div class="node__header">
		<h1><?php echo PLT_Content::archive_title();?></h1>   
	</div> 			
	<?php if (have_posts()) : ?>            
		<?php get_template_part("parts/listing/start", $part);?>
			<?php 
				while (have_posts()) : the_post();
					echo "<li>";
						get_template_part("parts/loops/loop", $part);
					echo "</li>";
				endwhile;
			?>            
		<?php get_template_part("parts/listing/end", $part);?>
		<?php echo PLT_Content::pagination(); 
	else: 
		echo "<p>Ничего нет</p>";
	endif;?>			      
</div>	    

<?php 
	do_action('aside_page_end');
	get_footer();
?>