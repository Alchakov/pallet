<?php 
$active = false;
if (is_singular() && carbon_get_the_post_meta('plt_hero_active')) {
	$stiker 	   = carbon_get_the_post_meta('plt_hero_sticker');
	$sticker_color = carbon_get_the_post_meta('plt_hero_sticker_color');
	$title 		   = carbon_get_the_post_meta('plt_hero_title');
	$subtitle	   = carbon_get_the_post_meta('plt_hero_subtitle');
	$background    = carbon_get_the_post_meta('plt_hero_background');
	$image 		   = carbon_get_the_post_meta('plt_hero_image');
	$list 		   = carbon_get_the_post_meta('plt_hero_list');
	$active 	   = true;
} elseif (is_archive() && carbon_get_term_meta(get_queried_object()->term_id, 'plt_hero_active')) {
	$term_ID 	   = get_queried_object()->term_id;
	$stiker 	   = carbon_get_term_meta($term_ID, 'plt_hero_sticker');
	$sticker_color = carbon_get_term_meta($term_ID, 'plt_hero_sticker_color');
	$title 		   = carbon_get_term_meta($term_ID, 'plt_hero_title');
	$subtitle	   = carbon_get_term_meta($term_ID, 'plt_hero_subtitle');
	$background    = carbon_get_term_meta($term_ID, 'plt_hero_background');
	$image 		   = carbon_get_term_meta($term_ID, 'plt_hero_image');
	$list 		   = carbon_get_term_meta($term_ID, 'plt_hero_list');
	$active 	   = true;	
}
if ($active):	
	?>	
	<div class="b-hero" style="background-image: url('<?php echo wp_get_attachment_url($background);?>')">
		<div class="container">
			<div class="b-hero__inner">
				<div class="b-hero__info">
					<?php 
					if ($stiker) printf('<div class="b-hero__sticker" style="background:%s">%s</div>', $sticker_color, $stiker);
					if ($title) printf('<div class="b-hero__title">%s</div>', $title);
					if ($subtitle) printf('<div class="b-hero__subtitle">%s</div>', $subtitle);
					if (!empty($list)) {
						echo '<ul class="b-hero__list">';
						foreach ($list as $item)
							printf('<li><span class="b-hero__list-img" style="background-image: url(%s)"></span>%s</li>', wp_get_attachment_url($item['image']), $item['text'] );
						echo '</ul>';
					}					
					?>
				</div>
				<?php 
				if ($image) printf('<div class="b-hero__image">%s</div>', wp_get_attachment_image($image, 'medium'));	
				?>
				<div class="b-hero__form">
					<div class="b-hero__form-title">Оставить заявку</div>
					<?php get_template_part('parts/forms/form', 'hero');?>	
				</div>			
			</div>	
		</div>
	</div>
	<?php 
endif;?>