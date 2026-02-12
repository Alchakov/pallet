<?php

class PLT_Brand {
	function __construct() {			
		add_action('init', [$this, 'init_post']);
		add_action('init', [$this, 'init_taxonomies']);
	}
	
	function init_post() {
		$labels = array(
			'name' => 'Производители',
			'singular_name' => 'Производитель',
			'all_items' => 'Все производители',
			'add_new' => 'Добавить производителя',
			'add_new_item' => 'Добавить нового производителя',
			'edit_item' => 'Редактировать производителя',
			'new_item' => 'Новый производитель',
			'view_item' => 'Просмотр производителя',
			'search_items' => 'Поиск производителей',
			'not_found' => 'Производители не найдены',
			'not_found_in_trash' => 'В корзине производителей не найдено',
		);
		
		$args = array(
			'label' => 'Производители',
			'labels' => $labels,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt'
			),
			'public' => true,
			'exclude_from_search' => false,
			'show_in_rest' => true,
			'menu_position' => 6,
			'menu_icon' => 'dashicons-store',
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'proizvoditeli',
				'with_front' => false
			),
			'query_var' => true,
		);
				
		register_post_type('brand', $args);
	}

	function init_taxonomies() {
		// Категории брендов
		register_taxonomy('brand_category', 'brand', array(
			'labels' => array(
				'name' => 'Категории производителей',
				'singular_name' => 'Категория производителя',
				'search_items' => 'Поиск категорий',
				'all_items' => 'Все категории',
				'edit_item' => 'Редактировать категорию',
				'update_item' => 'Обновить категорию',
				'add_new_item' => 'Добавить новую категорию',
				'new_item_name' => 'Название новой категории',
				'menu_name' => 'Категории',
			),
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'kategorii-proizvoditeley'),
		));

		// Регионы
		register_taxonomy('region', 'brand', array(
			'labels' => array(
				'name' => 'Регионы',
				'singular_name' => 'Регион',
				'search_items' => 'Поиск регионов',
				'all_items' => 'Все регионы',
				'edit_item' => 'Редактировать регион',
				'update_item' => 'Обновить регион',
				'add_new_item' => 'Добавить новый регион',
				'new_item_name' => 'Название нового региона',
				'menu_name' => 'Регионы',
			),
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'regiony'),
		));

		// Бренд для продуктов (связь продуктов с производителями)
		register_taxonomy('product_brand', 'product', array(
			'labels' => array(
				'name' => 'Производители товаров',
				'singular_name' => 'Производитель товара',
				'search_items' => 'Поиск производителей',
				'all_items' => 'Все производители',
				'edit_item' => 'Редактировать производителя',
				'update_item' => 'Обновить производителя',
				'add_new_item' => 'Добавить производителя',
				'new_item_name' => 'Название производителя',
				'menu_name' => 'Производители',
			),
			'hierarchical' => false,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'proizvoditel'),
	
				// Фильтры (особенности) брендов
		register_taxonomy('brand_feature', 'brand', array(
			'labels' => array(
				'name' => 'Фильтры брендов',
				'singular_name' => 'Фильтр бренда',
				'search_items' => 'Поиск фильтров',
				'all_items' => 'Все фильтры',
				'edit_item' => 'Редактировать фильтр',
				'update_item' => 'Обновить фильтр',
				'add_new_item' => 'Добавить новый фильтр',
				'new_item_name' => 'Название нового фильтра',
				'menu_name' => 'Фильтры',
			),
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'brand-feature'),
		));
	}
}
new PLT_Brand();
