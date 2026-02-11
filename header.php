<?php
$html_class = (wp_is_mobile()) ? "mobile-device" : "desktop-device";
?>
<!DOCTYPE html>
<html class="<?php echo $html_class;?>" lang="ru">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo wp_get_document_title();?></title>
        
		<!--[if gte IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
        
		<!--[if IE 9]>
			<link href="https://cdn.jsdelivr.net/gh/coliff/bootstrap-ie8/css/bootstrap-ie9.min.css" rel="stylesheet">
		<![endif]-->
        
		<?php wp_head(); ?> 
         
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
		<meta name="HandheldFriendly" content="True"/>
		<meta http-equiv="Cache-Control" content="no-cache"/>
		<meta http-equiv="cleartype" content="on"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
		<meta http-equiv="imagetoolbar" content="no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>	
		<meta name="theme-color" content="#54c8f9">	
		<script type="text/javascript">(function(window,document,n,project_ids){window.GudokData=n;if(typeof project_ids !== "object"){project_ids = [project_ids]};window[n] = {};window[n]["projects"]=project_ids;config_load(project_ids.join(','));function config_load(cId){var a=document.getElementsByTagName("script")[0],s=document.createElement("script"),i=function(){a.parentNode.insertBefore(s,a)},cMrs='';s.async=true;if(document.location.search&&document.location.search.indexOf('?gudok_check=')===0)cMrs+=document.location.search.replace('?','&');s.src="//mod.gudok.tel/script.js?sid="+cId+cMrs;if(window.opera == "[object Opera]"){document.addEventListener("DOMContentLoaded", i, false)}else{i()}}})(window, document, "gd", "mmur1tec08");</script>
	</head>
	<body>
		<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date(); for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }} k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(36041375, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, ecommerce:"dataLayer" }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/36041375" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
		<div class="wrapper">
        
		<header role="presentation" class="b-header"> 
			
			<div class="header-top d-none d-md-block">
				<div class="container">
					<nav class="top-bar">
						<?php 
						wp_nav_menu(array(								               
							'theme_location' => 'top-nav',   
							'container' => false,               
							'menu_class' => 'top-menu hierarchical-menu', 
							'menu_id' => 'top-nav',   
							'link_before' => '<span class="help">',
							'link_after' => '</span>',
							'depth' => 3
						));
						?>
					</nav>						
					<div class="header-search d-none d-lg-block">
						<?php //get_search_form();?>
						<?php echo do_shortcode('[wcas-search-form]'); ?>
					</div>				
				</div>				
			</div>	
					
			<div class="header-middle d-none d-md-block">
				<div class="container">
					<div class="row justify-content-between align-items-center flex-nowrap">
						
						<div class="col-auto">
							<div class="box-logo">
								<a href="/">
									<?php plt_site()->get_img('logo.svg');?>
								</a>
							</div>
						</div>
												
						<?php 
						if ($timetable = plt_option('timetable')) : 
							?>
							<div class="col-auto d-none d-lg-block">								
								<div class="box-contact box-contact--icon box-contact--timetable">
									<div class="box-contact__icon">										
										<span class="icon-clock"></span>
									</div>
									<?php echo nl2br($timetable);?>
								</div>
							</div>
							<?php 
						endif;
						?>	
											
						<div class="col-auto">	
							
							<?php 
							$phone = PLT_Other::phone_number('main');
							$other_phones = PLT_Other::phone_number('other');
							if ($phone) : 
								$atts = [];							
								$attributes = '';
								if ($other_phones)
									$atts = [
										'class' => 'dropdown-toggle',
										'data-toggle' => 'dropdown',
										'aria-haspopup' => 'true',
										'aria-expanded' => 'false'
									];
								foreach ( $atts as $attr => $value ) 
									$attributes .= ' ' . $attr . '="' . $value . '"';
								?>							
								<div class="header-phone dropdown d-none d-md-inline-block">
	                                <span id="header-contact-dropdown-phones" <?php echo $attributes;?>>
	                                    <span class="box-contact box-contact--phone"><?php echo PLT_Helper::phone_link($phone);?></span>
	                                </span>
									<?php if (!empty($other_phones)) : ?>
		                                <div class="dropdown-menu" aria-labelledby="header-contact-dropdown-phones">
											<span class="box-contact"><?php echo implode("<br>", array_map('PLT_Helper::phone_link', $other_phones));?></span>
		                                </div>
									<?php endif;?>
	                            </div>								
							<?php endif;?>	
							
							<div class="header-callback"><a href="#" data-toggle="modal" data-target="#modal-call">Заказать звонок</a></div>
						</div>
						
						<div class="col-auto">
							<div class="header-social">								
								<?php echo do_shortcode('[pallet_social links="whatsapp,facebook,telegram,email"]');?>
							</div>
						</div>	
						
						<div class="col-auto">
							<div class="header-btn">
								<a href="#" class="btn btn--transparent btn--no-shadow" data-toggle="modal" data-target="#modal-calc"><span class="btn__inner"><span class="btn__text">Расчет за 5 минут</span></span></a>
							</div>
							
							<?php if ($email = plt_option('email')) : ?>
								<div class="text-center add-top-5"><div class="box-contact box-contact--email"><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></div></div>
							<?php endif;?>
							
						</div>		
								
					</div>					
				</div>				
			</div>			
			<div class="header-bottom d-none d-md-block">
				<div class="container">
					<div class="row">						
						<div class="col-md-2 no-gutter-right">
							<nav class="catalog-bar">
								<ul class="catalog-menu main-menu hierarchical-menu">
									<li class="menu-item-has-children">
										<a href="/vybrat-razdel-kataloga/" class="catalog-bar__title">
											<span class="help">
												<span class="catalog-bar__line catalog-bar__line--top"></span>
												<span class="catalog-bar__line catalog-bar__line--middle"></span>
												<span class="catalog-bar__line catalog-bar__line--bottom"></span>
												Каталог
											</span>
										</a>
										<?php wp_nav_menu(array(								               
											'theme_location' => 'catalog-nav',   
											'container' => false,               
											'menu_class' => 'sub-menu', 
											'menu_id' => 'catalog-nav',  
											'link_before' => '<span class="help">',
											'link_after' => '</span>',
										));?>
									</li>						
								</ul>								
							</nav>
						</div>					
						<div class="col-md-10 no-gutter-left">
							<nav class="main-bar">
								<?php								
									if (wp_is_mobile()) {
										$class_add = '';
										$depth = 1;
									} else {
										$class_add = ' hierarchical-menu';
										$depth = 3;
									}
									wp_nav_menu(array(								               
										'theme_location' => 'main-nav',   
										'container' => false,               
										'menu_class' => 'main-menu'.$class_add,   
										'menu_id' => 'main-nav', 
										'link_before' => '<span class="help">',
										'link_after' => '</span>',
										'depth' => $depth
									));
								?>
							</nav>
						</div>						
					</div>		
				</div>				
			</div>	
			
			<?php 
			if (wp_is_mobile()) : 
				?>
				<div class="mobile d-md-none">	
					<div class="mobile__header">						
						<div class="mobile__menu-toggle">
							<span class="menu-line menu-line--top"></span>
							<span class="menu-line menu-line--middle"></span>
							<span class="menu-line menu-line--bottom"></span>
						</div>												
						<div class="mobile__logo">
							<div class="box-logo">
								<a href="/">
									<?php plt_site()->get_img('logo.svg');?>
								</a>
							</div>
						</div>	
						<div class="mobile__actions">
							<a href="#" class="mobile-search-toggle mobile-action">
								<span class="icon-loupe"></span>
								<span class="icon-cross"></span>
							</a>		
							<a href="tel:<?php echo PLT_Helper::clean_phone_number(PLT_Other::phone_number('main'));?>" class="mobile-action"><span class="icon-phone-fill"></span></a>
							<div class="dropdown mobile-dropdown">
								<a href="/kontakty/" class="mobile-action" id="mobile-contact-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="icon-info"></span></a>
								<div class="dropdown-menu" aria-labelledby="mobile-contact-dropdown">							
									<?php 
									if ($phones = PLT_Other::phone_number()) :
										?>
										<p>
											<strong>Телефон:</strong>
											<span class="box-contact">
												<?php echo implode("<br>", array_map('PLT_Helper::phone_link', $phones));?>
											</span>
										</p>
										<?php 
									endif;
									if ($timetable = plt_option('timetable')) : 
										?>
										<p>
											<strong>Режим работы:</strong>
											<span class="box-contact">
												<?php echo nl2br($timetable);?>
											</span>
										</p>
										<?php 
									endif;
									?>	
									<p>
										<strong>E-mail:</strong>
										<span class="box-contact">
											<a href="mailto:<?php echo plt_option('email');?>"><?php echo plt_option('email');?></a>
										</span>
									</p>
									<?php echo do_shortcode('[pallet_social]');?>
								</div>
							</div>				
						</div>
					</div>	
					<div class="mobile__panel">
						<div class="mobile__panel-inner">
							<div class="mobile__panel-content">
								<div id="mobile-nav">
									<?php 
									wp_nav_menu(array(								               
										'theme_location' => 'mobile-nav',   
										'container' => false,               
										'menu_class' => 'mobile-menu',  
										'menu_id' => '', 
									));
									?>
								</div>							
							</div>	
						</div>												
					</div>
					<div class="mobile-search-block">
						<?php echo do_shortcode('[wcas-search-form]'); ?>						
					</div>
				</div>	
				<?php 
			endif;
			?>	
			
			<div class="header-sticky"></div>
		</header>			