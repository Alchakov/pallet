<?php
/**
 * Шаблон страницы производителя
 */
defined('ABSPATH') || exit;

get_header();
do_action('full_page_start');

global $post;
$brand_id = $post->ID;
?>
<script>
document.body.setAttribute('data-brand-id', <?php echo $brand_id; ?>);
</script>

<div class="node brand-single">
    <div class="brand-single__header">
        <div class="brand-single__logo">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php endif; ?>
        </div>
        <div class="brand-single__info">
            <h1><?php the_title(); ?></h1>
            <?php
		<?php
		$rating_data = plt_get_brand_rating($brand_id);
		$rating = $rating_data['average'];
		$reviews_count = $rating_data['count'];
		if ($rating) :                ?>
                <div class="brand-rating">
                    <?php
                    $full_stars = floor($rating);
                    $half_star = ($rating - $full_stars) >= 0.5;
                    for ($i = 0; $i < $full_stars; $i++) {
                        echo '<span class="star star-full">★</span>';
                    }
                    if ($half_star) {
                        echo '<span class="star star-half">★</span>';
                    }
                    ?>
                    <span class="rating-text"><?php echo $rating; ?> (<?php echo $reviews_count; ?> отзывов)</span>
                </div>
            <?php endif; ?>

			                <!-- Brand Filters (Taxonomies) -->
                <?php
                $brand_filters = get_the_terms($brand_id, 'brand_feature');                if ($brand_filters && !is_wp_error($brand_filters)) :
                ?>
                    <div class="brand-single__filters">
                        <?php foreach ($brand_filters as $filter) : ?>
                            <span class="brand-filter-badge"><?php echo esc_html($filter->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            
            <div class="brand-contacts">
                <?php
                $phone = carbon_get_post_meta($brand_id, 'plt_brand_phone');
                $email = carbon_get_post_meta($brand_id, 'plt_brand_email');
                $website = carbon_get_post_meta($brand_id, 'plt_brand_website');
                $address = carbon_get_post_meta($brand_id, 'plt_brand_address');
                
                if ($phone) echo '<div class="brand-contact-item"><strong>Телефон:</strong> ' . esc_html($phone) . '</div>';
                if ($email) echo '<div class="brand-contact-item"><strong>Email:</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></div>';
                if ($website) echo '<div class="brand-contact-item"><strong>Сайт:</strong> <a href="' . esc_url($website) . '" target="_blank">' . esc_html($website) . '</a></div>';
                if ($address) echo '<div class="brand-contact-item"><strong>Адрес:</strong> ' . esc_html($address) . '</div>';
                ?>
            </div>

            <div class="brand-actions">
                <button class="btn btn--yellow" data-toggle="modal" data-target="#modal-brand-apply">
                    <span class="btn__inner">
                        <span class="btn__text">Отправить заявку</span>
                    </span>
                </button>
                <a href="#brand-details" class="btn btn--transparent">
                    <span class="btn__inner">
                        <span class="btn__text">Подробнее</span>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <?php if (get_the_content()) : ?>
        <div class="brand-description">
            <?php the_content(); ?>
        </div>
    <?php endif; ?>

    <!-- Навигация по табам -->
    <div class="brand-tabs">
        <ul class="brand-tabs__nav">
            <li class="active"><a href="#about">О компании</a></li>
            <li><a href="#products">Товары</a></li>
            <li><a href="#reviews">Отзывы</a></li>
            <li><a href="#contacts">Контакты</a></li>
            <li><a href="#geography">География</a></li>
            <li><a href="#certificates">Сертификаты</a></li>
             <li><a href="#filters">Фильтры</a></li>
        </ul>
    </div>

    <!-- Таб: О компании -->
    <div id="about" class="brand-tab-content active">
        <div class="brand-details" id="brand-details">
            <h2>О производителе</h2>
            <div class="brand-details__grid">
                <?php
                $region_terms = get_the_terms($brand_id, 'region');
                $city = carbon_get_post_meta($brand_id, 'plt_brand_city');
                $work_hours = carbon_get_post_meta($brand_id, 'plt_brand_work_hours');
                $experience = carbon_get_post_meta($brand_id, 'plt_brand_experience');
                $payment = carbon_get_post_meta($brand_id, 'plt_brand_payment');
                ?>
                <?php if ($region_terms && !is_wp_error($region_terms)) : ?>
                    <div class="brand-detail-item">
                        <strong>Регион:</strong> <?php echo esc_html($region_terms[0]->name); ?>
                    </div>
                <?php endif; ?>
                <?php if ($city) : ?>
                    <div class="brand-detail-item">
                        <strong>Город:</strong> <?php echo esc_html($city); ?>
                    </div>
                <?php endif; ?>
                <?php if ($address) : ?>
                    <div class="brand-detail-item">
                        <strong>Адрес:</strong> <?php echo esc_html($address); ?>
                    </div>
                <?php endif; ?>
                <?php if ($phone) : ?>
                    <div class="brand-detail-item">
                        <strong>Телефон:</strong> <?php echo esc_html($phone); ?>
                    </div>
                <?php endif; ?>
                <?php if ($website) : ?>
                    <div class="brand-detail-item">
                        <strong>Сайт:</strong> <a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a>
                    </div>
                <?php endif; ?>
                <?php if ($email) : ?>
                    <div class="brand-detail-item">
                        <strong>Email:</strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                    </div>
                <?php endif; ?>
                <?php if ($work_hours) : ?>
                    <div class="brand-detail-item">
                        <strong>График работы:</strong> <?php echo esc_html($work_hours); ?>
                    </div>
                <?php endif; ?>
                <?php if ($experience) : ?>
                    <div class="brand-detail-item">
                        <strong>Стаж работы:</strong> <?php echo esc_html($experience); ?>
                    </div>
                <?php endif; ?>
                <?php if ($payment) : ?>
                    <div class="brand-detail-item">
                        <strong>Форма оплаты:</strong> <?php echo esc_html($payment); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (get_the_content()) : ?>
                <div class="brand-description-full">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Таб: Товары -->
    <div id="products" class="brand-tab-content">
        <h2>Товары производителя</h2>
        <?php
        // Получаем товары этого производителя
        // Связь через таксономию product_brand, где slug соответствует slug бренда
        $brand_slug = $post->post_name;
        $brand_term = get_term_by('slug', $brand_slug, 'product_brand');
        
        if ($brand_term && !is_wp_error($brand_term)) {
            $products = get_posts(array(
                'post_type' => 'product',
                'posts_per_page' => 6,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_brand',
                        'field' => 'term_id',
                        'terms' => $brand_term->term_id,
                    ),
                ),
            ));
        } else {
            $products = array();
        }
        
        if ($products) :
            ?>
            <div class="brand-products">
                <ul class="products">
                    <?php
                    global $post;
                    foreach ($products as $product_post) {
                        setup_postdata($product_post);
                        echo '<li>';
                        wc_get_template_part('content', 'product');
                        echo '</li>';
                    }
                    wp_reset_postdata();
                    ?>
                </ul>
                <a href="<?php echo esc_url(add_query_arg('brand', $brand_slug, wc_get_page_permalink('shop'))); ?>" class="btn btn--yellow">
                    <span class="btn__inner">
                        <span class="btn__text">Показать еще</span>
                    </span>
                </a>
            </div>
        <?php else : ?>
            <p>Товары не найдены</p>
        <?php endif; ?>
    </div>

    <!-- Таб: Отзывы -->
    <div id="reviews" class="brand-tab-content">
        <h2>Отзывы о бренде</h2>
        <!-- Здесь будет форма добавления отзыва и список отзывов -->
        <div class="brand-reviews">
		<?php
		// Получаем данные о рейтинге
		$rating_data = plt_get_brand_rating($brand_id);
		
		// Получаем отзывы
		$reviews = plt_get_brand_reviews($brand_id);
		?>
		
		<?php if ($rating_data['count'] > 0) : ?>
			<div class="brand-rating-summary">
				<div class="rating-overview">
					<div class="rating-number"><?php echo $rating_data['average']; ?></div>
					<div class="rating-stars">
						<?php echo plt_display_rating_stars($rating_data['average'], false); ?>
					</div>
					<div class="rating-count">На основе <?php echo $rating_data['count']; ?> отзывов</div>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if (!empty($reviews)) : ?>
			<div class="reviews-list">
				<?php foreach ($reviews as $review) : ?>
					<?php
					$review_type = carbon_get_post_meta($review->ID, 'plt_review_type');
					get_template_part('parts/loops/loop-review-' . $review_type, null, array('review_id' => $review->ID));
					?>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p>Отзывов пока нет. Будьте первым!</p>
		<?php endif; ?>        </div>
    </div>

    <!-- Таб: Контакты -->
    <div id="contacts" class="brand-tab-content">
        <h2>Контакты</h2>
        <div class="brand-contact-form">
            <h3>Написать производителю</h3>
            <!-- Форма будет здесь -->
        </div>
    </div>

    <!-- Таб: География -->
    <div id="geography" class="brand-tab-content">
        <h2>География</h2>
        <div id="brand-map" style="width: 100%; height: 500px;"></div>
    </div>

    <!-- Таб: Сертификаты -->
    <div id="certificates" class="brand-tab-content">
        <h2>Сертификаты</h2>
        <?php
        $certificates = carbon_get_post_meta($brand_id, 'plt_brand_certificates');
        if ($certificates) :
            ?>
            <div class="brand-certificates">
                <?php foreach ($certificates as $cert) : ?>
                    <div class="brand-certificate">
                        <a href="<?php echo wp_get_attachment_image_url($cert, 'full'); ?>" class="fancybox">
                            <?php echo wp_get_attachment_image($cert, 'medium'); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Таб: Фильтры -->
 <div id="filters" class="brand-tab-content">
   <h2>Фильтры бренда</h2>
   <?php		<?php
		$brand_filters = get_the_terms($brand_id, 'brand_feature');
		if ($brand_filters && !is_wp_error($brand_filters)) :
		?>
			<div class="brand-filters-list">
				<?php foreach ($brand_filters as $filter) : ?>
					<div class="brand-filter-item">
						<i class="fas fa-check-circle"></i>
						<span><?php echo esc_html($filter->name); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?> else : ?>
     <p>Фильтры не определены</p>
   <?php endif; ?>
 </div>
    </div>

    <!-- Фотогалерея -->
    <?php
    $gallery = carbon_get_post_meta($brand_id, 'plt_brand_gallery');
    if ($gallery) :
        ?>
        <div class="brand-gallery">
            <h2>Фотографии</h2>
            <div class="brand-gallery__grid">
                <?php foreach ($gallery as $img_id) : ?>
                    <div class="brand-gallery__item">
                        <a href="<?php echo wp_get_attachment_image_url($img_id, 'full'); ?>" class="fancybox">
                            <?php echo wp_get_attachment_image($img_id, 'medium'); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Другие производители -->
    <div class="brand-related">
        <h2>Другие производители</h2>
        <?php
        $related_brands = get_posts(array(
            'post_type' => 'brand',
            'posts_per_page' => 4,
            'post__not_in' => array($brand_id),
            'orderby' => 'rand',
        ));
        
        if ($related_brands) :
            ?>
            <div class="brands-list">
                <?php
                foreach ($related_brands as $related_brand) {
                    setup_postdata($related_brand);
                    get_template_part('parts/loops/loop', 'brand');
                }
                wp_reset_postdata();
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
do_action('full_page_end');
get_footer();
?>
