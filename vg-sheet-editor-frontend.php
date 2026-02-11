<?php 
/* 
Template Name: Full Page 
Template Post Type: page
*/
get_header(); 
the_post();
do_action('full_page_start');				
?>

<article class="node">
	<header class="node__header">								
		<?php the_title("<h1>", "</h1>");?>  		
	</header>
	<section class="content clearfix">
		<?php 
		// Выводим контент страницы
		the_content();
		
		// ДОПОЛНИТЕЛЬНО: Если хотите гарантированно вывести таблицу,
		// даже если в контенте нет шорткода:
		/*
		if(empty(get_the_content())) {
			echo do_shortcode('[vg_sheet_editor editor_id="12121"]');
		}
		*/
		?>   
	</section>
</article>

<?php
do_action('full_page_end');
get_footer();