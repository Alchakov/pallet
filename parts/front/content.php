<?php $gallery = carbon_get_the_post_meta('plt_front_gallery');?>
<section class="front-content <?php echo (!$gallery) ? 'front-content--full' : '';?>" data-aos="fade-in">
	<div class="container">	
		<div class="front-content__inner">
			<div class="box-logo">
				<?php plt_site()->get_img('logo-blue-green.svg');?>
			</div>
			<div class="row">
				<div class="col-lg-<?php echo ($gallery) ? 7 : 12;?>">
					<div class="front-content__content">
						<?php the_title('<h1>', '</h1>'); ?>
						<div class="content">			
							<?php the_content();?>
						</div>
					</div>
				</div>
				<?php if ($gallery): ?>
					<div class="col-lg-5">
						<div class="front-content__gallery">
							<?php 
							foreach ($gallery as $item)
								echo sprintf('<figure>%s%s</figure>', 
											wp_get_attachment_image($item['image'], 'medium'),
											$item['title'] ? '<figcaption>'.$item["title"].'</figcaption>' : ''
										);
							?>
						</div>
					</div>
				<?php endif; ?>
			</div>	
		</div>
	</div>
</section>
