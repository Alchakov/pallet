<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div class="single-product">
	
	<div class="single-product__info">
		<div class="row">
			<div class="col-md-5 col-lg-6">
				<div class="single-product__media">
					<?php
					/**
					 * Hook: woocommerce_before_single_product_summary.
					 *
					 * @hooked PLT_WC_Product()->show_sticker() - 10 - ADDED
					 * @hooked woocommerce_show_product_sale_flash - 10 - REMOVED
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
					?>
				</div>
			</div>
						
			<div class="col-md-7 col-lg-6 col-xl-4">
				<div class="single-product__summary">
					<?php
					/**
					 * Hook: woocommerce_single_product_summary.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked PLT_WC_Product()->product_attributes() - 6 - ADDED
					 * @hooked PLT_WC_Product()->product_stock() - 7 - ADDED
					 * @hooked woocommerce_template_single_rating - 10 - REMOVED
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20 - REMOVED
					 * @hooked woocommerce_template_single_add_to_cart - 30 - REMOVED
					 * @hooked woocommerce_template_single_meta - 40 - REMOVED
					 * @hooked woocommerce_template_single_sharing - 50 - REMOVED
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>
			
			<div class="col-xl-2">
				<?php plt_wc_product()->product_delivery();?>			
			</div>
		</div>
	</div>
	
	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked PLT_WC_Product()->product_payment() - 5 - ADDED
	 * @hooked PLT_WC_Product()->product_content() - 10 - ADDED
	 * @hooked woocommerce_output_product_data_tabs - 10 - REMOVED
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20 - REMOVED
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php 
/**
 * Hook: woocommerce_after_single_product.
 *
 */
 do_action( 'woocommerce_after_single_product' ); ?>
