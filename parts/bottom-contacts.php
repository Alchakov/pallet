<div class="container">	
	<div class="b-contacts">
		<div class="b-contacts__info">
			
			<?php if ($phone = PLT_Other::phone_number('main')) : ?>
				<div class="b-contacts__item">
					<div class="b-contacts__title">Позвоните нам</div>
					<div class="b-contacts__icon"><div class="c-icon"><span class="icon-phone-fill"></span></div></div>
					<div class="box-contact box-contact--phone"><?php echo PLT_Helper::phone_link($phone);?></div>
					<div class="b-contacts__link"><a href="#" class="link-arrow" data-toggle="modal" data-target="#modal-call">Заказать звонок</a></div>
				</div>
			<?php endif;?>
			
			<?php if ($email = plt_option('email')) : ?>
				<div class="b-contacts__item">
					<div class="b-contacts__title">или напишите</div>
					<div class="b-contacts__icon"><div class="c-icon"><span class="icon-envelope"></span></div></div>
					<div class="b-contacts__link"><div class="box-contact box-contact--email"><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></div></div>
					<?php echo do_shortcode('[pallet_social links="whatsapp,viber,telegram"]');?>
				</div>
			<?php endif;?>
			
		</div>
		<div class="b-contacts__form">
			<div class="h3">Оставьте заявку</div>
			<p>и мы свяжемся с Вами</p>
			<?php get_template_part('parts/forms/form', 'apply');?>	
		</div>
	</div>
</div>