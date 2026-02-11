<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="dropdown catalog__ordering">
	<div id="catalog-ordering" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Сортировка 
	</div>	
	<div class="dropdown-menu" aria-labelledby="catalog-ordering">
		<ul>			
		<?php 
		foreach ( $catalog_orderby_options as $id => $name ) 
			echo sprintf('<li><a href="%s" class="%s">%s</a></li>',
						add_query_arg(['orderby' => $id, 'paged' => 1]),
						($orderby == $id) ? 'is-active' : '',
						esc_html( $name )
				);			
		?>
		</ul>
	</div>
</div>	