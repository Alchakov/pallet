<?php
defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/inc/classes/PLT_Main.php';

function plt_site() {
	return PLT_Main::instance();
}

plt_site();

/************* THUMBNAIL SIZES *************/		
add_image_size('thumb_450x300', 450, 300, true);	
add_image_size('thumb_300x420', 300, 420, true);			

/************* REQUIRED MODULES *************/
require_once('inc/form/PLT_Form.php');
require_once('inc/customizer.php');
require_once('inc/shortcodes.php');
require_once('inc/custom_fields.php');

/************* REQUIRED POST TYPES *************/
require_once('inc/post_type/PLT_Client.php');
require_once('inc/post_type/PLT_Video.php');
require_once('inc/post_type/PLT_Office.php');
require_once('inc/post_type/PLT_Review.php');
require_once('inc/post_type/PLT_Discount.php');
require_once('inc/post_type/PLT_Worker.php');
require_once('inc/post_type/PLT_Brand.php');

/************* PRIVACY NOTIFICATION ********************/
add_action('init', function() {
    if(!(isset($_COOKIE['show_cookie_notification']) && $_COOKIE['show_cookie_notification'] == '1')) {
        setcookie('show_cookie_notification', '1', time()+60*60*24*30, '/');
    }
}, 1);

add_action('wp_footer', function() {
    $pass = (isset($_COOKIE['show_cookie_notification']) && $_COOKIE['show_cookie_notification']==1) ? false : true;
    if ($pass):?>
        <div class="cookie-notification">
            <p>Используя сайт вы&nbsp;соглашаетесь с&nbsp;использованием файлов cookie и&nbsp;сервисов сбора технических данных посетителей (IP-адресов, местоположения и&nbsp;др.) для обеспечения работоспособности и&nbsp;улучшения качества работы сайта. <a href="/politika-konfidenczialnosti/" class="link">Подробнее</a></p>
            <a href="#" class="cookie-notification__close"><span class="icon-cross"></span></a>
        </div>
    <?php endif;
},1);

/************* SSL ********************/
add_action('template_redirect', function(){
    if (!is_ssl () ) {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
        exit();
    }
});

function yfym_custom_query_arg_filter($args, $feed_id) {
	if($feed_id == 5) { //Замените на ID вашего фида - убрали. Если надо поставить 1
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => array(2868, 2871), // Замените на фактический ID вашей категории или на массив array(3206, 477) и т.д
				'operator' => 'IN',
			),
		);
	}
    return $args;
}

add_filter('yfym_query_arg_filter', 'yfym_custom_query_arg_filter', 10, 2);

/* Замена местами цен
function my_custom_yfym_simple_price_xml_filter( $price_yml, $product, $feed_id ) {
	$id = $product->get_id();
	$retail_price = get_post_meta($id, '_retail_price', true);
  $price_yml = $retail_price;
  return $price_yml;
}
add_filter( 'yfym_simple_price_filter', 'my_custom_yfym_simple_price_xml_filter', 10, 3 );
*/


function custom_enqueue_scripts() {
    // Подключаем скрипт для фильтров
    wp_enqueue_script('custom-filter-script', get_template_directory_uri() . '/custom-filter.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');





function custom_admin_menu() {
    // Переименование пункта меню "Записи"
    global $menu;
    foreach ($menu as $key => $value) {
        if ($value[0] == 'WBW Product Filter') { // Замените 'Записи' на текущее название
            $menu[$key][0] = 'Фильтр'; // Замените 'Новые записи' на нужное название
        }
       
    }
}
add_action('admin_menu', 'custom_admin_menu');








 





/////////////////////////////

function enqueue_custom_catalog_script() {
    if (is_shop() || is_product_category() || is_product_tag()) {
    // Подключаем первый скрипт
    wp_enqueue_script('custom-catalog-js', get_template_directory_uri() . '/js/custom-catalog.js', array('jquery'), null, true);
    
    // Подключаем второй скрипт
    wp_enqueue_script('custom-hide-catalog-attribute', get_template_directory_uri() . '/js/custom-hide-catalog-attribute.js', array('jquery'), null, true);
}

    // Проверяем, находится ли мы на странице карточки товара
    if (is_product()) {
        wp_enqueue_script('custom-single-product-js', get_template_directory_uri() . '/js/custom-single-product.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_catalog_script');








///////////////////////////////////////////////

// Добавление полей при создании новой категории
add_action('product_cat_add_form_fields', 'add_custom_product_cat_fields');
function add_custom_product_cat_fields() {
    ?>
    <div class="form-field">
        <label for="custom_field_one"><?php _e('Текст под заголовком', 'text-domain'); ?></label>
        <?php wp_editor('', 'custom_field_one', array('textarea_name' => 'custom_field_one', 'editor_height' => 100)); ?>
        <p class="description"><?php _e(' ', 'text-domain'); ?></p>
    </div>

    <div class="form-field">
        <label for="custom_field_two"><?php _e('Текст под фильтром', 'text-domain'); ?></label>
        <?php wp_editor('', 'custom_field_two', array('textarea_name' => 'custom_field_two', 'editor_height' => 100)); ?>
        <p class="description"><?php _e(' ', 'text-domain'); ?></p>
    </div>
    <?php
}

// Добавление полей при редактировании категории
add_action('product_cat_edit_form_fields', 'edit_custom_product_cat_fields');
function edit_custom_product_cat_fields($term) {
    $custom_field_one = get_term_meta($term->term_id, 'custom_field_one', true);
    $custom_field_two = get_term_meta($term->term_id, 'custom_field_two', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="custom_field_one"><?php _e('Текст под заголовком', 'text-domain'); ?></label></th>
        <td>
            <?php wp_editor($custom_field_one, 'custom_field_one', array('textarea_name' => 'custom_field_one', 'editor_height' => 100)); ?>
            <p class="description"><?php _e(' ', 'text-domain'); ?></p>
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row"><label for="custom_field_two"><?php _e('Текст под фильтром', 'text-domain'); ?></label></th>
        <td>
            <?php wp_editor($custom_field_two, 'custom_field_two', array('textarea_name' => 'custom_field_two', 'editor_height' => 100)); ?>
            <p class="description"><?php _e(' ', 'text-domain'); ?></p>
        </td>
    </tr>
    <?php
}


// Сохранение значений полей при редактировании категории
add_action('edited_product_cat', 'save_custom_product_cat_fields');
// Сохранение значений полей при создании категории
add_action('create_product_cat', 'save_custom_product_cat_fields');

function save_custom_product_cat_fields($term_id) {
    if (isset($_POST['custom_field_one'])) {
        update_term_meta($term_id, 'custom_field_one', wp_kses_post($_POST['custom_field_one']));
    }
    if (isset($_POST['custom_field_two'])) {
        update_term_meta($term_id, 'custom_field_two', wp_kses_post($_POST['custom_field_two']));
    }
}

// ============ RATING & REVIEWS ============
require_once get_template_directory() . '/inc/rating-functions.php';
require_once get_template_directory() . '/inc/brand-filters-admin.php';


// ------------- FONT AWESOME -------------
function plt_enqueue_font_awesome() {
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		array(),
		'6.5.1'
	);
}
add_action('wp_enqueue_scripts', 'plt_enqueue_font_awesome');
