<?php

class PLT_WC_Admin {
		
	function __construct() {
		add_action( 'woocommerce_product_options_pricing', [$this, 'add_price']); 
		add_action( 'woocommerce_process_product_meta', [$this, 'save_product_meta']); 
	}	
		
	function add_price() {  
		woocommerce_wp_text_input([
			'id' => '_retail_price',
			'label' => 'Розничная цена (₽)',
			'data_type' => 'price'
		]);
		woocommerce_wp_text_input([
			'id' => '_retail_price_sale',
			'label' => 'Розничная цена распродажи (₽)',
			'data_type' => 'price'
		]);
		
		woocommerce_wp_text_input([
			'id' => '_price_change_limit',
			'label' => 'Порог',
			'type' => 'number',
			'custom_attributes' => [
	            'step' => 'any',
	            'min' => '0'
	        ]
		]);
	}
	
	
	function save_product_meta($post_id) {
		$product = wc_get_product( $post_id );		
		$product->update_meta_data('_retail_price', $_POST['_retail_price'] ?? '');
		$product->update_meta_data('_retail_price_sale', $_POST['_retail_price_sale'] ?? '');
		$product->update_meta_data('_price_change_limit', $_POST['_price_change_limit'] ?? '');
		$product->save();
	}
}

new PLT_WC_Admin();