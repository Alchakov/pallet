<?php

class PLT_Review {
	function __construct() {			
		add_action('init', [$this, 'init_post']);
		
		add_filter( 'manage_review_posts_columns', [$this, 'set_columns'] );
		add_action( 'manage_review_posts_custom_column' , [$this, 'set_column_content'], 10, 2 );
	}
	
	function init_post() {
		$labels = array(
			'name' => 'Отзывы',
			'singular_name' => 'Отзыв',
			'all_items' => 'Все отзывы',
			'add_new' => 'Добавить',
		);
		
		$args = array(
			'label' => 'Отзывы',
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
			'menu_icon' => 'dashicons-format-status',
			'has_archive' => false,
			'rewrite' => false	
		);
				
		register_post_type('review', $args);
	}	
	
	function set_columns($columns) {
	    $columns['type'] = 'Тип';
	    return $columns;
	}

	function set_column_content( $column, $post_id ) {
		if ($column == 'type') {
			$type = get_post_meta( $post_id , '_plt_review_type' , true );
			switch ($type) {
				case 'photo':
					echo "Фото";
					break;
				case 'video':
					echo "Видео";
					break;
				default:
					echo 'Текст';
					break;
			}
		}
	}
		
}

new PLT_Review();