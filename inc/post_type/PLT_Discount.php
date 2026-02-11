<?php

class PLT_Discount {
	function __construct() {			
		add_action('init', [$this, 'init_post']);		
		add_filter ('manage_discount_posts_columns', [$this, 'manage_columns']);	
		add_action ('manage_discount_posts_custom_column',  [$this, 'manage_custom_column'], 10, 2);
	}
	
	function init_post() {
		$labels = array(
			'name' => 'Акции',
			'singular_name' => 'Акция',
			'all_items' => 'Все акции',
			'add_new' => 'Добавить',
		);
		
		$args = array(
			'label' => 'Акции',
			'labels' => $labels,
			'supports' => array(
				'title',
				'editor'
			),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_rest' => false,
			'show_in_nav_menus' => true,
			'query_var' => false,
			'menu_position' => 9,		
			'menu_icon' => 'dashicons-megaphone',
			'has_archive' => false,
			'rewrite' => false	
		);
				
		register_post_type('discount', $args);
	}
	
	function manage_columns( $columns ) {
	    return array_merge ( $columns, array (
	        'start_date' => 'Дата начала',
	        'end_date'   => 'Дата завершения'
	    ));
	}
	
	function manage_custom_column( $column, $post_id ) {
	    switch ( $column ) {
	        case 'start_date':
	            echo mysql2date("j.m.Y", carbon_get_post_meta($post_id, 'plt_discount_start'));
	            break;
	        case 'end_date':
	            echo mysql2date("j.m.Y", carbon_get_post_meta($post_id, 'plt_discount_end'));
	            break;
	    }
	}
		
}

new PLT_Discount();