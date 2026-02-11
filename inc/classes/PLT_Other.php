<?php

class PLT_Other {
		
	function __construct() {
		add_filter( 'wpseo_breadcrumb_links', [$this, 'wpseo_breadcrumb_links']); 
		add_filter( 'wpseo_breadcrumb_single_link', [$this, 'filter_wpseo_breadcrumb_single_link'], 10, 2 ); 
		add_filter( 'wpseo_breadcrumb_output_wrapper', [$this, 'filter_wpseo_breadcrumb_output_wrapper'], 10, 1 ); 
	}	
	
	function wpseo_breadcrumb_links( $links ) {
		if ( is_woocommerce() && isset( $links[1]['ptarchive'] ) && 'product' === $links[1]['ptarchive'] ) {
			unset( $links[1] );
		}
		$links = array_values( $links );
		return $links;
	}
	
	function filter_wpseo_breadcrumb_single_link( $link_output, $link ) {
		if (strpos($link_output, 'breadcrumb_last') !== false)
			return '<li><span class="current">' . $link['text'] . '</span></li>';  
		else
			return '<li><a href="' . esc_url( $link['url'] ) . '">' . $link['text'] . '</a></li>';
	}
	
	function filter_wpseo_breadcrumb_output_wrapper( $this_wrapper ) { 
	    return 'ul'; 
	}
	
	static function phone_number($type = '') {
		$arr = explode("\n", plt_option('phone'));
		if (!empty($arr)) {
			$first = array_shift($arr);
			switch ($type) {
				case 'main':
					return $first;
					break;
				case 'other':
					return $arr;
					break;
				default:
					return array_merge([$first], $arr);
					break;
			}
		}
		return false;	
	}
	
	static function social_list() {
		return [
			'vk' 			=> 'VK',
			'facebook' 		=> 'Facebook',
			'instagram'		=> 'Instagram',
			'odnoklassniki' => 'Одноклассники',
			'youtube' 		=> 'YouTube',
			'telegram' 		=> 'Telegram',
			'whatsapp' 		=> 'WhatsApp',
			'viber' 		=> 'Viber',
		];
	}
			
}