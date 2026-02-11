<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$availability = $product->get_availability();
if ($availability['availability'] == 'Нет в наличии') return;

$id = $product->get_id();
$limit = get_post_meta($id, '_price_change_limit', true);

$price = $product->get_price_html();

if ( $limit ) {
	$retail_price = get_post_meta($id, '_retail_price', true);
	$retail_sale = get_post_meta($id, '_retail_price_sale', true);
	$retail_display = 0;

	if ( $retail_price ) {
		$retail_display = ($retail_sale) ? $retail_sale : $retail_price;
		if ($retail_sale)
			$retail_html = wc_format_sale_price(wc_get_price_to_display($product, ['price' => $retail_price]), wc_get_price_to_display($product, ['price' => $retail_sale]));
		else
			$retail_html = wc_price(wc_get_price_to_display($product, ['price' => $retail_price]));
	} else {
		$retail_html = $price;
	}
} else {
	$retail_html = $price;
}
?>

<?php
if ( $limit ) {
	echo sprintf(
		'<div class="product-price add-bottom-20">
			<span class="product-price__label">Оптовая цена (от %s шт.):</span> <span class="product-price__amount">%s</span>
		</div>',
		$limit,
		$price
	);
}
?>

<div class="product-actions">
	<?php if ($retail_html) :
		?>
		<div class="product-price">
			<span class="product-price__label">Цена:</span> <span class="product-price__amount"><?php echo $retail_html; ?></span>
		</div>
		<?php
	endif;?>
	<div class="product-buy">
		<a href="#" class="btn buy-action" data-product="<?php echo $product->get_id();?>"><span class="btn__inner"><span class="btn__text">Купить</span></span></a>
	</div>
</div>