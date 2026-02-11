<?php
/**
 *
 * РАСПРЕДЕЛЕНИЕ ШАБЛОНОВ отображения контента в зависимости от раздела сайта.
 */
class PLT_Routes {

	function __construct() {
		add_filter( 'template_include', [ $this, 'replace_path' ], 99 );
	}

	/**
	 * Возвращает измененный путь к тому или иному шаблону.
	 *
	 * @param string $template путь к шаблону по умолчанию
	 *
	 * @return string
	 */
	function replace_path( $template ) {
		if (is_woocommerce()) return $template;
		
		global $post;
		
		$path = '';	
			
		if (is_page())
			$path = "page/{$post->post_name}";
		elseif (is_singular())
			$path = "{$post->post_type}/single";
		elseif (is_post_type_archive('brand'))
			$path = "brand/archive-brand";
		elseif (is_tax('brand_category') || is_tax('region'))
			$path = "brand/archive-brand";
			
		if ($path)
			return $this->locate_template( $path.'.php' );
	
		
		return $template;
	}


	/**
	 * Проверяет наличие указанного шаблона и заменяет им дефолтный шаблон.
	 *
	 * @param string  $path     Пользовательский путь к шаблону
	 *
	 * @return string
	 * @global string $template Дефолтный путь к шаблону
	 */
	 function locate_template( $path ) {
		global $template;

		$path = "templates/{$path}";

		if ( $new_template = locate_template( [ $path ] ) ) {
			$template = $new_template;
		}

		return $template;
	}
}
