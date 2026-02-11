<?php 
if ($advantages) : 
	?>
	<div class="b-advantages">
		<ul class="flex-grid-2 flex-grid-lg-4">
			<?php 
			foreach ($advantages as $advantage) :
				printf('<li><div class="advantage"><div class="advantage__icon" style="background-image:url(%s)"></div>%s</div></li>',
				 		wp_get_attachment_url($advantage['image']), 
						$advantage['title']
					);
			endforeach;
			?>	
		</ul>
	</div>
	<?php	
endif;