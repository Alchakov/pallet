<?php
class PLT_REST_API {

    const NAME_SPACE = 'pallet/v1';

    function __construct() {
        add_filter( 'rest_api_init', [ $this, 'register_rest_route' ] );
    }


    function register_rest_route() {
        register_rest_route( self::NAME_SPACE, '/menu/', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_menu' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/catalog/(?P<category>[a-zA-Z0-9_-]+)/(?P<paged>[0-9]+)', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_catalog' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/product/(?P<product>[a-zA-Z0-9_-]+)', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_product' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/product-buy/(?P<product>[0-9]+)', [
            'methods'  => 'GET',
            'callback' => [ $this, 'buy_product' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/catalog-front', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_catalog_front' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/catalog-sitemap', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_catalog_sitemap' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/brands-map', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_brands_map' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/brand-map/(?P<brand_id>[0-9]+)', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_brand_map' ],
        ]);

        register_rest_route( self::NAME_SPACE, '/brands-filter', [
            'methods'  => 'GET',
            'callback' => [ $this, 'get_brands_filter' ],
        ]);
    }

    function get_catalog_sitemap( WP_REST_Request $request ) {
        $response = do_shortcode('[wp_sitemap_page only="product_cat"]');
        $response .= do_shortcode('[wp_sitemap_page only="product"]');

        return rest_ensure_response( $response );
    }

    function get_catalog_front( WP_REST_Request $request ) {
        $items = carbon_get_theme_option('plt_front_catalog_items');

        if ( empty( $items) ) {
            $response = false;
        } else {
            ob_start();
            woocommerce_product_loop_start();
            foreach ( $items as $item ) :
                $post_object = get_post($item['id']);
                setup_postdata( $GLOBALS['post'] =& $post_object );
                do_action( 'woocommerce_shop_loop' );
                echo '<li>';
                wc_get_template_part( 'content', 'product' );
                echo '</li>';
            endforeach;
            woocommerce_product_loop_end();
            wp_reset_postdata();
            $response = ob_get_clean();
        }

        return rest_ensure_response( $response );
    }

    function get_menu( WP_REST_Request $request ) {

        if (wp_is_mobile()) {
            $class_add = '';
            $depth = 1;
        } else {
            $class_add = ' hierarchical-menu';
            $depth = 3;
        }

        $response['catalog'] = wp_nav_menu(array(
            'theme_location' => 'catalog-nav',
            'container' => false,
            'menu_class' => 'sub-menu',
            'menu_id' => 'catalog-nav',
            'link_before' => '<span class="help">',
            'link_after' => '</span>',
            'echo' => false
        ));

        $response['main'] =  wp_nav_menu(array(
            'theme_location' => 'main-nav',
            'container' => false,
            'menu_class' => 'main-menu'.$class_add,
            'menu_id' => 'main-nav',
            'link_before' => '<span class="help">',
            'link_after' => '</span>',
            'depth' => $depth,
            'echo' => false
        ));

        return rest_ensure_response( $response );
    }


    function get_catalog( WP_REST_Request $request ) {
        $category_slug = $request->get_param( 'category' );
        $paged  = $request->get_param( 'paged' );
        $category = get_term_by( 'slug', $category_slug, 'product_cat' );
        $parameters = $request->get_query_params();

        //content
        $args = [
            'posts_per_page' => 12,
            'post_type' 	 => 'product',
            'paged' 		 => $paged,
            'post_parent' 	 => 0,
            'fields' 		 => 'ids',
        ];

        if ( $category_slug != 'all' )
            $args['product_cat'] = $category_slug;

        if ( isset( $parameters['orderby'] ) && ( ( $parameters['orderby'] == 'price-desc' ) || ( $parameters['orderby'] == 'price' ) ) ) {
            $order = [
                'meta_key'       => '_price',
                'orderby'        => 'meta_value_num',
                'order'          => ($parameters['orderby'] == 'price-desc') ? 'DESC' : 'ASC'
            ];
        } else {
            $order = [
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ];
        }

        $products = new WP_Query( array_merge( $args, $order ) );

        ob_start();

        if ( $products->have_posts() ) {

            woocommerce_product_loop_start();

            while ( $products->have_posts() ) {
                $products->the_post();
                do_action( 'woocommerce_shop_loop' );
                echo '<li>';
                wc_get_template_part( 'content', 'product' );
                echo '</li>';
            }

            woocommerce_product_loop_end();

        } else {
            echo 'Ничего нет';
        }

        wp_reset_postdata();

        $products_list = ob_get_contents();

        ob_clean();

        plt_wc_catalog()->advantages($category->term_id);

        $advantages = ob_get_contents();

        ob_clean();

        plt_wc_catalog()->sub_cat($category->term_id);

        $sub_cat = ob_get_contents();

        ob_clean();

        wc_get_template( 'loop/content.php', ['term' => $category] );

        $content = ob_get_contents();

        ob_end_clean();

        //seo
        $wpseo_titles = get_option( 'wpseo_titles', [] );
        $meta = get_option( 'wpseo_taxonomy_meta' );
        if ( isset($meta) ) {
            $seo_title  = $meta['product_cat'][$category->term_id]['wpseo_title'];
            $seo_desc  = $meta['product_cat'][$category->term_id]['wpseo_desc'];
            if ( empty( $seo_title ) ) {
                $yoast_title  = isset( $wpseo_titles[ 'title-tax-product_cat' ] ) ? $wpseo_titles[ 'title-tax-product_cat'] : $category->name;
                $seo_title = wpseo_replace_vars( $yoast_title, $category );
            }
            if ( empty( $seo_desc ) ) {
                $yoast_description = isset( $wpseo_titles[ 'metadesc-tax-product_cat' ] ) ? $wpseo_titles[ 'metadesc-tax-product_cat' ] : '';
                $seo_desc = wpseo_replace_vars( $yoast_description, $category );
            }
        }
        //breadcrumbs
        $breadcrumbs = [];
        if ($category_slug != 'all') {
            $parents = get_ancestors( $category->term_id, 'product_cat' );
            foreach ( array_reverse( $parents ) as $cat ) {
                $breadcrumbs[]  = [
                    'text' =>  get_term($cat)->name,
                    'url'  =>  get_term_link( $cat )
                ];
            }
            $breadcrumbs[]  = [
                'text' =>  $category->name,
                'url'  =>  get_term_link( $category->term_id )
            ];
        }

        $response = [
            'title'         => ($category_slug == 'all') ? 'Каталог' : $category->name,
            'products'      => $products_list,
            'max_num_pages' => $products->max_num_pages,
            'advantages'    => $advantages,
            'content'       => $content,
            'sub_cat'       => $sub_cat,
            'seo_title'     => $seo_title,
            'seo_desc'      => $seo_desc,
            'breadcrumbs'   => $breadcrumbs
        ];

        return rest_ensure_response( $response );
    }


    function get_product( WP_REST_Request $request ) {
        global $product;

        $slug = $request->get_param( 'product' );
        $product_obj = get_page_by_path( $slug, OBJECT, 'product' );
        $id = $product_obj->ID;
        $title = get_the_title( $id );
        $wc_product = wc_get_product($id);
        $product = $wc_product;


        ob_start();

        //gallery
        PLT_WC_Product()->show_sticker();
        woocommerce_show_product_images();
        $gallery = ob_get_contents();

        //summary
        ob_clean();
        PLT_WC_Product()->product_attributes();
        PLT_WC_Product()->product_stock();
        woocommerce_template_single_price();
        $summary = ob_get_contents();

        //content
        ob_clean();
        PLT_WC_Product()->product_content();
        $content = ob_get_contents();

        //upsell
        ob_clean();
        woocommerce_upsell_display();
        $upsell = ob_get_contents();

        ob_end_clean();

        //seo
        $wpseo_titles = get_option( 'wpseo_titles', [] );
        $seo_title = get_post_meta($id, '_yoast_wpseo_title', true);
        if ( empty( $seo_title ) ) {
            $yoast_title  = isset( $wpseo_titles[ 'title-product' ] ) ? $wpseo_titles[ 'title-product'] : get_the_title();
            $seo_title = wpseo_replace_vars( $yoast_title, $product_obj );
        }
        $seo_desc  = get_post_meta($id, '_yoast_wpseo_metadesc', true);
        if ( empty( $seo_desc ) ) {
            $yoast_description = isset( $wpseo_titles[ 'metadesc-product' ] ) ? $wpseo_titles[ 'metadesc-product' ] : '';
            $seo_desc = wpseo_replace_vars( $yoast_description, $product_obj );
        }

        //breadcrumbs
        $main_cat = plt_site()->get_primary_term($id, 'product_cat');
        $breadcrumbs = [];
        $parents = get_ancestors( $main_cat->term_id, 'product_cat' );
        foreach ( array_reverse( $parents ) as $cat ) {
            $breadcrumbs[]  = [
                'text' =>  get_term($cat)->name,
                'url'  =>  get_term_link( $cat )
            ];
        }
        $breadcrumbs[]  = [
            'text' =>  $main_cat->name,
            'url'  =>  get_term_link( $main_cat->term_id )
        ];
        $breadcrumbs[]  = [
            'text' =>  $title
        ];

        $response = [
            'title'       => $title,
            'gallery'     => $gallery,
            'summary'     => $summary,
            'content'     => $content,
            'upsell'      => $upsell,
            'seo_title'   => $seo_title,
            'seo_desc'    => $seo_desc,
            'breadcrumbs' => $breadcrumbs
        ];

        return rest_ensure_response( $response );
    }

    function buy_product( WP_REST_Request $request ) {
        $id = $request->get_param( 'product' );
        ob_start();
        plt_wc_product()->buy_action($id);
        $response = ob_get_clean();

        return rest_ensure_response( $response );
    }

    function get_brands_map( WP_REST_Request $request ) {
        $params = $request->get_query_params();
        
        $args = array(
            'post_type' => 'brand',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        // Фильтр по регионам
        if (!empty($params['regions'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'region',
                'field'    => 'term_id',
                'terms'    => is_array($params['regions']) ? $params['regions'] : explode(',', $params['regions']),
            );
        }

        // Фильтр по категориям
        if (!empty($params['categories'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'brand_category',
                'field' => 'slug',
                'terms' => is_array($params['categories']) ? $params['categories'] : explode(',', $params['categories']),
            );
        }

        // Фильтр по минимальной поставке
        if (!empty($params['min_order'])) {
            $min_orders = is_array($params['min_order']) ? $params['min_order'] : explode(',', $params['min_order']);
            $meta_query = array('relation' => 'OR');
            foreach ($min_orders as $min_order) {
                $meta_query[] = array(
                    'key' => 'plt_brand_min_order',
                    'value' => $min_order,
                    'compare' => '='
                );
            }
            $args['meta_query'][] = $meta_query;
        }

        // Фильтр по кастомизации
        if (!empty($params['customization'])) {
            $customizations = is_array($params['customization']) ? $params['customization'] : explode(',', $params['customization']);
            $meta_query = array('relation' => 'OR');
            foreach ($customizations as $customization) {
                $meta_query[] = array(
                    'key' => 'plt_brand_customization',
                    'value' => $customization,
                    'compare' => 'LIKE'
                );
            }
            $args['meta_query'][] = $meta_query;
        }

        // Фильтр по наличию прайса
        if (!empty($params['has_price'])) {
            $args['meta_query'][] = array(
                'key' => 'plt_brand_has_price',
                'value' => 'yes',
                'compare' => '='
            );
        }

        $brands = get_posts($args);
        $response = array();

        foreach ($brands as $brand) {
            $coords = carbon_get_post_meta($brand->ID, 'plt_brand_map_coords');
            if (empty($coords)) {
                $address = carbon_get_post_meta($brand->ID, 'plt_brand_map_address');
                if ($address) {
                    // Здесь можно добавить геокодирование адреса через Yandex Geocoder API
                    // Пока используем адрес как есть
                    continue; // Пропускаем бренды без координат
                }
                continue;
            }

            $response[] = array(
                'id' => $brand->ID,
                'title' => get_the_title($brand->ID),
                'coords' => $coords,
                'address' => carbon_get_post_meta($brand->ID, 'plt_brand_address'),
                'phone' => carbon_get_post_meta($brand->ID, 'plt_brand_phone'),
                'email' => carbon_get_post_meta($brand->ID, 'plt_brand_email'),
                'website' => carbon_get_post_meta($brand->ID, 'plt_brand_website'),
                'url' => get_permalink($brand->ID),
            );
        }

        return rest_ensure_response($response);
    }

    function get_brand_map( WP_REST_Request $request ) {
        $brand_id = $request->get_param('brand_id');
        
        $brand = get_post($brand_id);
        if (!$brand || $brand->post_type !== 'brand') {
            return rest_ensure_response(array());
        }

        $coords = carbon_get_post_meta($brand_id, 'plt_brand_map_coords');
        if (empty($coords)) {
            return rest_ensure_response(array());
        }

        $response = array(
            array(
                'id' => $brand_id,
                'title' => get_the_title($brand_id),
                'coords' => $coords,
                'address' => carbon_get_post_meta($brand_id, 'plt_brand_address'),
                'phone' => carbon_get_post_meta($brand_id, 'plt_brand_phone'),
                'email' => carbon_get_post_meta($brand_id, 'plt_brand_email'),
                'website' => carbon_get_post_meta($brand_id, 'plt_brand_website'),
                'url' => get_permalink($brand_id),
            )
        );

        return rest_ensure_response($response);
    }

    function get_brands_filter( WP_REST_Request $request ) {
        $params = $request->get_query_params();
        
        $args = array(
            'post_type' => 'brand',
            'posts_per_page' => isset($params['per_page']) ? intval($params['per_page']) : 12,
            'paged' => isset($params['paged']) ? intval($params['paged']) : 1,
            'post_status' => 'publish',
        );

        // Фильтр по регионам
        if (!empty($params['regions'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'region',
                'field' => 'term_id',
                'terms' => is_array($params['regions']) ? $params['regions'] : explode(',', $params['regions']),
            );
        }

        // Фильтр по категориям
        if (!empty($params['categories'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'brand_category',
                'field' => 'slug',
                'terms' => is_array($params['categories']) ? $params['categories'] : explode(',', $params['categories']),
            );
        }

        // Фильтр по минимальной поставке
        if (!empty($params['min_order'])) {
            $min_orders = is_array($params['min_order']) ? $params['min_order'] : explode(',', $params['min_order']);
            $meta_query = array('relation' => 'OR');
            foreach ($min_orders as $min_order) {
                $meta_query[] = array(
                    'key' => 'plt_brand_min_order',
                    'value' => $min_order,
                    'compare' => '='
                );
            }
            $args['meta_query'][] = $meta_query;
        }

        // Фильтр по кастомизации
        if (!empty($params['customization'])) {
            $customizations = is_array($params['customization']) ? $params['customization'] : explode(',', $params['customization']);
            $meta_query = array('relation' => 'OR');
            foreach ($customizations as $customization) {
                $meta_query[] = array(
                    'key' => 'plt_brand_customization',
                    'value' => $customization,
                    'compare' => 'LIKE'
                );
            }
            $args['meta_query'][] = $meta_query;
        }

        // Фильтр по наличию прайса
        if (!empty($params['has_price'])) {
            $args['meta_query'][] = array(
                'key' => 'plt_brand_has_price',
                'value' => 'yes',
                'compare' => '='
            );
        }

        // Фильтр по городу
        if (!empty($params['city'])) {
            $args['meta_query'][] = array(
                'key'     => 'plt_brand_address',
                'value'   => sanitize_text_field($params['city']),
                'compare' => 'LIKE',
            );
        }

        $query = new WP_Query($args);
        
        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('parts/loops/loop', 'brand');
            }
        }
        wp_reset_postdata();
        $brands_html = ob_get_clean();

        $response = array(
            'brands' => $brands_html,
            'found_posts' => $query->found_posts,
            'max_pages' => $query->max_num_pages,
        );

        return rest_ensure_response($response);
    }

}