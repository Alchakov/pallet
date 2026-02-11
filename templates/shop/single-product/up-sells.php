<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;

if (!$upsells) 
	$upsells = plt_wc_product()->get_siblings( $product->get_id() );

if ( $upsells ) : ?>

	<section class="single-product__upsells">	
			
		<div class="single-product__upsells-title title-slider-arrow">
			<h2>Смотрите также</h2>
			<div class="single-product__upsells-arrow slider-arrow-outside"></div>
		</div>
		
		<div class="single-product__upsells-slider slider-content slider-content--full slider-content--equal-height">
			<?php 
			foreach ( $upsells as $upsell ) :
				$post_object = get_post( $upsell->get_id() );					
				setup_postdata( $GLOBALS['post'] =& $post_object );
				wc_get_template_part( 'content', 'product' );
			endforeach; 
			?>
		</div>		

	</section>

<?php endif;

wp_reset_postdata();

