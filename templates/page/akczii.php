<?php 
get_header(); 
the_post();
do_action('full_page_start');	
?>
<div class="node">
	<div class="node__header">								
		<?php the_title("<h1>", "</h1>");?>  		
	</div>	
	<?php
	$discount_all = get_posts( array(
		'numberposts' => -1,
		'post_type'   => 'discount',
			'meta_query' => array(
				array(
					'key' => '_plt_discount_end',
					'value' =>  current_time('Y-m-d'),
					'type' => 'DATE',
					'compare' => '>='
				),
				array(
					'key' => '_plt_discount_start',
					'value' =>  current_time('Y-m-d'),
					'type' => 'DATE',
					'compare' => '<='
				)
			)
	));
	
	if ($discount_all) : 
		?>		
		<ul class="discount-list flex-grid-1 flex-grid-md-2 flex-grid-lg-3">
			<?php 
			foreach( $discount_all as $discount) :
				setup_postdata( $GLOBALS['post'] = & $discount );
				echo "<li>";
					get_template_part("parts/loops/loop", "discount");
				echo "</li>";
			endforeach;
			wp_reset_postdata(); ?>
		</ul>
		<?php
	endif;
	
	$discount_products = new WP_Query(array(
		'posts_per_page'    => -1,
		'post_status'       => 'publish',
		'post_type'         => 'product',
		'meta_query'        => WC()->query->get_meta_query(),
		'orderby'           => 'menu_order',
		'order'             => 'ASC',
		'post__in'          => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
	));

	if ( $discount_products->have_posts() ) : 
		?>
		<h2 class="add-top-40">Товары по акции</h2>
		<div class="catalog">
		   <div class="catalog__grid nomargin">
			   <?php
			   woocommerce_product_loop_start();
			   while ( $discount_products->have_posts() ) : $discount_products->the_post();
				   do_action( 'woocommerce_shop_loop' );
				   echo '<li>';
					   wc_get_template_part( 'content', 'product' );
				   echo '</li>';
			   endwhile;
			   woocommerce_product_loop_end();
			   wp_reset_postdata();
			   ?>
		   </div>
	   </div>
		<?php 
	endif;
	?>
</div>

<?php
	do_action('full_page_end');
	get_footer();
?>