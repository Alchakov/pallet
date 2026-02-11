<?php 
if (!carbon_get_theme_option('plt_front_videos_active')) return; 

$title = carbon_get_theme_option('plt_front_videos_title');
$text = carbon_get_theme_option('plt_front_videos_text');
$subtitle = carbon_get_theme_option('plt_front_videos_subtitle');
$items = carbon_get_theme_option('plt_front_videos_items');

if (empty($items)) return;
?>

<section class="front-videos" data-aos="fade-in">
	<div class="container">		
		<?php 
		if ($title) echo "<h2>{$title}</h2>";		
		if ($text) printf('<div class="front-videos__text content">%s</div>', apply_filters('the_content', $text));		
		if ($subtitle) printf('<div class="front-videos__subtitle">%s</div>', $subtitle);
		$shortcode = sprintf('[pallet_videos ids="%s"]', implode(',', wp_list_pluck( $items, 'id' )));
		echo do_shortcode($shortcode);
		?>
		<div class="front-btn">
			<a href="<?php the_permalink(298);?>" class="btn btn--transparent btn--no-shadow"><span class="btn__inner"><span class="btn__text link-arrow">Все видео</span></span></a>
		</div>
	</div>
</section>
