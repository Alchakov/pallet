<?php 
if (!$link) return;

$url = parse_url($link);
parse_str($url['query'], $query);

$youtube_img = 'https://img.youtube.com/vi/'.$query['v'].'/0.jpg';
$original = sprintf('<img src="%s" class="box-video__original" alt=""/>', ($image) ? wp_get_attachment_url($image, 'large') : $youtube_img);
$hover = sprintf('<img src="%s" class="box-video__hover" alt=""/>', ($hover) ? wp_get_attachment_url($hover, 'large') : $youtube_img);

echo sprintf(
	'<a href="https://www.youtube.com/embed/%s?autoplay=1" class="fancybox box-video" data-fancybox-type="iframe">%s%s</a>',
	$query['v'],
	$hover,
	$original
);