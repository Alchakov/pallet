<?php
/**
 * Rating and Reviews Functions
 * Функции для работы с рейтингом и отзывами брендов
 */

// Получить средний рейтинг бренда на основе отзывов
function plt_get_brand_rating($brand_id) {
    $args = array(
        'post_type' => 'review',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_plt_review_brand',
                'value' => '"post:' . $brand_id . '"',
                'compare' => 'LIKE'
            )
        )
    );
    
    $reviews = get_posts($args);
    
    if (empty($reviews)) {
        return array(
            'average' => 0,
            'count' => 0,
            'ratings' => array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0)
        );
    }
    
    $ratings = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
    $total = 0;
    $count = 0;
    
    foreach ($reviews as $review) {
        $rating = (int) carbon_get_post_meta($review->ID, 'plt_review_rating');
        if ($rating >= 1 && $rating <= 5) {
            $ratings[$rating]++;
            $total += $rating;
            $count++;
        }
    }
    
    $average = $count > 0 ? round($total / $count, 1) : 0;
    
    return array(
        'average' => $average,
        'count' => $count,
        'ratings' => $ratings,
        'total' => $total
    );
}

// Отобразить звезды рейтинга
function plt_display_rating_stars($rating, $show_number = true) {
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    $html = '<div class="plt-rating-stars">';
    
    for ($i = 0; $i < $full_stars; $i++) {
        $html .= '<span class="star star-full">★</span>';
    }
    
    if ($half_star) {
        $html .= '<span class="star star-half">★</span>';
    }
    
    for ($i = 0; $i < $empty_stars; $i++) {
        $html .= '<span class="star star-empty">☆</span>';
    }
    
    if ($show_number) {
        $html .= ' <span class="rating-number">' . number_format($rating, 1) . '</span>';
    }
    
    $html .= '</div>';
    
    return $html;
}

// Получить отзывы бренда
function plt_get_brand_reviews($brand_id, $limit = -1) {
    $args = array(
        'post_type' => 'review',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_plt_review_brand',
                'value' => '"post:' . $brand_id . '"',
                'compare' => 'LIKE'
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    return get_posts($args);
}

// Кэшировать рейтинг бренда в post meta
function plt_update_brand_rating_cache($brand_id) {
    $rating_data = plt_get_brand_rating($brand_id);
    
    update_post_meta($brand_id, '_plt_brand_rating_average', $rating_data['average']);
    update_post_meta($brand_id, '_plt_brand_rating_count', $rating_data['count']);
}

// Обновить кэш рейтинга при сохранении отзыва
add_action('save_post_review', function($post_id) {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }
    
    $brand = carbon_get_post_meta($post_id, 'plt_review_brand');
    
    if (!empty($brand) && is_array($brand)) {
        $brand_id = $brand[0]['id'];
        plt_update_brand_rating_cache($brand_id);
    }
}, 10, 1);
