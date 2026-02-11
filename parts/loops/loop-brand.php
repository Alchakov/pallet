<?php
/**
 * Шаблон карточки производителя
 */
global $post;
$brand_id = $post->ID;
$logo = get_the_post_thumbnail($brand_id, 'medium');
$rating = carbon_get_post_meta($brand_id, 'plt_brand_rating');
$reviews_count = carbon_get_post_meta($brand_id, 'plt_brand_reviews_count');
$address = carbon_get_post_meta($brand_id, 'plt_brand_address');
$city = carbon_get_post_meta($brand_id, 'plt_brand_city');
$has_price = carbon_get_post_meta($brand_id, 'plt_brand_has_price');
$region_terms = get_the_terms($brand_id, 'region');
?>

<div class="brand-card">
    <div class="brand-card__inner">
        <div class="brand-card__header">
            <?php if ($logo) : ?>
                <div class="brand-card__logo">
                    <a href="<?php the_permalink(); ?>">
                        <?php echo $logo; ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="brand-card__title">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </div>
        </div>
        
        <div class="brand-card__description">
            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
        </div>

        <div class="brand-card__info">
            <?php if ($city || ($region_terms && !is_wp_error($region_terms))) : ?>
                <div class="brand-card__location">
                    <?php
                    if ($city) {
                        echo esc_html($city);
                    }
                    if ($region_terms && !is_wp_error($region_terms)) {
                        if ($city) echo ', ';
                        echo esc_html($region_terms[0]->name);
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if ($address) : ?>
                <div class="brand-card__address">
                    <?php echo esc_html($address); ?>
                </div>
            <?php endif; ?>

            <?php if ($rating) : ?>
                <div class="brand-card__rating">
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
                    <span><?php echo $rating; ?> (<?php echo $reviews_count; ?>)</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="brand-card__actions">
            <?php if ($has_price) : ?>
                <span class="brand-badge">Есть прайс</span>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" class="btn btn--yellow btn--small">
                <span class="btn__inner">
                    <span class="btn__text">Подробнее</span>
                </span>
            </a>
        </div>
    </div>
</div>
