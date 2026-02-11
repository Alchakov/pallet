<div class="worker-preview article-preview article-preview--strict">
	<div class="worker-preview__inner article-preview__inner">
		<?php 
		if (has_post_thumbnail())
			the_post_thumbnail("thumb_450x300");
		else
			echo plt_site()->the_placeholder_img('thumb_450x300');
		?>
		<div class="article-preview__info">	
			<?php if ($position = carbon_get_the_post_meta('plt_worker_position')) : ?>
				<div class="article-preview__subtitle">			
					<?php echo $position;?>
				</div>
			<?php endif;?>		
			<div class="article-preview__title">
				<?php the_title();?>
			</div>
		</div>		
	</div>
	<a href="<?php echo get_the_post_thumbnail_url(null, 'full');?>" class="worker-preview__link fancybox"></a>
</div>