<?php
/**
 * Product Category Content
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!$term)
	$term = get_queried_object();

$gallery = carbon_get_term_meta($term->term_id, 'plt_product_cat_gallery');
$benefits_active = carbon_get_term_meta($term->term_id, 'plt_product_cat_benefits_active');
$benefits = carbon_get_term_meta($term->term_id, 'plt_product_cat_benefits_list');
$benefits_title = carbon_get_term_meta($term->term_id, 'plt_product_cat_benefits_title');

if (!$term->description && !$gallery && !$benefits_active) return;
?>

<div class="catalog__content <?php echo ($gallery) ? 'catalog__content--short' : '';?>">
	<div class="row">
		<div class="col-md-<?php echo ($gallery) ? 7 : 12;?>">
			<div class="content">	
				<?php 
				echo wc_format_content( $term->description );
				if ($benefits_active)
					plt_site()->tpl('/parts/box-benefits', ['title' => $benefits_title, 'list' => $benefits]);	
				?>
			</div>
		</div>
		
		<?php if ($gallery): ?>
			<div class="col-md-5">
				<div class="catalog__content-gallery">
					<?php 
					foreach ($gallery as $item): 
						echo sprintf('<figure>%s%s</figure>', 
									wp_get_attachment_image($item['image'], 'medium'),
									$item['title'] ? '<figcaption>'.$item["title"].'</figcaption>' : ''
								);
					endforeach; 
					?>
				</div>
			</div>
		<?php endif; ?>
				
	</div>	
</div>