<?php
/**
 * Шаблон архива производителей
 */
defined('ABSPATH') || exit;

get_header();
do_action('full_page_start');
?>

<div class="node brands-archive">
    <div class="node__header">
        <h1>
            <?php
            if (is_tax('brand_category')) {
                $term = get_queried_object();
                echo 'Производители ' . esc_html($term->name) . ' в России';
            } else {
                echo 'Производители канистр в России';
            }
            ?>
        </h1>
    </div>

    <?php if (is_tax('brand_category')) : ?>
        <div class="content add-bottom-20">
            <?php echo term_description(); ?>
        </div>
    <?php endif; ?>

    <div class="brands-archive__content">
        <div class="row">
            <!-- Левая колонка - Фильтры -->
            <div class="col-md-3 brands-archive__sidebar" id="brands-filters">
                <div class="brands-filters">
                    <!-- Навигация по категориям -->
                    <div class="brands-filters__section">
                        <h3 class="brands-filters__title">Навигация</h3>
                        <ul class="brands-filters__nav">
                            <li><a href="<?php echo get_post_type_archive_link('brand'); ?>">Все категории</a></li>
                            <?php
                            $brand_categories = get_terms(array(
                                'taxonomy'   => 'brand_category',
                                'hide_empty' => false,
                            ));
                            foreach ($brand_categories as $category) :
                                $is_active = is_tax('brand_category', $category->term_id);
                                ?>
                                <li class="<?php echo $is_active ? 'active' : ''; ?>">
                                    <a href="<?php echo get_term_link($category); ?>">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <?php
                        // Картинка-ссылка на карту
                        $map_img = plt_site()->get_img_url('map_brand.jpg');
                        ?>
                        <div class="brands-map-thumb">
                            <a href="#brands-map">
                                <img src="<?php echo esc_url($map_img); ?>" alt="Производители на карте">
                            </a>
                        </div>
                    </div>

                    <!-- Фильтры -->
                    <div class="brands-filters__section">
                        <h3 class="brands-filters__title">Фильтры</h3>
                        
                        <!-- Минимальная поставка -->
                        <div class="brands-filters__group">
                            <h4>Минимальная поставка</h4>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="min_order[]" value="retail">
                                <span>Розница</span>
                            </label>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="min_order[]" value="small_wholesale">
                                <span>Мелкий опт</span>
                            </label>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="min_order[]" value="wholesale">
                                <span>Опт</span>
                            </label>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="min_order[]" value="large_wholesale">
                                <span>Крупный опт</span>
                            </label>
                        </div>

                        <!-- Кастомизация -->
                        <div class="brands-filters__group">
                            <h4>Кастомизация</h4>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="customization[]" value="color">
                                <span>Цвет на заказ</span>
                            </label>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="customization[]" value="size">
                                <span>Размер на заказ</span>
                            </label>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="customization[]" value="logo">
                                <span>Логотип на заказ</span>
                            </label>
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="customization[]" value="packaging">
                                <span>Упаковка на заказ</span>
                            </label>
                        </div>

                        <!-- Дополнительные фильтры -->
                        <div class="brands-filters__group">
                            <label class="brands-filters__checkbox">
                                <input type="checkbox" name="has_price" value="1">
                                <span>Есть прайс</span>
                            </label>
                        </div>

                        <button type="button" class="btn btn--transparent brands-filters__reset" id="reset-filters">
                            <span class="btn__inner">
                                <span class="btn__text">Сбросить все</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Центральная колонка - Список производителей -->
            <div class="col-md-9 brands-archive__main">

                <!-- Панель с выбором региона/города и видом списка -->
                <div class="brands-results">
                    <div class="brands-results__header">
                        <div class="brands-results__filters">
                            <select id="brands-region-select" class="form-control" style="display:inline-block; width:48%; max-width:220px;">
                                <option value="">Регион</option>
                                <?php
                                $regions = get_terms(array(
                                    'taxonomy'   => 'region',
                                    'hide_empty' => false,
                                ));
                                foreach ($regions as $region) :
                                    ?>
                                    <option value="<?php echo esc_attr($region->term_id); ?>">
                                        <?php echo esc_html($region->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text"
                                   id="brands-city-input"
                                   class="form-control"
                                   placeholder="Населённый пункт"
                                   style="display:inline-block; width:48%; max-width:220px; margin-left:4%;">
                            <a href="#brands-map" class="btn btn--yellow" style="margin-left:10px;">
                                <span class="btn__inner"><span class="btn__text">Показать на карте</span></span>
                            </a>
                        </div>
                        <div class="brands-results__view">
                            <button class="brands-view-btn active" data-view="grid" id="view-grid">
                                <span class="icon-grid"></span>
                            </button>
                            <button class="brands-view-btn" data-view="list" id="view-list">
                                <span class="icon-list"></span>
                            </button>
                        </div>
                    </div>

                    <span class="brands-results__count" id="brands-count">
                        Найдено <?php echo $wp_query->found_posts; ?> производителей
                    </span>

                    <div class="brands-list" id="brands-list" data-view="grid">
                        <?php
                        if (have_posts()) :
                            $brand_index = 0;
                            while (have_posts()) : the_post();
                                $brand_index++;
                                ?>
                                <div class="brand-item">
                                    <?php get_template_part('parts/loops/loop', 'brand'); ?>
                                </div>
                                <?php
                                // Форма заявки под третьим брендом только на первой странице архива
                                if ($brand_index === 3 && !is_paged()) :
                                    ?>
                                    <div class="brand-item brand-item--form">
                                        <div class="brand-card">
                                            <div class="brand-card__inner">
                                                <?php get_template_part('parts/forms/form', 'apply'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                            endwhile;
                        else :
                            ?>
                            <p>Производители не найдены</p>
                        <?php endif; ?>
                    </div>

                    <!-- Пагинация -->
                    <?php
                    if (function_exists('plt_wc_catalog')) {
                        echo PLT_Content::pagination();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Карта производителей -->
    <div class="brands-map-container" id="brands-map-container">
        <h2>Производители на карте</h2>
        <div id="brands-map" style="width: 100%; height: 600px;"></div>
    </div>
</div>

<?php
do_action('full_page_end');
get_footer();
?>
