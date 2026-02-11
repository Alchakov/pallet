<?php 
if (!carbon_get_theme_option('plt_front_steps_active')) return; 

$steps = [];
for ($i=1; $i < 6; $i++) {
	$title = carbon_get_theme_option('plt_front_steps_step_'.$i.'_title');
	$content = carbon_get_theme_option('plt_front_steps_step_'.$i.'_content');
	$steps[$i]  = ($title || $content) ?  '<div class="front-step">'.
												sprintf('<div class="front-step__title">%s</div>', $title).
												sprintf('<div class="front-step__content content">%s</div>', apply_filters('the_content', $content)).
												sprintf('<div class="front-step__num">%s</div>', $i).
											'</div>'
										: '';
}

$title = carbon_get_theme_option('plt_front_steps_title');
$subtitle = carbon_get_theme_option('plt_front_steps_subtitle');

?>
<section class="front-steps" data-aos="fade-in">
	<div class="container">		
		<?php 
		if ($title) echo "<h2>{$title}</h2>";				
		if ($subtitle) printf('<div class="front-steps__subtitle">%s</div>', $subtitle);
		?>
		<div class="front-steps__row">
			<ul class="flex-grid-3">
				<li><?php echo $steps[1];?></li>
				<li><?php echo $steps[2];?></li>
				<li><?php echo $steps[3];?></li>
				<li class="d-md-none"><?php echo $steps[4];?></li>
				<li class="d-md-none"><?php echo $steps[5];?></li>
			</ul>
		</div>
		<?php 
		if ($steps[4] || $steps[5]) :
			?>
			<div class="front-steps__row front-steps__row--second">
				<ul class="flex-grid-3">
					<li><?php echo $steps[4];?></li>
					<li><?php echo $steps[5];?></li>
				</ul>
				<?php plt_site()->get_img('pallets.png');?>
			</div>
			<?php 
		endif; 
		?>
	</div>
</section>
