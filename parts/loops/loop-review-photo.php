<div class="review-photo">	
	<div class="review-photo__image">
		<?php 
		echo sprintf(
			'<a href="%s" class="fancybox zoom-icon" rel="review-photo-image">%s</a>',
			 wp_get_attachment_url($image), 
			 wp_get_attachment_image( $image, 'thumb_300x420')
		 );
		?>
		<div class="review-photo__shadow">
			<?php plt_site()->get_img('bg-shadow2.png');?>
		</div>
	</div>
	<div class="review-photo__title">
		<?php echo $title;?> 
	</div>
</div>