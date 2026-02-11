<footer role="contentinfo" class="b-footer">
	
 	<div class="container">			
		<div class="footer-top">
			<div class="row">
				<?php 
				$menus = ['footer-nav-left', 'footer-nav-middle', 'footer-nav-right'];
				foreach ($menus as $menu) :
					$menu_title = wp_get_nav_menu_name($menu);
					?>
					<div class="col-md-3">
						<div class="footer-menu">
							<div class="footer-menu__title"><?php echo $menu_title;?></div>
							<?php wp_nav_menu(array(								               
								'theme_location' => $menu,   
								'container' => false,               
								'menu_class' => 'footer-menu__nav',
								'menu_id' => $menu,
								'depth' => 1       
							));?>
						</div>
					</div>
					<?php 
				endforeach;
				?>
				<div class="col-md-3">
					<div class="footer-btn">
						<a href="#" class="btn btn--white btn--no-shadow" data-toggle="modal" data-target="#modal-calc"><span class="btn__inner"><span class="btn__text">Расчет за 5 минут</span></span></a>
						<div class="add-top-15"><a href="#" data-toggle="modal" data-target="#modal-call">Заказать звонок</a></div>
					</div>					
				</div>
			</div>
		</div>		
	
		<div class="footer-bottom">				
			<div class="row justify-content-md-between align-items-md-center flex-md-nowrap">
				<div class="col-md-3 d-none d-md-block">
					<div class="box-logo">
						<a href="/">
							<?php plt_site()->get_img('logo-grey.svg');?>
						</a>
					</div>
				</div>				
				<?php 
				if ($timetable = plt_option('timetable')) : 
					?>
					<div class="col-md-3">								
						<div class="box-contact box-contact--icon box-contact--timetable">
							<div class="box-contact__icon">										
								<span class="icon-clock"></span>
							</div>
							<?php echo nl2br($timetable);?>
						</div>
					</div>
					<?php 
				endif;
								
				if ($phones = PLT_Other::phone_number()) :
					?>
					<div class="col-md-3">								
						<div class="box-contact box-contact--icon box-contact--phone">
							<div class="box-contact__icon">										
								<span class="icon-phone"></span>
							</div>
							<?php echo implode("<br>", array_map('PLT_Helper::phone_link', $phones));?>
						</div>
					</div>
					<?php 
				endif;
				?>					
				<div class="col-md-3">
					<?php echo do_shortcode('[pallet_social]');?>
				</div>
			</div>	
			
			<div class="row add-top-30">
				<?php 
				if ($company_info = plt_option('company_info')) : 
					?>
					<div class="col-md-7 col-lg-6 order-md-last">		
						<div class="two-col box-company-info">				
							<p class="footer-copyright">Реквизиты: <?php echo nl2br($company_info);?></p>	
						</div>			
					</div>	
					<?php 
				endif;
				?>			
				<div class="col-md-5 col-lg-6">
					<p class="footer-copyright">
						<span><?php echo date('Y');?> г. «Тара Онлайн» &copy; Все права защищены.</span>
					</p>
					<p class="footer-copyright">
						<a href="/politika-konfidenczialnosti/">Политика конфиденциальности</a><br>
						<a href="/polzovatelskoe-soglashenie/">Пользовательское соглашение</a><br>
						<a href="/sitemap/">Карта&nbsp;сайта</a><br>			
					</p>
				</div>		
			</div>
		</div>
	</div>
</footer>

</div>

<a href="#" class="scroll-to-top">
  <span class="help"><span class="icon-arrow-up-long"></span></span>
</a>

<?php
	$modal = ['call', 'calc', 'apply', 'buy', 'consalt', 'get-wholesale-price', 'product-cat-title-button', 'buy-quest'];
	
	if (is_front_page()) {
		$modal[] = 'client';
	}
		

	foreach ($modal as $item)
		get_template_part("parts/modals/modal", $item);

	wp_footer();
?>

</body>
</html>