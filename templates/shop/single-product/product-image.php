<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$post_thumbnail_id = $product->get_image_id();
?>

<div class="single-product__gallery">
	<div class="single-product__gallery-wrapper">
	<?php 
	if (!$attachment_ids):
		if ( $post_thumbnail_id ) {		
			$html = plt_wc_product()->get_gallery_image_link_html( $post_thumbnail_id );
		} else {		
			$html = sprintf( '<img src="%s" alt="%s"/>', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
		}		
		echo '<figure>'.apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ).'</figure>'; 
	else :
		 ?>
		 <div class="single-product__gallery-main-track">
		 	<?php foreach ( $attachment_ids as $attachment_id ) {			
		 			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', plt_wc_product()->get_gallery_image_link_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		 	}?>
		 </div>

		 <div class="single-product__gallery-thumb-track slider-content">
		 	<?php foreach ( $attachment_ids as $attachment_id ) {			
		 			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', plt_wc_product()->get_gallery_image_html( $attachment_id, 'thumbnail' ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		 	}?>
		 </div>
		<?php 
	endif;
	?>	
	</div>
</div>