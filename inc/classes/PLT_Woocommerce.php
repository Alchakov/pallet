<?php

class PLT_Woocommerce {
		
	function __construct() {	
		add_action( 'after_setup_theme', function () { 
			add_theme_support( 'woocommerce' ); 
		});
		
		add_filter( 'woocommerce_template_path', [ $this, 'change_template_shop_in_theme' ] );
		add_filter('woocommerce_currency_symbol', [ $this, 'currency_symbol' ], 10, 2);	
		
		add_action( 'template_redirect', [ $this, 'remove_woocommerce_styles_scripts' ], 999 );
				
		add_filter('woocommerce_format_dimensions', [$this, 'localize_demensions_units']);
		add_filter('woocommerce_format_weight', [$this, 'localize_weight_units']);
		
		$this->layout();
		
		// Продукт
		require_once __DIR__ . '/WooCommerce/PLT_WC_Product.php';		
		
		// Каталог
		require_once __DIR__ . '/WooCommerce/PLT_WC_Catalog.php';
		
		//Админ
		require_once __DIR__ . '/WooCommerce/PLT_WC_Admin.php';	
		
	}	
	
	function change_template_shop_in_theme() {
		return 'templates/shop/';
	}
	
	function layout() {		
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 ); 
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 ); 
		add_action( 'woocommerce_before_main_content', function() {
			do_action('full_page_start');
		}, 10);
		add_action( 'woocommerce_before_main_content', function() {
			echo '<div class="woocommerce">';
		}, 11);
		
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 ); 
		add_action( 'woocommerce_after_main_content', function() {
			echo '</div>';
		}, 9);
		add_action( 'woocommerce_after_main_content', function() {
			do_action('full_page_end');
		}, 10);
		
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 ); 
	}
	
	function currency_symbol( $currency_symbol, $currency ) {
		if (!is_admin() || wp_doing_ajax()) {
			switch( $currency ) {
				 case 'RUB': $currency_symbol = '<span class="rub">руб.</span>'; break;
			}
		}    
		return $currency_symbol;
	}
	
	function remove_woocommerce_styles_scripts() {	    
		remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
		remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
		remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);	    
	}
	
	function localize_demensions_units($dimensions) {
		return str_replace('mm', 'мм', $dimensions);
	}
	
	function localize_weight_units($weight) {
	    return str_replace('kg', 'кг', $weight);
	}
}