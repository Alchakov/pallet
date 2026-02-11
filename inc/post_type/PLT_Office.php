<?php

class PLT_Office {
	function __construct() {			
		add_action('init', [$this, 'init_post']);
	}
	
	function init_post() {
		$labels = array(
			'name' => 'Офисы',
			'singular_name' => 'Офис',
			'all_items' => 'Все офисы',
			'add_new' => 'Добавить',
		);
		
		$args = array(
			'label' => 'Офисы',
			'labels' => $labels,
			'supports' => array(
				'title',			
            	'thumbnail'
			),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => false,
			'menu_position' => 9,		
			'menu_icon' => 'dashicons-sticky',
			'has_archive' => false,
			'rewrite' => false	
		);
				
		register_post_type('office', $args);
	}	
		
}

new PLT_Office();