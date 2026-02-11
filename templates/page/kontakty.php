<?php 
get_header(); 
the_post();
do_action('full_page_start');	
?>
<div class="node">
	<div class="node__header">								
		<?php the_title("<h1>", "</h1>");?>  		
	</div>
	<div>
		<div class="catalog__subcat">
			<div class="catalog__subcat-inner">
				<ul>
					<li><a href="/shema-proezda/">Схема проезда</a></li>
					<li><a href="/o-kompanii/">О компании</a></li>
				</ul>	
			</div>
		</div>

    </div>
	<?php 
	if ($offices = get_posts(['numberposts' => -1,'post_type' => 'office'])) :
		?>
		<ul class="flex-grid-1 flex-grid-sm-2 flex-grid-lg-3">
			<?php 
			foreach( $offices as $office) :
                setup_postdata( $GLOBALS['post'] = & $office );
                echo "<li>";
                get_template_part("parts/loops/loop", "office");
                echo "</li>";
            endforeach;
            wp_reset_postdata(); 
			?>
		</ul>
		<?php
	endif;
	?>
</div>
<h2>Где нас найти</h2>
</div>
<div class="map-area">		
	<div id="offces-map"></div>
</div>
<div class="container">
	<?php if ($company_info = plt_option('company_info')) echo sprintf('<div class="content clearfix add-top-20"><h3 class="nomargin">Реквизиты:</h3><p class="add-top-10">%s</p></div>', nl2br($company_info));?>
	

<?php
	do_action('full_page_end');
	get_footer();
?>