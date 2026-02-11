<section class="front-advantages" data-aos="fade-in">
	<div class="container">
		<?php 
		$advantages = carbon_get_theme_option( 'plt_advantages' );	
		plt_site()->tpl( 'parts/advantages', ['advantages' => $advantages]);
		?>
	</div>
</section>