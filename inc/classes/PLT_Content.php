<?php

class PLT_Content {
	
	function __construct() {
		
		add_action('the_content', [ $this, 'content_cleaner' ], 20, 1);	
		
		add_filter('excerpt_more', function( $more ) {
			return '...';
		});
		add_filter( 'excerpt_length', function( $length ) {
			return 30;
		}, 999 );
		
		add_filter( 'wp_get_attachment_image_attributes', function( $attr ) {
		    unset($attr['title']) ;
		    return $attr;
		});			
	}
	
	function content_cleaner( $content ) {
	   //Убираем верхний отступ
	   $tags = array("<h1", "<h2", "<h3", "\n<h");
	   $tag_content = substr( $content, 0, 3 );
	   if (in_array($tag_content,$tags))
		   $content = '<div class="remove-top-margin"></div>'.$content;	
		   
	   //Убираем p у изображений	
	   $content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	   
	   //Убираем пустые строки
	   $content = force_balance_tags($content);
	   $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
	   $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
	   return $content;
	}
	
	/**
	 * Возвращает заголовок для архива.
	 *
	 * @return string
	 */
	static function archive_title() {
	    $title = "";
	    if (is_category() || is_tax() || is_tag())
	        $title = single_cat_title('', false);
	    elseif (is_archive())
	        $title = post_type_archive_title('', false);
		elseif (is_home())
		        $title = get_the_title(get_option('page_for_posts', true));
	    elseif (is_search())
	        $title = "Результаты поиска для: " . esc_attr(get_search_query());
			
	    return $title;
	}
	
	
	/**
	 * Возвращает пагинацию.
	 *
	 * @return string
	 */
	static function pagination($query = "") {
	    global $wp_query;	
		
		$bignum = 999999999;
			
	    if (!$query)	
	        $query = $wp_query;		
		
	    if ($query->max_num_pages <= 1)
	        return;    
			
	    $result_pl = paginate_links(array(
	        'base' => str_replace($bignum, '%#%', esc_url(get_pagenum_link($bignum))),
	        'format' => '',
	        'current' => max(1, get_query_var('paged')),
	        'total' => $query->max_num_pages,
	        'prev_text' => '<span class="icon-arrow-left-long"></span>',
	        'next_text' => '<span class="icon-arrow-right-long"></span>',
	        'type' => 'list',
	        'end_size' => 3,
	        'mid_size' => 3
	    ));
	    $result_pl = str_replace('/page/1/', '/', $result_pl);
		
		return '<div class="pagination">'.$result_pl.'</div>';
	}

	static function nonce_field( $action = -1, $name = '_wpnonce', $referer = true, $echo = true ) {
		$name        = esc_attr( $name );
		$nonce_field = '<input type="hidden" name="' . $name . '" value="' . wp_create_nonce( $action ) . '" />';

		if ( $referer ) {
			$nonce_field .= wp_referer_field( false );
		}

		if ( $echo ) {
			echo $nonce_field;
		}

		return $nonce_field;
	}
	
}