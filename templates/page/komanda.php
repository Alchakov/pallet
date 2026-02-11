<?php 
get_header(); 
the_post();
do_action('full_page_start');	
?>
<article class="node">
	<header class="node__header">								
		<?php the_title("<h1>", "</h1>");?>  		
	</header>
	<?php 
	if ($post->post_content) : 
		?>
		<div class="content add-bottom-50">
			<?php the_content();?>
		</div>	
		<?php 
	endif;
	
	if ($staff = get_posts( array('posts_per_page' => -1,'post_type' => 'worker'))) : ?>
		<ul class="staff-list flex-grid-2 flex-grid-sm-3">	
			<?php foreach ($staff as $worker) {
				echo "<li>";
					setup_postdata( $GLOBALS['post'] = & $worker );
					get_template_part('parts/loops/loop', 'worker');
				echo "</li>";
			}?>
		</ul>		
		<?php 
		wp_reset_postdata();
	endif;	
	?>
</article>

<?php
	do_action('full_page_end');
	get_footer();
?>