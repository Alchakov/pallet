<?php
/**
 * Single Product Delivery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ($methods = carbon_get_theme_option( 'plt_delivery' )) : 
	?>
	<div class="single-product__delivery">
		<div class="single-product__delivery-title">Способы доставки</div>		
		<div class="single-product__delivery-methods">
			<?php foreach ($methods as $method) : ?>
				<div class="single-product__delivery-method">
					<div class="single-product__delivery-method__icon"><?php echo file_get_contents(wp_get_attachment_url($method['image']));?></div>
					<div class="single-product__delivery-method__title"><?php echo $method['title'];?></div>
					<div class="single-product__delivery-method__price"><?php echo $method['price'];?></div>
					<div class="single-product__delivery-method__caption"><?php echo $method['caption'];?></div>
				</div>
			<?php endforeach;?>		
		</div>
	</div>
	<?php	
endif;
