<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>

<div class="product-item">
	<div class="product-item__wrapper">
		<div class="product-item__inner">
			<div class="product-item__header">
				<a href="<?php the_permalink();?>">
					<div class="product-item__image">
						<div class="image-responsive image-responsive--5by2">
							<span><?php woocommerce_template_loop_product_thumbnail(); ?></span>
						</div>
						<?php plt_wc_product()->show_sticker();?>			
					</div>
					<div class="product-item__title">
						<?php the_title();?>
					</div>
				</a>
			</div>
			<div class="product-item__attributes d-none d-sm-block">
				<?php plt_wc_product()->product_attributes();?>
			</div>
			<div class="product-item__actions">
				<?php plt_wc_product()->product_stock();?>
				<?php woocommerce_template_single_price();?>
			</div>
		</div>
	</div>
	<?php
	if ( get_post_meta($product->get_id(), '_price_change_limit', true) )
		echo '<a href="javascript();" class="product-item__get-wholesale-price" data-toggle="modal" data-target="#modal-get-wholesale-price" data-product="' . esc_attr( $product->name ) . '">Получить оптовый прайс</a>';
	?>
</div>
