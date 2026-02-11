<?php 
if (!carbon_get_theme_option('plt_front_slider_active')) return; 

if ($slideshow = carbon_get_theme_option('plt_front_slider')) : 
	?>
	<div class="front-slider">
		<div class="front-slideshow slider-content--equal-height">
			<?php foreach($slideshow as $item) :
				extract($item);
				?>
				<div class="front-slide" <?php if($background) echo 'style="background-image: url('.wp_get_attachment_image_url($background, 'full').')"';?>>
					<div class="container">
						<div class="row">
							<div class="col-md-6 col-lg-5">	
								<?php 
								if ($title) printf('<div class="front-slide__title">%s</div>', $title);									
								if ($caption) printf('<div class="front-slide__caption">%s</div>', $caption);						
								if ($button) printf('<div class="front-slide__btn"><a href="%s" class="btn"><span class="btn__inner"><span class="btn__text link-arrow">%s</span></span></a></div>', $button_link, $button);
								?>	
							</div>
							<div class="col-md-6 col-lg-7">	
								<div class="front-slide__image">
									<?php 
									if ($type == 'image' && $image) :																	
										echo wp_get_attachment_image($image, 'large');
									elseif ($type == 'video' && $video) :
										$data = [
											'image' => $image,
											'hover' => $video_image_hover,
											'embed' => $video,
											'type'  => 'video'
										];
										plt_site()->tpl("parts/loops/loop-video", $data);
									elseif ($type == 'video_file' && $video_file) :
										$data = [
											'image' => $image,
											'hover' => $video_image_hover,
											'video_url' => $video_file,
											'type'  => 'video_file'
										];
										plt_site()->tpl("parts/loops/loop-video", $data);
									endif; 
									?>
								</div>
							</div>
						</div>
					</div>					
				</div>
				<?php
			endforeach;?>			
		</div>		
		<div class="front-slideshow-arrows slider-arrow-outside"></div>
	</div>
<?php endif;?>