<?php

class PLT_Video {
	function __construct() {			
		add_action('init', [$this, 'init_post']);
	}
	
	function init_post() {
		$labels = array(
			'name' => 'Видео',
			'singular_name' => 'Видео',
			'all_items' => 'Все видео',
			'add_new' => 'Добавить',
		);
		
		$args = array(
			'label' => 'Видео',
			'labels' => $labels,
			'supports' => array(
				'title',
			),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => false,
			'menu_position' => 9,		
			'menu_icon' => 'dashicons-format-video',
			'has_archive' => false,
			'rewrite' => false	
		);
				
		register_post_type('video', $args);
	}	
		
}

new PLT_Video();