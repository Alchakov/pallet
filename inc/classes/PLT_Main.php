<?php

class PLT_Main {
	
	private static $instance = null;
	private $url;
	private $path;
		

	/**
	 * @return null|PLT_Main
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	function __construct() {
		$this->url  = get_template_directory_uri();
		$this->path = get_parent_theme_file_path();			
			
		$this->includes();
		$this->hooks();
	}

	function includes() {
		// Очистка
		require_once __DIR__ . '/PLT_Cleaner.php';
		new PLT_Cleaner();
		
		// Вспомогательные функции	
		require_once __DIR__ . '/PLT_Helper.php';
		new PLT_Helper();
		
		// Шаблоны
		require_once __DIR__ . '/PLT_Routes.php';
		new PLT_Routes();
		
		// Админ
		require_once __DIR__ . '/PLT_Admin.php';
		new PLT_Admin();
		
		// Обертка + Блоки
		require_once __DIR__ . '/PLT_Wrapper.php';
		new PLT_Wrapper();
		
		// Меню
		require_once __DIR__ . '/PLT_Menu.php';
		new PLT_Menu();
		
		// Контент
		require_once __DIR__ . '/PLT_Content.php';
		new PLT_Content();	
		
		// Комментарии
		require_once __DIR__ . '/PLT_Comment.php';
		new PLT_Comment();	
		
		// Прочее
		require_once __DIR__ . '/PLT_Other.php';
		new PLT_Other();	
		
		// Woocommerce
		require_once __DIR__ . '/PLT_Woocommerce.php';
		new PLT_Woocommerce();

		// Woocommerce
		require_once __DIR__ . '/PLT_REST_API.php';
		new PLT_REST_API();
	}

	/**
	 * Подключает хуки, нужные для работы темы.
	 *
	 * @return void
	 */
	function hooks() {
		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts_and_styles' ] );
	}
		
	/**
	 * Задаёт основные настройки темы.
	 *
	 * @return void
	 */
	function after_setup_theme() {
		add_theme_support('post-thumbnails');
    	set_post_thumbnail_size(150, 150);
	    add_theme_support('menus');
	    register_nav_menus(array(
			'top-nav' => 'Верхнее меню',
			'main-nav' => 'Главное меню',
			'catalog-nav' => 'Каталог',
			'footer-nav-left' => 'Нижнее меню - Левое',
			'footer-nav-middle' => 'Нижнее меню - Среднее',
			'footer-nav-right' => 'Нижнее меню - Правое',
			'mobile-nav' => 'Мобильное меню',
	    ));
		add_theme_support('html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption'
		));
		add_filter( 'upload_mimes', function( $mimes ){
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		});

	}

	/**
	 * Подключение css, js и шрифтов
	 *
	 * @return void
	 */
	function scripts_and_styles() {
					
		//style
        wp_enqueue_style('googlefont', 'https://fonts.googleapis.com/css?family=Montserrat:400,600,700,700i,900&display=swap&subset=cyrillic', [] , '1', 'all');	
        wp_enqueue_style('icomoon', $this->get_asset('fonts/icomoon/style.css'), [], '1', 'all');
		wp_enqueue_style('pallet', $this->get_asset('css/pallet.min.css'), [], '150124-1', 'all');
		
        //script		
		wp_deregister_script('jquery');
		wp_deregister_script('jquery-migrate');
		wp_enqueue_script( 'jquery', $this->get_asset('js/vendor/jquery-3.3.1.min.js'));
		wp_enqueue_script( 'jquery-migrate', $this->get_asset('js/vendor/jquery-migrate-3.0.0.min.js'));
		
		wp_enqueue_script('pallet', $this->get_asset('js/pallet.lib.min.js'), NULL, '1', true);
		wp_enqueue_script('pallet-config', $this->get_asset('js/config.min.js'), array('pallet'), '150124-1', true);
		wp_enqueue_script('pallet-form', $this->get_asset('js/form.min.js'), array('pallet'), '1', true);		
		wp_enqueue_script('pallet-product', $this->get_asset('js/product.min.js'), array('pallet'), '2', true);
		
		if (is_front_page()) {
			//wp_enqueue_style('aos',$this->get_asset('js/lib/aos/aos.css'), array(), '1', 'all');
			//wp_enqueue_script('aos', $this->get_asset('js/lib/aos/aos.js'), array('jquery'), '1', true);			
			//wp_add_inline_script('aos', 'AOS.init({offset: 150, delay: 0, duration: 800, easing: "ease", once: true, mirror: false});');
		}
		
		if (is_front_page() || is_page(27)) {
			wp_enqueue_script('yandex', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU', NULL, '1', true);
			wp_enqueue_script('offices-map', $this->get_asset('js/offices.min.js'), array('yandex'), '2', true);
		}

		if (is_post_type_archive('brand') || is_singular('brand') || is_tax('brand_category') || is_tax('region')) {
			wp_enqueue_style('brands', $this->get_asset('css/brands.css'), ['pallet'], '1', 'all');
			wp_enqueue_script('yandex', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU', NULL, '1', true);
			wp_enqueue_script('brands-map', $this->get_asset('js/brands-map.min.js'), array('yandex', 'jquery'), '1', true);
			wp_enqueue_script('brands-filter', $this->get_asset('js/brands-filter.min.js'), array('jquery'), '1', true);
		}

		if (is_singular('brand')) {
			wp_enqueue_script('brand-tabs', $this->get_asset('js/brand-tabs.min.js'), array('jquery'), '1', true);
		}		
		
		if ( is_singular('post') && comments_open() && get_option( 'thread_comments' ) ) { 		  
		    wp_enqueue_script( 'comment-reply' ); 
		}
	}

	/**
	 * Возвращает изображение или на папку с файлами без закрывающего слеша
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	function get_img_url( $path = '' ) {
		return $this->url . '/assets/images/' . ltrim( $path, '/' );
	}
	
	/**
	 * Выводит изображение
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	function get_img( $path = '' ) {
		echo sprintf('<img src="%s" alt="" />', $this->get_img_url($path));
	}	

	/**
	 * Возвращает ссылку без закрывающего слеша на папку с файлами или полный путь к файлу, если указан.
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	function get_asset( $path = '' ) {
		$url = $this->url . '/assets';
		$url = $path ? $url . '/' . ltrim( $path, '/' ) : $url;

		return $url;
	}

	/**
	 * Подключает указанный шаблон.
	 *
	 * @param string $slug
	 * @param array  $params
	 * @param array  $output
	 */	
	function tpl( $slug, $params = [], $output = true ) {
	    if(!$output) ob_start();
	    if (!$template_file = locate_template("{$slug}.php", false, false)) {
	      trigger_error(sprintf(__('Error locating %s for inclusion', 'ft'), $file), E_USER_ERROR);
	    }
	    extract($params, EXTR_SKIP);
	    require($template_file);
	    if(!$output) return ob_get_clean();
	}
	
	/**
	 * Возвращает главную таксономию
	 *
	 * @param int $id
	 * @param string $taxonomy
	 *
	 * @return object
	 */
	function get_primary_term($id, $taxonomy) {
	    if ( class_exists('WPSEO_Primary_Term') )  {
	        $wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy,  $id );
	        $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
	        $term = get_term( $wpseo_primary_term );
	        if (is_wp_error($term)) {
	            $terms = wp_get_object_terms( $id, $taxonomy );
	            $term = $terms[0];
	        }
	    } else {
	        $terms = wp_get_object_terms( $id, $taxonomy );
	        $term = $terms[0];
	    }
	    return $term;
	}
	
	function the_placeholder_img($size) {
		echo $this->get_placeholder_img($size);
	}
	function get_placeholder_img($size) {
		return wp_get_attachment_image(130, $size);
	}
}
