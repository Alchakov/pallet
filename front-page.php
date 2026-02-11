<?php
get_header();
the_post();
do_action('main_start');

$parts = [
	'slider',
	'advantages',
	'catalog',
	'offer',
	'delivery',
	'steps',
	'reviews',
	'form',
	'client',
	'video',
	'content',
	'map',
	'contact'
];
foreach ($parts as $part)
	get_template_part('parts/front/'.$part);
			
do_action('main_end');

get_footer();
?>