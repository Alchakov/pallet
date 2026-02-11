<?php 

if (!carbon_get_theme_option('plt_front_reviews_active')) ; 

$title = carbon_get_theme_option('plt_front_reviews_title');
$reviews = [
	'video' => [
		'title' => carbon_get_theme_option('plt_front_reviews_video_title'),
		'items' => carbon_get_theme_option('plt_front_reviews_video_items')
	],
	'photo' => [
		'title' => carbon_get_theme_option('plt_front_reviews_photo_title'),
		'items' => carbon_get_theme_option('plt_front_reviews_photo_items')
	],
	'text' => [
		'title' => carbon_get_theme_option('plt_front_reviews_text_title'),
		'items' => carbon_get_theme_option('plt_front_reviews_text_items')
	]
];
?>
<section class="front-reviews<?php echo (empty($reviews['video']['items'])) ? ' front-reviews--strict' : '';?>" data-aos="fade-in">		
	<?php
	if (!empty($reviews['video']['items'])) : 	
		?>
		<div class="container container--clear">
			<div class="front-reviews__row front-reviews__row--video">
				<div class="row">
					<div class="col-xl-10 center-block">
						<?php if ($title) echo "<h2>{$title}</h2>";?>
						<div class="title-slider-arrow">
							<h3><?php echo $reviews['video']['title'];?></h3>
							<div id="front-review-video__arrow" class="slider-arrow-outside"></div>
						</div>
						<div class="slider-3 slider-content slider-content--full" data-arrow-place="front-review-video__arrow">
							<?php 
							foreach ($reviews['video']['items'] as $item) :
								$title = get_the_title($item['id']);
								$caption = carbon_get_post_meta($item['id'], 'plt_review_video_caption');
								$title .= ($caption) ? "<span>{$caption}</span>" : '';
								$data = [
									'image' => carbon_get_post_meta($item['id'], 'plt_review_image'),
									'hover' => carbon_get_post_meta($item['id'], 'plt_review_video_image_hover'),
									'embed' => carbon_get_post_meta($item['id'], 'plt_review_video_embed'),
									'title' => $title
								];
								echo "<div>";
									plt_site()->tpl("parts/loops/loop-video", $data);
								echo "</div>";
							endforeach;			
							?>	
						</div>
					</div>	
				</div>
			</div>
		</div>	
		<?php
	endif;
	
	if ($title && empty($reviews['video']['items'])) :
		?>
		<div class="container container--clear">
			<div class="front-reviews__row front-reviews__row--h2">
				<div class="row">
					<div class="col-xl-10 center-block">
						<h2><?php echo $title;?></h2>
					</div>
				</div>
			</div>
		</div>	
		<?php 
	endif;
	
	if (!empty($reviews['photo']['items'])) : 
		?>
		<div class="container container--clear">
			<div class="front-reviews__row front-reviews__row--photo">
				<div class="row">
					<div class="col-xl-10 center-block">
						<div class="title-slider-arrow">
							<h3><?php echo $reviews['photo']['title'];?></h3>
							<div id="front-review-photo__arrow" class="slider-arrow-outside"></div>
						</div>
						<div class="slider-3 slider-content slider-content--full" data-arrow-place="front-review-photo__arrow">
							<?php 
							foreach ($reviews['photo']['items'] as $item) :
								$data = [
									'image' => carbon_get_post_meta($item['id'], 'plt_review_image'),
									'title' => get_the_title($item['id'])
								];
								echo "<div>";
									plt_site()->tpl("parts/loops/loop-review-photo", $data);
								echo "</div>";
							endforeach;			
							?>	
						</div>
					</div>
				</div>
			</div>
		</div>		
		<?php
	endif;
	
	if (!empty($reviews['text']['items'])) : 
		?>
		<div class="container container--clear">
			<div class="front-reviews__row front-reviews__row--text">
				<div class="row">
					<div class="col-xl-10 center-block">
						<div class="title-slider-arrow">
							<h3><?php echo $reviews['text']['title'];?></h3>
							<div id="front-review-text__arrow" class="slider-arrow-outside"></div>				
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-lg-12 center-block">
						<div class="slider-2 slider-content slider-content--full" data-arrow-place="front-review-text__arrow">
							<?php 
							foreach ($reviews['text']['items'] as $item) :
								$data = [
									'image' => carbon_get_post_meta($item['id'], 'plt_review_image'),
									'text' => carbon_get_post_meta($item['id'], 'plt_review_text_content'),						
									'star' => carbon_get_post_meta($item['id'], 'plt_review_text_star'),
									'title' => get_the_title($item['id'])
								];
								echo "<div>";
									plt_site()->tpl("parts/loops/loop-review-text", $data);
								echo "</div>";
							endforeach;			
							?>	
						</div>
					</div>
				</div>
			</div>
		</div>			
		<?php
	endif;					
	?>	
		
	<div class="container">		
		<div class="front-btn">
			<a href="/otzyvy/" class="btn btn--transparent btn--no-shadow"><span class="btn__inner"><span class="btn__text link-arrow">Все отзывы</span></span></a>
		</div>	
	</div>	
</section>