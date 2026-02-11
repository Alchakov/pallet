<?php
/**
 * Single Product Sticker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$stickers = wc_get_product_terms( $product->get_id(), 'pa_sticker', array( 'fields' => 'all' ) );
if (!empty($stickers)) : 
	echo '<ul class="product-stickers">';
	foreach ($stickers as $sticker) 
		echo sprintf('<li><div class="sticker" style="background:%s">%s</div></li>', carbon_get_term_meta($sticker->term_id, 'plt_sticker_color'),$sticker->name);	
	echo '</ul>';
endif;