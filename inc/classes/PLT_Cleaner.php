<?php

class PLT_Cleaner {
	
	function __construct() {
		add_action('init', [ $this, 'head_cleanup' ] );		
		
		add_filter('rest_authentication_errors', function( $result ) {
			$rest_route = $GLOBALS['wp']->query_vars['rest_route'];
			$allow = '/pallet/v1';

			if ( substr($rest_route, 0, strlen($allow)) === $allow )
				return $result;

			if( empty( $result ) && ! current_user_can('edit_others_posts') )
				return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );

			return $result;
		});		
		
		add_filter('the_generator', function() {
		    return '';
		});	
			
		add_filter('wp_head', function() {
			global $wp_widget_factory;			
			if (has_filter('wp_head', 'wp_widget_recent_comments_style')) {
				remove_filter('wp_head', 'wp_widget_recent_comments_style');
			}			
			if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
				remove_action('wp_head', array(
					$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
					'recent_comments_style'
				));
			}
		}, 1);
				
		add_filter('gallery_style', function( $css ) {
			return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
		});					
	}
	
	function head_cleanup() {
		remove_action('wp_head', 'feed_links_extra', 3);
	    remove_action('wp_head', 'feed_links', 2);
	    remove_action('wp_head', 'rsd_link');
	    remove_action('wp_head', 'wlwmanifest_link');
	    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	    remove_action('wp_head', 'start_post_rel_link', 10, 0);
	    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	    remove_action('wp_head', 'wp_generator');
	   	add_filter('style_loader_src', [ $this, 'remove_wp_ver_css_js' ], 9999);
	    add_filter('script_loader_src', [ $this, 'remove_wp_ver_css_js' ], 9999);
	    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	    remove_action('template_redirect', 'wp_shortlink_header', 11);
	    header_remove('x-powered-by');
	    remove_action('wp_head', 'index_rel_link');
	    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	    add_filter('wp_headers', function($headers) {
		    unset($headers['X-Pingback']);
		    return $headers;
		});	    
	    remove_action('wp_head', 'print_emoji_detection_script', 7);
	    remove_action('wp_print_styles', 'print_emoji_styles');
		
		if(! current_user_can('edit_others_posts') ) {
			 // Отключаем REST API
			add_filter('rest_enabled', '__return_false');
			add_filter('rest_jsonp_enabled', '__return_false');
			add_filter('json_enabled', '__return_false');
			add_filter('json_jsonp_enabled', '__return_false');
			
			// Отключаем фильтры REST API
			remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
			remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
			remove_action('template_redirect', 'rest_output_link_header', 11, 0);
			remove_action('auth_cookie_malformed', 'rest_cookie_collect_status');
			remove_action('auth_cookie_expired', 'rest_cookie_collect_status');
			remove_action('auth_cookie_bad_username', 'rest_cookie_collect_status');
			remove_action('auth_cookie_bad_hash', 'rest_cookie_collect_status');
			remove_action('auth_cookie_valid', 'rest_cookie_collect_status');
			remove_filter('rest_authentication_errors', 'rest_cookie_check_errors', 100);
			
			// Отключаем события REST API
			remove_action('init', 'rest_api_init');
			remove_action('rest_api_init', 'rest_api_default_filters', 10, 1);
			//remove_action( 'parse_request', 'rest_api_loaded' );
			
			// Отключаем Embeds связанные с REST API
			remove_action('rest_api_init', 'wp_oembed_register_route');
			remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);    
			remove_action('wp_head', 'wp_oembed_add_discovery_links');  
			remove_action('wp_head', 'wp_oembed_add_host_js');
		}
	}
	
	function remove_wp_ver_css_js($src) {
	    if (strpos($src, 'ver=') && !strpos($src, '/pallet/assets/'))
	        $src = remove_query_arg('ver', $src);
	    return $src;
	}	
	
}