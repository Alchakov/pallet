<div class="office-preview">
	<div class="office-preview__image">
		<?php 
		if (has_post_thumbnail())
			the_post_thumbnail("thumb_450x300");
		else
			echo plt_site()->the_placeholder_img('thumb_450x300');
		?>
	</div>								
	<div class="office-preview__info">									
		<?php the_title('<div class="office-preview__title">', '</div>');?>
		
		<?php
		if ($address = carbon_get_the_post_meta('plt_office_address')) : 
			?>
			<div class="box-contact box-contact--icon box-contact--address">
				<div class="box-contact__icon">										
					<span class="icon-point3"></span>
				</div>
				<?php echo $address;?>
			</div>
			<?php 
		endif;
		
						
		if ($phones = carbon_get_the_post_meta('plt_office_phone')) :
			$phones = explode(',', $phones);
			?>
			<div class="box-contact box-contact--icon box-contact--phone">
				<div class="box-contact__icon">										
					<span class="icon-phone"></span>
				</div>
				<?php echo implode("<br>", array_map('PLT_Helper::phone_link', $phones));?>
			</div>
			<?php 
		endif;
		
		if ($email = carbon_get_the_post_meta('plt_office_email')) : 
			?>
			<div class="box-contact box-contact--icon box-contact--email">
				<div class="box-contact__icon">										
					<span class="icon-envelope1"></span>
				</div>
				<a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>
			</div>
			<?php 
		endif;
				
		if ($timetable = carbon_get_the_post_meta('plt_office_timetable')) : 
			?>
			<div class="box-contact box-contact--icon box-contact--timetable">
				<div class="box-contact__icon">										
					<span class="icon-clock"></span>
				</div>
				<?php echo nl2br($timetable);?>
			</div>
			<?php 
		endif;
		?>									
		<a href="#" class="btn" data-toggle="modal" data-target="#modal-consalt"><span class="btn__inner"><span class="btn__text">Написать нам</span></span></a>
	</div>		
</div>