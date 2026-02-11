<?php 
// Получаем входные данные
$image = !empty($image) && is_numeric($image) ? wp_get_attachment_url($image, 'large') : $image;
$hover = !empty($hover) && is_numeric($hover) ? wp_get_attachment_url($hover, 'large') : $hover;

$link = '';
$youtube_img = '';
$front = $image;

// --- YouTube ---
if (!empty($embed)) {
    $url = parse_url($embed);
    parse_str($url['query'] ?? '', $query);
    if (!empty($query['v'])) {
        $youtube_img = 'https://img.youtube.com/vi/'.$query['v'].'/0.jpg';
        $link = sprintf('https://www.youtube.com/embed/%s?autoplay=1&rel=0', $query['v']);
        $front = $image ?: $youtube_img;
    } else {
        $link = $embed;
    }

// --- Видео по ссылке (mp4) ---
} elseif (!empty($link) && isset($type) && $type === 'video_file') {
    if (!empty($link) && is_numeric($link)) {
        $link = wp_get_attachment_url((int)$link);
    }
    $front = $image;
} elseif (!empty($link) && isset($type) && $type === 'video_file') {
    $front = $image;
}

// Если передали mp4 в $data['link']
if (!empty($link)) {
    $link = $link;
} elseif (!empty($data['link'])) {
    $link = $data['link'];
}

?>
<div class="video-preview">
    <figure>
        <?php if(!empty($link)): ?>
        <a href="<?php echo esc_url($link);?>" class="fancybox video-preview__preview" data-fancybox-type="iframe" tabindex="0">
        <?php endif; ?>
            <span class="image-responsive image-responsive--16by9">
                <span>
                    <?php
                    if ($hover)
                        echo '<img src="'.esc_url($hover).'" class="video-preview__back" alt="">';

                    if ($front)
                        echo '<img src="'.esc_url($front).'" class="video-preview__front" alt="">';
                    ?>
                </span>
            </span>

            <?php
            if (isset($type) && $type === 'video_file') {
                echo '<span class="video-preview__play-icon"></span>';
            }
            ?>

        <?php if(!empty($link)): ?>
        </a>
        <?php endif; ?>

        <?php if (isset($title) && $title != '') printf('<figcaption>%s</figcaption>', esc_html($title)); ?>
    </figure>
</div>
