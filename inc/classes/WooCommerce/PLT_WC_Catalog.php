<?php

class PLT_WC_Catalog {
	
	private static $instance = null;
	
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	function __construct() {
		//woocommerce_before_shop_loop
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		
		//woocommerce_after_shop_loop
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
		add_action( 'woocommerce_after_shop_loop', [$this, 'pagination'], 10 );
		
		//pallet_product_cat_node_bottom
		add_action( 'pallet_product_cat_node_bottom', [$this, 'advantages'], 10 );
		add_action( 'pallet_product_cat_node_bottom', [$this, 'content'], 20 );
					
		$this->filters();	 
	}	
	
	function filters() {		
		add_filter( 'loop_shop_per_page', function() { return 21; }, 3 );
				
		foreach ( array( 'woocommerce_default_catalog_orderby_options', 'woocommerce_catalog_orderby' ) as $filter ) {
			add_filter( $filter, [$this, 'orderby_options'] );
		}
	}
	
	function pagination() {
		echo PLT_Content::pagination();
	}
		
	function sub_cat( $term_ID = '' ) {
		if (!$term_ID)
			$term_ID = get_queried_object()->term_id;

		$subcats = get_categories(array(
			'parent' => $term_ID,
			'taxonomy' => 'product_cat'
		));
		if ($subcats) : 
			$row_1 = [];
			$row_2 = [];			
			if (count($subcats) > 4) {
				$row_1 = array_slice($subcats, 0, 3);
				$row_2 = array_slice($subcats, 3);
			} else {
				$row_1 = $subcats;
			}			
			wc_get_template( 'loop/sub-category.php', ['row_1' => $row_1, 'row_2' => $row_2] );
		endif;
	}
	
	function orderby_options( $sortby ) {
		unset($sortby['rating']);
		unset($sortby['date']);
		unset($sortby['popularity']);
		$sortby['menu_order'] = 'По популярности';	
		$sortby['price-desc'] = 'Сначала дорогие';
		$sortby['price'] = 'Сначала дешевые';
		return $sortby;
	}
	

	function advantages( $term_ID = '' ) {
		
		if (!$term_ID)
			$term_ID = get_queried_object()->term_id;

		if (carbon_get_term_meta($term_ID, 'plt_product_cat_advantages_active') != 'yes') 
			return;
			
		$advantages = carbon_get_term_meta($term_ID, 'plt_product_cat_advantages');
		if (empty($advantages)) 
			$advantages = carbon_get_theme_option( 'plt_advantages' );
			
		plt_site()->tpl( 'parts/advantages', ['advantages' => $advantages]);
	}
	
	function content() {		
		if (!(is_product_taxonomy() && 0 === absint(get_query_var('paged')))) return;
		wc_get_template( 'loop/content.php' );
	}

}

function plt_wc_catalog() {
	return PLT_WC_Catalog::instance();
}
plt_wc_catalog();