<div class="box-benefits">	
	<?php if ($title) echo sprintf('<div class="box-benefits__title">%s</div>', $title);?>
	<div class="box-benefits__items">
		<?php 
		foreach ($list as $item): 
			?>
			<div class="box-benefits__item">
				
				<?php if ($item['title']) echo sprintf('<div class="box-benefits__item-title">%s</div>', $item['title']);?>
				
				<?php if ($item['content']) : ?>					
					<div class="box-benefits__item-content">
						<?php echo apply_filters('the_content', $item['content']);?>
												
						<?php if ($item['image']) : ?>
							<div class="box-benefits__item-image">
								<div class="image-responsive image-responsive--1by1">
									<span><?php echo wp_get_attachment_image($item['image'], 'full');?></span>
								</div>	
							</div>						
						<?php endif;?>	
											
					</div>
				<?php endif;?>
				
			</div>
			<?php
		endforeach; 
		?>
	</div>
</div>