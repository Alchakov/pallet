<?php 
if ($methods = carbon_get_theme_option( 'plt_delivery' )) : 
	?>
	<div class="container">	
		<div class="b-delivery">
			<h2 class="b-delivery__title">Способы доставки</h2>		
			<ul class="b-delivery__methods flex-grid-1 flex-grid-md-3">
				<?php foreach ($methods as $method) : ?>
					<li>
						<div class="delivery-method">
							<div class="delivery-method__icon"><?php echo file_get_contents(wp_get_attachment_url($method['image']));?></div>
							<div class="delivery-method__title"><?php echo $method['title'];?></div>
							<div class="delivery-method__price"><?php echo $method['price'];?></div>
							<div class="delivery-method__caption"><?php echo $method['caption'];?></div>
							<div class="delivery-method__link"><a href="<?php echo $method['link'];?>" class="link-arrow">Узнать детали</a></div>
							<div class="delivery-method__shadow"><?php plt_site()->get_img('bg-shadow.png');?></div>
						</div>
					</li>
				<?php endforeach;?>	
			</ul>
		</div>
	</div>
	<?php	
endif;
