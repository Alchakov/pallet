<?php 
if (!carbon_get_theme_option('plt_front_offer_active')) return; 

$type = carbon_get_theme_option('plt_front_offer_type');
$title = carbon_get_theme_option('plt_front_offer_title');
$image = carbon_get_theme_option('plt_front_offer_image');
$list = carbon_get_theme_option('plt_front_offer_list');
?>

<div class="front-offer" data-aos="fade-in">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<?php
				if ($type) printf('<div class="front-offer__type">%s</div>', $type);
				if ($type) printf('<div class="front-offer__title">%s</div>', $title);
				
				if ($image || $list) :
					?>
					<div class="row add-top-40">
						<div class="col-md-7">
							<?php if ($image) printf('<div class="front-offer__image">%s</div>', wp_get_attachment_image($image, 'medium'));?>
						</div>
						<div class="col-md-5">
							<?php 
							if (!empty($list)) {
								echo '<ul class="front-offer__list">';
								foreach ($list as $item)
									printf('<li><span class="front-offer__list-img" style="background-image: url(%s)"></span>%s</li>', wp_get_attachment_url($item['image']), $item['text'] );
								echo '</ul>';
							}	
							?>
						</div>
					</div>
					<?php 
				endif;
				?>
			</div>
			<div class="col-md-4">
				<div class="front-offer__form">
					<div class="front-offer__form-title">Оставить заявку</div>
					<?php get_template_part('parts/forms/form', 'front-offer');?>	
				</div>					
			</div>
		</div>
	</div>
</div>
