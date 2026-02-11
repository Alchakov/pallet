<?php

class PLT_Worker {
	function __construct() {			
		add_action('init', [$this, 'init_post']);
	}
	
	function init_post() {
		$labels = array(
			'name' => 'Сотрудники',
			'singular_name' => 'Сотрудник',
			'all_items' => 'Все сотрудники',
			'add_new' => 'Добавить',
		);
		
		$args = array(
			'label' => 'Сотрудники',
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
			'menu_icon' => 'dashicons-groups',
			'has_archive' => false,
			'rewrite' => false	
		);
				
		register_post_type('worker', $args);
	}	
		
}

new PLT_Worker();