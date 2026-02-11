<?php 

if (!carbon_get_theme_option('plt_front_catalog_active')) return; 

$title = carbon_get_theme_option('plt_front_catalog_title');
$items = carbon_get_theme_option('plt_front_catalog_items');

if (empty($items)) return;
?>

<section class="front-catalog" data-aos="fade-in">
	<div class="container">
		<?php 
		if ($title) echo "<h2>{$title}</h2>";
		
		woocommerce_product_loop_start();
		
		foreach ( $items as $item ) :	
			$post_object = get_post($item['id']);					
			setup_postdata( $GLOBALS['post'] =& $post_object );	
			do_action( 'woocommerce_shop_loop' );
			echo '<li>';
				wc_get_template_part( 'content', 'product' );
			echo '</li>';
		endforeach; 

		woocommerce_product_loop_end();
		
		wp_reset_postdata();
		?>
		
		<div class="front-btn">
			<a href="<?php the_permalink(131);?>" class="btn btn--transparent btn--no-shadow"><span class="btn__inner"><span class="btn__text link-arrow">Перейти в каталог</span></span></a>
			<p>или получить <a href="#" data-toggle="modal" data-target="#modal-consalt">бесплатную консультацию</a></p>
		</div>
	</div>
</section>
