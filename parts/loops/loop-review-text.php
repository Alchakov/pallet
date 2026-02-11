<div class="review-text">	
	<div class="review-text__image">
		<div class="image-responsive image-responsive--1by1">
			<span>
				<?php 
				if ($image)
					echo wp_get_attachment_image( $image, 'medium');
				else
					plt_site()->get_img('person.png');
				?>
			</span>
		</div>
	</div>
	<div class="review-text__text">
		<?php echo apply_filters('the_content', $text);?> 
	</div>
	<div class="review-text__title">
		<?php echo $title;?> 
	</div>
	<div class="review-text__rating">
		<?php 
		for ($i = 0; $i < 5; $i++)
			echo sprintf('<span class="c-star%s"></span>', ($i > $star) ? ' c-star--empty' : '');
		?>
	</div>
</div>