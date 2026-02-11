<div class="client-preview">
	<a href="<?php the_permalink();?>">
		<div class="client-preview__image">
			<div class="image-responsive image-responsive--16by9">
				<span>
					<?php 
					if (has_post_thumbnail())
						the_post_thumbnail("medium");
					else
						echo plt_site()->the_placeholder_img('medium');
					?>
				</span>
			</div>			
		</div>	
		<div class="client-preview__title">
			<?php the_title();?>
		</div>	
	</a>	
</div>