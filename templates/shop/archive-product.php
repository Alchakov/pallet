<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */
defined('ABSPATH') || exit;
$term_ID = get_queried_object()->term_id;
get_header('shop');
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content) - REMOVED
 * @hooked woocommerce_breadcrumb - 20 - REMOVED
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');
?>
<div class="node catalog">
    <div class="node__header">
        <h1><?php woocommerce_page_title(); ?></h1>
        <?php
        if (carbon_get_term_meta($term_ID, 'plt_product_cat_title_button') == 1) {
            $btn_text = carbon_get_term_meta($term_ID, 'plt_product_cat_title_button_text');
            echo sprintf(
                '<a href="#" class="btn" data-toggle="modal" data-target="#modal-product-cat-title-button" data-modal-title="%s"><span class="btn__inner"><span class="btn__text">%s</span></span></a>',
                esc_attr($btn_text),
                $btn_text
            );
        }
        ?>
    </div>
    <?php
    if (woocommerce_product_loop()) {
        /**
         * Hook: woocommerce_before_shop_loop.
         *
         * @hooked woocommerce_output_all_notices - 10
         * @hooked woocommerce_result_count - 20 - REMOVED
         * @hooked woocommerce_catalog_ordering - 30 - REMOVED
         */
        do_action('woocommerce_before_shop_loop');
        ?>
        <div class="catalog__manage"> <div>
                    <!-- Под заголовком -->
                    <?php
                    $custom_field_one = get_term_meta($term_ID, 'custom_field_one', true);
                    if (!empty($custom_field_one)) {
                        echo '<div class="custom-field-one content">' . wpautop(do_shortcode($custom_field_one)) . '</div>';
                    }
                    ?>
                </div><br>
            <div class="row">
               
                <div class="col-md-9 col-xl-8">
                    <?php plt_wc_catalog()->sub_cat(); ?>
                </div>
                <div class="col-md-3 offset-xl-1">
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
                <div class="col">
                    <div class="filter-container">
                        <button class="filter-button" id="filterToggle">
                            <span class="filter-icon"><i class="fa fa-sliders" aria-hidden="true"></i></span> Фильтр
                        </button>
                        <div class="filter-content" id="filterContent" style="display: none;">
                            <?php echo do_shortcode('[wpf-filters id=1]'); ?>
                        </div>
                    </div>
                </div>
                
            </div><div>
                    <!-- Под фильтром -->
                    <?php
                    $custom_field_two = get_term_meta($term_ID, 'custom_field_two', true);
                    if (!empty($custom_field_two)) {
                        echo '<div class="custom-field-two content">' . wpautop(do_shortcode($custom_field_two)) . '</div>';
                    }
                    ?>
                </div>
        </div>

        <div class="catalog__grid">
            <?php
            woocommerce_product_loop_start();
            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();
                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action('woocommerce_shop_loop');
                    echo '<li>';
                    wc_get_template_part('content', 'product');
                    echo '</li>';
                }
            }
            woocommerce_product_loop_end();
            /**
             * Hook: woocommerce_after_shop_loop.
             *
             * @hooked woocommerce_pagination - 10 - REMOVED
             * @hooked PLT_WC_Catalog->pagination() - 10 - ADD
             */
            do_action('woocommerce_after_shop_loop');
        } else {
            /**
             * Hook: woocommerce_no_products_found.
             *
             * @hooked wc_no_products_found - 10
             */
            do_action('woocommerce_no_products_found');
        }
            ?>
        </div>
    </div>

    <?php
    /**
     * Hook: pallet_product_cat_node_bottom.
     *
     * @hooked PLT_WC_Catalog->advantages() - 10
     * @hooked PLT_WC_Catalog->content() - 20
     */
    do_action('pallet_product_cat_node_bottom');
    ?>
</div>
<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

get_footer('shop');