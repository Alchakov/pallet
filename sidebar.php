<?php 
$is_blog = (is_home() || is_singular('post') || is_category()); 
?>
<aside class="sidebar" role="complementary">
		
	<?php if ($is_blog) : ?>
		<?php 			
			$args = array(
				'title_li'           => __(''),	
				'depth'           => 1,		
				'use_desc_for_title' => 0,	
			);
		?>		
		<div class="sidebar-item sidebar-item--blog">
			<div class="d-none d-lg-block sidebar-item__title">Разделы</div>
			<div class="d-lg-none">
				<a href="#sidebar-menu-blog" class="toggle-btn toggle-btn--arrow toggle-btn--changeable">
					<span class="toggle-btn__inner">
						<span class="toggle-btn__text" data-active="Скрыть">Разделы</span>
					</span>
				</a>
			</div>			
			<ul id="sidebar-menu-blog" class="sidebar-menu">				
				<?php wp_list_categories( $args );?>
			</ul>
		</div>
		
	<?php endif;?>
			
	<div class="sidebar-item sidebar-item--review d-none d-lg-block">
		<div class="sidebar-item__title">Нас благодарят</div>
		<a href="/otzyvy/" class="btn btn--high"><span class="btn__inner"><span class="btn__text">Читайте отзывы о&nbsp;нашей работе</span></span></a>
	</div>	
	
	<?php
		if ( is_active_sidebar( 'main_sidebar' ) ) 
			dynamic_sidebar( 'main_sidebar' ); 	  
	?>
	
	<div class="sidebar-sticky">
		<div class="sidebar-item sidebar-item--consalt d-none d-lg-block">
			<div class="sidebar-item__title">Бесплатная консультация</div>		
			<?php get_template_part('parts/forms/form', 'sidebar');?>	
		</div>
	</div>	
	
</aside>