<?php
/**
 * Шаблон карточки производителя
 */
global $post;
$brand_id = $post->ID;
$logo = get_the_post_thumbnail($brand_id, 'medium');
$rating_data = plt_get_brand_rating($brand_id);
$rating = $rating_data['average'];
$reviews_count = $rating_data['count'];
$address = carbon_get_post_meta($brand_id, 'plt_brand_address');
$phone = carbon_get_post_meta($brand_id, 'plt_brand_phone');
$email = carbon_get_post_meta($brand_id, 'plt_brand_email');
$website = carbon_get_post_meta($brand_id, 'plt_brand_website');
$work_hours = carbon_get_post_meta($brand_id, 'plt_brand_work_hours');
$has_price = carbon_get_post_meta($brand_id, 'plt_brand_has_price');
?>

<div class="brand-card">
	<div class="brand-card__inner">
		<div class="brand-card__logo-wrapper">
			<?php if ($logo) : ?>
				<div class="brand-card__logo">
					<a href="<?php the_permalink(); ?>">
						<?php echo $logo; ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
		
		<div class="brand-card__content">
			<!-- Title -->
			<h3 class="brand-card__title">
				<a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			
			<!-- Badges -->
			<div class="brand-card__badges">
				<span class="brand-badge brand-badge--verified">проверенный поставщик</span>
				<span class="brand-badge brand-badge--recommended">рекомендуем</span>
			</div>

                    <!-- Brand Filters (Taxonomies) -->
        <?php
        $brand_filters = get_the_terms($brand_id, 'brand_filter');
        if ($brand_filters && !is_wp_error($brand_filters)) :
        ?>
            <div class="brand-card__filters">
                <?php foreach ($brand_filters as $filter) : ?>
                    <span class="brand-filter-badge"><?php echo esc_html($filter->name); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
			
			<!-- Rating -->
			<?php if ($rating) : ?>
				<div class="brand-card__rating">
					<?php
					$full_stars = floor($rating);
					$half_star = ($rating - $full_stars) >= 0.5;
					for ($i = 0; $i < $full_stars; $i++) {
						echo '<i class="fas fa-star"></i>';
					}
					if ($half_star) {
						echo '<i class="fas fa-star-half-alt"></i>';
					}
					$empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
					for ($i = 0; $i < $empty_stars; $i++) {
						echo '<i class="far fa-star"></i>';
					}
					?>
				</div>
			<?php endif; ?>
			
			<!-- Contact Info -->
			<div class="brand-card__info">
				<?php if ($address) : ?>
					<div class="brand-card__info-item">
						<i class="fas fa-map-marker-alt"></i>
						<span><?php echo esc_html($address); ?></span>
					</div>
				<?php endif; ?>
				
				<?php if ($phone) : ?>
					<div class="brand-card__info-item">
						<i class="fas fa-phone"></i>
						<span><?php echo esc_html($phone); ?></span>
					</div>
				<?php endif; ?>
				
				<?php if ($email) : ?>
					<div class="brand-card__info-item">
						<i class="fas fa-envelope"></i>
						<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
					</div>
				<?php endif; ?>
				
				<?php if ($website) : ?>
					<div class="brand-card__info-item">
						<i class="fas fa-globe"></i>
						<a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_html($website); ?></a>
					</div>
				<?php endif; ?>
				
				<?php if ($work_hours) : ?>
					<div class="brand-card__info-item">
						<i class="far fa-clock"></i>
						<span><?php echo esc_html($work_hours); ?></span>
					</div>
				<?php endif; ?>
			</div>
			
			<!-- Actions -->
			<div class="brand-card__actions">
				<button class="btn btn--yellow" data-toggle="modal" data-target="#modal-brand-apply">
					<span class="btn__text">Оставить заявку</span>
				</button>
				<button class="btn btn--yellow" data-toggle="modal" data-target="#modal-brand-price">
					<span class="btn__text">Заказать прайс-лист</span>
				</button>
				<a href="#brand-map" class="btn btn--outline">
					<span class="btn__text">Показать на карте</span>
				</a>
			</div>
		</div>
	</div>
</div>
