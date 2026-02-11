<?php

class PLT_Client {
	function __construct() {			
		add_action('init', [$this, 'init_post']);
	}
	
	function init_post() {
		$labels = array(
			'name' => 'Клиенты',
			'singular_name' => 'Клиент',
			'all_items' => 'Все клиенты',
			'add_new' => 'Добавить',
		);
		
		$args = array(
			'label' => 'Клиенты',
			'labels' => $labels,
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			),
			'public' => true,
	        'exclude_from_search' => false,
			'show_in_rest' => true,
	        'menu_position' => 5,
	        'menu_icon' => 'dashicons-businessman',		
	        'has_archive' => true,
			'rewrite' => array(
	            'slug' => 'klienty',
				'with_front' => false
	        ) 		
		);
				
		register_post_type('client', $args);
	}	
		
}

new PLT_Client();