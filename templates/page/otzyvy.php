<?php 
get_header(); 
the_post();
do_action('full_page_start');	
?>
<article class="node">
	<header class="node__header">								
		<?php the_title("<h1>", "</h1>");?>  		
	</header>
	<section class="content clearfix">		
		<?php the_content();?>
	</section>
	<?php
	if ($reviews = get_posts( array('posts_per_page' => -1,'post_type' => 'review'))) :
		
		$photo = [];	
		$video = [];
		$text = [];
		
		foreach ($reviews as $review) {
			$type = carbon_get_post_meta($review->ID, 'plt_review_type');
			array_push($$type, $review);
		}
		
		$reviews_data = [
			'video' => [
				'title' => 'Видеоотзывы',
				'slug' => 'b-reviews-video',
				'items' => $video 
			],
			'photo' => [
				'title' => 'Благодарственные письма',
				'slug' => 'b-reviews-photo',
				'items' => $photo 
			],
			'text' => [
				'title' => 'Отзывы покупателей',
				'slug' => 'b-reviews-text',
				'items' => $text 
			] 
		];
					
		$reviews_data = (array_filter($reviews_data, function ($value) {
		    if (!empty($value['items']) && !is_null($value['items'])) {
		        return $value;
		    }
		}));
		?>
		<div class="b-reviews">
			<div class="b-reviews__nav">
				<ul class="nav nav-tabs">
					<?php 
					$first = true;
					foreach ($reviews_data as $value) :
						echo sprintf('<li><a class="%s" data-toggle="tab" href="#%s">%s</a></li>',
							($first) ? 'active' : '',
							$value['slug'],
							$value['title']
						);
						$first = false;
					endforeach;	
					?>
				</ul>
			</div>
			<div class="b-reviews__content">
				<div class="tab-content">
					<?php 
					$first = true;
					foreach ($reviews_data as $key => $value) :
						echo sprintf('<div class="tab-pane fade %s" id="%s">',
							($first) ? 'show active' : '',
							$value['slug']
						);
						
						switch ($key) {
							
							case 'video':
								?>
								<ul class="reviews-list reviews-list--videos flex-grid-2 flex-grid-lg-3">
									<?php 
									foreach( $video as $item ) { 
										$title = get_the_title($item->ID);
										$caption = carbon_get_post_meta($item->ID, 'plt_review_video_caption');
										$title .= ($caption) ? "<span>{$caption}</span>" : '';
										$data = [
											'image' => carbon_get_post_meta($item->ID, 'plt_review_image'),
											'hover' => carbon_get_post_meta($item->ID, 'plt_review_video_image_hover'),
											'embed' => carbon_get_post_meta($item->ID, 'plt_review_video_embed'),
											'title' => $title
										];
										echo "<li>";
											plt_site()->tpl("parts/loops/loop-video", $data);
										echo "</li>";
									}
									?>	
								</ul>	
								<?php
								break;
								
							case 'photo':
								?>
								<ul class="reviews-list reviews-list--photo flex-grid-2 flex-grid-sm-3 flex-grid-md-4">	
									<?php 
									foreach ($photo as $item) {
										$data = [
											'image' => carbon_get_post_meta($item->ID, 'plt_review_image'),
											'title' => get_the_title($item->ID)
										];
										echo "<li>";
											plt_site()->tpl("parts/loops/loop-review-photo", $data);
										echo "</li>";
									}?>
								</ul>	
								<?php
								break;
								
							default:
								?>
								<ul class="reviews-list reviews-list--text flex-grid-1 flex-grid-md-2">	
									<?php 
									foreach ($text as $item) {
										$data = [
											'image' => carbon_get_post_meta($item->ID, 'plt_review_image'),
											'text' => carbon_get_post_meta($item->ID, 'plt_review_text_content'),						
											'star' => carbon_get_post_meta($item->ID, 'plt_review_text_star'),
											'title' => get_the_title($item->ID)
										];
										echo "<li>";
											plt_site()->tpl("parts/loops/loop-review-text", $data);
										echo "</li>";
									}?>
								</ul>	
								<?php 
								break;
						}						
						echo "</div>";
						$first = false;
					endforeach;	
					?>
				</div>
			</div>
		</div>
		<?php			
	endif;				
	?>   	
</article>

<?php
	do_action('full_page_end');
	get_footer();
?>