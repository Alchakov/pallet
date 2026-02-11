<?php

add_shortcode( 'pallet_agreement', function($atts) {   
    return 'Я&nbsp;даю свое согласие на&nbsp;обработку персональных данных и&nbsp;соглашаюсь с&nbsp;<a href="/polzovatelskoe-soglashenie/">условиями</a> и&nbsp;<a href="/politika-konfidenczialnosti/">политикой конфиденциальности</a>';
});

add_shortcode('pallet_social', function($atts) {
	$atts = shortcode_atts( array(
		'links' => implode(',', array_keys(PLT_Other::social_list())),		
		'class' => ""
	), $atts );
	
	$links = array_map('trim', explode(",", $atts['links']));
	$socials = array();		
	$class = trim("box-social ".$atts['class']);
		
	foreach ($links as $link) {
		if ($value = get_theme_mod('social_'.$link, '')) 
			$social[$link] = $value;
		elseif ($link == 'email' && $value = plt_option('email'))
			$social['envelope'] = 'mailto:'.$value;		
	}
	
	$output = '<div class="'.$class.'"><ul>';					
	foreach( $social as $name => $url )
		if (!empty( $url ))							
			$output .= sprintf('<li><a href="%1$s" class="c-social c-social--%2$s" target="_blank"><span class="icon icon-%2$s"></span></a></li>', $url , esc_attr( strtolower( $name )));		
	$output .=  '</ul></div>';
	return 	$output;
});

add_shortcode('pallet_form', function($atts) { 
	$atts = shortcode_atts( array(
		'title' => 'Оставить заявку',
		'btn' 	=> 'Отправить заявку',
	), $atts );
	ob_start();	
	?>
	<div class="box-form">
		<div class="h2"><?php echo $atts['title'];?></div>		
		<?php plt_site()->tpl('parts/forms/form-content', $atts);?>	
	</div>
	<?php
	return ob_get_clean();
});

add_shortcode('pallet_share', function($atts) { 
	static $loadShareScript = true;
	$atts = shortcode_atts( array(
		'title' => '',
	), $atts );
	ob_start();?>
	<div class="box-share">
		<?php if($atts['title']) echo '<div class="box-share__title">'.$atts['title'].'</div>';?>		
		<?php if ($loadShareScript) : ?>
			<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
			<script src="//yastatic.net/share2/share.js"></script>
		<?php endif;?>
		<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,telegram" data-counter=""></div>
	</div>
<?php	
	$loadShareScript = false;
    return ob_get_clean();
});

add_shortcode('pallet_videos', function($atts) { 
	$atts = shortcode_atts( array(
		'ids' => '',
		'grid' => '2',
	), $atts );
	$videos = get_posts( array('posts_per_page' => -1, 'post_type' => 'video', 'include' => $atts['ids']));
	if (empty($videos)) return;
	ob_start();?>
	<ul class="videos-list flex-grid-2 flex-grid-lg-<?php echo $atts['grid'];?>">
		<?php 
		foreach( $videos as $video ) { 
			$data = [
				'image' => carbon_get_post_meta($video->ID, 'plt_video_image'),
				'hover' => carbon_get_post_meta($video->ID, 'plt_video_image_hover'),
				'embed' => carbon_get_post_meta($video->ID, 'plt_video_embed'),
				'title' => get_the_title($video->ID)
			];
			echo "<li>";
				plt_site()->tpl("parts/loops/loop-video", $data);
			echo "</li>";
		}
		?>	
	</ul>	
<?php	
    return ob_get_clean();
});


