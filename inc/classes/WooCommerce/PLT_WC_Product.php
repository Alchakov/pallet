<?php

class PLT_WC_Product {
	
	private static $instance = null;
	
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	function __construct() {
		//woocommerce_before_single_product_summary
		remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
		add_action('woocommerce_before_single_product_summary', [$this, 'show_sticker'], 10);
		add_action('woocommerce_single_product_summary', [$this, 'product_attributes'], 6);		
		add_action( 'woocommerce_single_product_summary', [$this, 'product_stock'], 7 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );	
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );	
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );	
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );	
		
		//woocommerce_after_single_product_summary		
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );	
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		add_action( 'woocommerce_after_single_product_summary', [$this, 'product_payment'], 5 );
		add_action( 'woocommerce_after_single_product_summary', [$this, 'product_content'], 10 );
						
		$this->filters();	
		
		if (wp_doing_ajax()) {
		    add_action('wp_ajax_buy_action', [$this, 'buy_action_handler']);
		    add_action('wp_ajax_nopriv_buy_action', [$this, 'buy_action_handler']);
		}    

	}		
	
	function filters() {
		add_filter( 'woocommerce_attribute', function ( $formatted_values, $attribute, $values ) { 
			return wptexturize( implode( ', ', $values ) );
		}, 10, 3 );
		
		add_filter( 'woocommerce_get_availability', function ( $availability, $_product ) {
			if ( $availability['availability'] == '') $availability['availability'] = 'В наличии';
			return $availability;
		}, 10, 2);
	}
	
	function get_gallery_image_link_html( $attachment_id, $size = 'woocommerce_single' ) {
		return sprintf('<div class="single-product__gallery-image"><div class="image-responsive image-responsive--6by4"><a href="%s" class="fancybox" rel="product-galley">%s</a></div></div>', wp_get_attachment_url($attachment_id), wp_get_attachment_image( $attachment_id, $size));
	}

	function get_gallery_image_html( $attachment_id, $size = 'woocommerce_single' ) {
		return sprintf('<div class="single-product__gallery-image">%s</div>', wp_get_attachment_image( $attachment_id, $size));
	}
	
	function product_attributes() {
	    global $product;
	    if ($product && $product->has_attributes()) {
	        wc_display_product_attributes( $product );
	    }
	}
	
	function product_stock() {		
		global $product;
		echo wc_get_stock_html( $product );
	}
	
	function product_delivery() {		
		wc_get_template( 'single-product/delivery.php' );
	}
	
	function product_payment() {		
		wc_get_template( 'single-product/payment.php' );
	}
	
	function product_content() {		
		wc_get_template( 'single-product/content.php' );
	}
		
	function show_sticker() {
		wc_get_template( 'single-product/sticker.php' );
	}

	function buy_action_handler() {
		if ($_POST) {
			$id = (int)$_POST['product'];
			$this->buy_action($id);
		}
		die();
	}
			
	function buy_action( $id ) {

		$product = wc_get_product($id);
		if (!$product)
			wp_send_json_error('Произошла ошибка!');

		if ($product->get_availability() == "Нет в наличии")
			wp_send_json_error('Товара нет в наличии!');

		if ($product->get_price() == '')
			wp_send_json_error('Товара не продается!');

		$retail_price = get_post_meta($id, '_retail_price', true);
		$retail_sale = get_post_meta($id, '_retail_price_sale', true);
		$retail_display = 0;

		if ($retail_price) {
			$retail_display = ($retail_sale) ? $retail_sale : $retail_price;
			if ($retail_sale)
				$retail_html = wc_format_sale_price(wc_get_price_to_display($product, ['price' => $retail_price]), wc_get_price_to_display($product, ['price' => $retail_sale]));
			else
				$retail_html = wc_price(wc_get_price_to_display($product, ['price' => $retail_price]));
		}

		$data = [
			'name' 		   => get_the_title($product->get_id()),
			'image'    	   => get_the_post_thumbnail_url($product->get_id(), 'medium'),
			'price' 	   => $product->get_price(),
			'price_html'   => $product->get_price_html(),
			'retail_price' => $retail_display,
			'retail_html'  => $retail_html,
			'limit' 	   => get_post_meta($id, '_price_change_limit', true)
		];

		$card = plt_site()->tpl('parts/buy-card', $data, false);
		wp_send_json_success(['card' => $card]);

	}
	
	function get_siblings( $current = '' ) {
		if (!$current)
			$current = get_the_ID();

		$amount = 15;
		$key = 0;
		$main_key = 0;
		$upsells = [];
		
		$siblings = get_posts( array(
			'numberposts' => -1,
			'post_type'   => 'product',
			'orderby' => 'menu_order',
			'tax_query' => array(
			    array(
			      'taxonomy' => 'product_cat',
			      'field' => 'term_id',
			      'terms' => wp_get_object_terms($current, 'product_cat', array('fields' => 'ids'))
			    )
			 ),
			 'suppress_filters' => false
		));
			
		$count = count($siblings);
		$amount = ($count > $amount) ? $amount : $count;
		foreach ($siblings as $k => $v) {
			if ($v->ID == $current) {
				$key = $k;
				break;
			}
		}	
			
		$main_key = $key;
		
		while ($amount > 0) {
			$key++;
			if ($key == $count) 
				$key = 0;
			if ($main_key != $key) 
				$upsells[] = $siblings[$key];
							
			$amount--;
		}	
		
		return array_map( 'wc_get_product', $upsells);
	}
}

function plt_wc_product() {
	return PLT_WC_Product::instance();
}
plt_wc_product();