<?php 
// Получаем входные данные
$image = !empty($image) && is_numeric($image) ? wp_get_attachment_url($image, 'large') : $image;
$hover = !empty($hover) && is_numeric($hover) ? wp_get_attachment_url($hover, 'large') : $hover;
$video_url = !empty($video_url) ? $video_url : '';
$embed = !empty($embed) ? $embed : '';
$type = !empty($type) ? $type : 'video';

// Определяем основное изображение для превью
$front_image = $image;
$video_link = '';

// Для YouTube
if ($type == 'video' && !empty($embed)) {
    $url = parse_url($embed);
    parse_str($url['query'] ?? '', $query);
    if (!empty($query['v'])) {
        $youtube_img = 'https://img.youtube.com/vi/'.$query['v'].'/0.jpg';
        $video_link = sprintf('https://www.youtube.com/embed/%s?autoplay=1&rel=0', $query['v']);
        $front_image = $image ?: $youtube_img;
    } else {
        $video_link = $embed;
    }
} 
// Для видео-файла
elseif ($type == 'video_file' && !empty($video_url)) {
    // Проверяем, является ли ссылка ID медиафайла
    if (is_numeric($video_url)) {
        $video_url = wp_get_attachment_url((int)$video_url);
    }
    $video_link = $video_url;
    $front_image = $image;
}
?>

<div class="video-preview">
    <figure>
        <?php if($type == 'video' && !empty($video_link)): ?>
            <!-- YouTube видео - открывается в fancybox iframe -->
            <a href="<?php echo esc_url($video_link); ?>" 
               class="fancybox video-preview__preview" 
               data-fancybox-type="iframe" 
               tabindex="0">
                <span class="image-responsive image-responsive--16by9">
                    <span>
                        <?php if(!empty($hover)): ?>
                            <img src="<?php echo esc_url($hover); ?>" class="video-preview__back" alt="">
                        <?php endif; ?>
                        <?php if(!empty($front_image)): ?>
                            <img src="<?php echo esc_url($front_image); ?>" class="video-preview__front" alt="">
                        <?php endif; ?>
                    </span>
                </span>
                <span class="video-preview__play-icon"></span>
            </a>
            
        <?php elseif($type == 'video_file' && !empty($video_link)): ?>
            <!-- Видео-файл - открывается в fancybox как видео -->
			   <a href="<?php echo esc_url($video_link); ?>" 
               class="fancybox video-preview__preview" 
               data-fancybox-type="iframe" 
               tabindex="0">
                <span class="image-responsive image-responsive--16by9">
                    <span>
                        <?php if(!empty($hover)): ?>
                            <img src="<?php echo esc_url($hover); ?>" class="video-preview__back" alt="">
                        <?php endif; ?>
                        <?php if(!empty($front_image)): ?>
                            <img src="<?php echo esc_url($front_image); ?>" class="video-preview__front" alt="">
                        <?php endif; ?>
                    </span>
                </span>
                <span class="video-preview__play-icon"></span>
            </a>
        <?php elseif(!empty($front_image)): ?>
            <!-- Только изображение, если нет видео -->
            <span class="image-responsive image-responsive--16by9">
                <span>
                    <img src="<?php echo esc_url($front_image); ?>" class="video-preview__front" alt="">
                </span>
            </span>
        <?php endif; ?>
        
        <?php if (isset($title) && $title != ''): ?>
            <figcaption><?php echo esc_html($title); ?></figcaption>
        <?php endif; ?>
    </figure>
</div>

<?php if($type == 'video_file'): ?>

<?php endif; ?>