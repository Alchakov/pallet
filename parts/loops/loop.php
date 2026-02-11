<div class="article-preview">
	<a href="<?php the_permalink();?>" class="article-preview__inner">
		<?php 
		if (has_post_thumbnail())
			the_post_thumbnail("thumb_450x300");
		else
			echo plt_site()->the_placeholder_img('thumb_450x300');
		?>
		<div class="article-preview__info">			
			<div class="article-preview__subtitle">			
				<?php the_time('j.m.Y');?>
			</div>
			<div class="article-preview__title">
				<?php the_title();?>
			</div>
			<div class="article-preview__more">			
				Подробнее
			</div>
		</div>		
	</a>	
</div>