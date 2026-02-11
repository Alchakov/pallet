<?php 

if (!carbon_get_theme_option('plt_front_clients_active')) return; 

$title = carbon_get_theme_option('plt_front_clients_title');
$items = carbon_get_theme_option('plt_front_clients_items');

if (empty($items)) return;
?>

<section class="front-clients" data-aos="fade-in">
	<div class="container">		
		<div class="front-clients__inner">
			<div class="title-slider-arrow">
				<?php if ($title) echo "<h2>{$title}</h2>";?>
				<div id="front-clients__arrow" class="slider-arrow-outside"></div>
			</div>
			<div class="slider-4 slider-content slider-content--full" data-arrow-place="front-clients__arrow">
				<?php 
				foreach ($items as $item) :	
					$post_object = get_post($item['id']);					
					setup_postdata( $GLOBALS['post'] =& $post_object );	
					echo "<div>";
						get_template_part('parts/loops/loop', 'client');
					echo "</div>";
				endforeach;			
				wp_reset_postdata();
				?>	
			</div>
			<div class="front-btn">
				<a href="<?php echo get_post_type_archive_link('client');?>" class="btn btn--transparent btn--no-shadow"><span class="btn__inner"><span class="btn__text link-arrow">Все клиенты</span></span></a>
				<p>или <a href="#" data-toggle="modal" data-target="#modal-client">стать нашим клиентом</a></p>
			</div>
		</div>	
	</div>
</section>
