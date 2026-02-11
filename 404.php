<?php  
	get_header(); 
	the_post();
	do_action('full_page_start');				
?>

<div class="node">
	<div class="text-center">	
		<h1>Ошибка 404. Страница не найдена</h1>
		<div class="h2">
			<a href="/">На главную</a>
		</div>
	</div>
</div>

<?php
	do_action('full_page_end');
	get_footer();
?>