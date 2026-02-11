<div class="discount-preview">

    <?php if ($caption = carbon_get_the_post_meta('plt_discount_caption')) : ?>
        <div class="discount-preview__caption">
            <?php echo $caption;?>
        </div>
    <?php endif;?>

    <div class="discount-preview__title">
        <?php the_title();?>
    </div>

    <div class="discount-preview__content">
        <div class="content">
            <?php the_content();?>
        </div>
    </div>
</div>