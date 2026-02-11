<?php
/**
 * Show sub category
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="catalog__subcat">
	<div class="catalog__subcat-inner">
	<?php	
	echo '<ul>';	
	foreach ($row_1 as $subcat)
		printf('<li><a href="%s">%s</a></li>', 
				get_term_link( $subcat->slug, 'product_cat' ),
				($title = carbon_get_term_meta( $subcat->term_id, 'plt_product_cat_title_short' ) ) ? $title : $subcat->name
			);
	if (!empty($row_2)) 
		echo '
		<li class="catalog__subcat-toggle">
			<a href="#catalog-subcat-row-2" class="toggle-btn toggle-btn--arrow toggle-btn--changeable">
				<span class="toggle-btn__inner">
					<span class="toggle-btn__text" data-active="Скрыть">Больше</span>
				</span>
			</a>
		</li>';
	echo '</ul>';	
	
	if (!empty($row_2)) :
		echo '<div class="toggle-info" id="catalog-subcat-row-2"><ul>';
		foreach ($row_2 as $subcat)
			printf('<li><a href="%s">%s</a></li>', 
					get_term_link( $subcat->slug, 'product_cat' ),
					($title = carbon_get_term_meta( $subcat->term_id, 'plt_product_cat_title_short' ) ) ? $title : $subcat->name
				);
		echo '</ul></div>';
	endif;
	?>
	</div>
</div>

