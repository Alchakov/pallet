<?php

class PLT_Menu {
	
	function __construct() {			
		add_action('nav_menu_css_class', [ $this, 'css_attributes_filter' ], 100, 1);	
		add_action('nav_menu_item_id', [ $this, 'css_attributes_filter' ], 100, 1);	
		add_action('category_css_class', [ $this, 'css_attributes_filter' ]);
		add_filter( 'nav_menu_css_class',  [ $this, 'change_menu_classes' ], 101, 3 );		
		add_filter( 'wp_nav_menu',  [ $this, 'change_menu_text' ]);			
		add_filter( 'category_css_class',  [ $this, 'change_category_css_class' ]);				
		add_filter( 'list_cats', [ $this, 'change_list_cats' ], 10, 2);
	}
	
	function css_attributes_filter( $var ) {
	    return is_array($var) ? array_intersect($var, array(
	        'current-menu-item',
	        'menu-item-has-children',
			'current-product-ancestor',
			'current_page_parent',
	        'current-menu-parent',
			'current-menu-ancestor',
			'current-cat-ancestor',
			'current-cat'
	    )) : '';
	}
		
	function change_menu_classes( $classes, $item, $args ) {		
		global $post;	
		
		
		if (((is_archive() || !is_singular('post')) && !is_category()) && $item->title == get_the_title(get_option('page_for_posts', true))){
	        $classes = array_diff( $classes, array( 'current_page_parent' ) );
	    }
		
		if (is_product()) {
			$term = wp_cache_get( 'primary_term', 'plt_menu' );			
			if( false === $term ) {
				$term = plt_site()->get_primary_term(get_the_ID(), 'product_cat');	
				wp_cache_set( 'primary_term', $term, 'plt_menu' );
			}
			
			if ($item->object_id != $term->term_id) {
				$classes = array_intersect(['menu-item-has-children'], $classes);
			}
						
		} else {
			
			$post_type = get_post_type($post->ID);
			if ($post_type != 'post' && $post_type != 'page') {
				$current_post_type = get_post_type_object($post_type);
				$current_post_type_slug = $current_post_type->rewrite['slug'];
				$menu_slug = strtolower(trim($item->url));
		        if ('/'.$current_post_type_slug.'/' == substr($menu_slug, -strlen($current_post_type_slug)-2)) {
				   $classes[] = 'current-menu-item';
				}
			}
			
		}		
		
	    return $classes;
	}
	
	function change_menu_text($text ) {
	    $replace = array(
	        'current-menu-item' => 'active-page is-active',
	        'current-page-ancestor' => 'is-active',
			'current_page_parent' => 'is-active',
	        'current-menu-parent' => 'is-active',
	        'current-menu-ancestor' => 'is-active',
			'current-product-ancestor' => 'is-active',
	    );
	    $text = str_replace(array_keys($replace), $replace, $text);
		$text = preg_replace('/(is-active\s?)+/m', 'is-active ', $text);
	    return $text;
	}
	
	function change_category_css_class( $text ) {
		$replace = array(
			'current-cat' => 'is-active'
		);
		$text = str_replace(array_keys($replace), $replace, $text);
		return $text;
	}
	
	function change_list_cats( $element, $category ) {	
		return '<span class="help">'.$element.'</span>';
	}
	
}