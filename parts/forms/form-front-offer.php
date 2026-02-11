<form action="#" class="form  form--color_white" name="form-front-offer">
	<div class="form__group">
		<div class="form__field form__field--text">
			<input type="text" name="name" placeholder="Имя">
		</div>	       
		<div class="form__field form__field--text">
			<input type="tel" name="phone" class="input-phone" placeholder="Телефон *">
		</div>
	</div>
	<div class="form__group form__group--footer"> 
		<div class="form__result"></div>
        <div class="clearfix">
			<input type="hidden" value="<?= is_single() ? esc_attr(get_the_title()) : esc_attr(PLT_Content::archive_title());?>" name="page_name">
			<?php PLT_Content::nonce_field( 'form-front-offer-nonce');?>
			<button class="btn run-form" type="submit"><span class="btn__inner"><span class="btn__text">Оставить заявку</span></span></button>
        </div>		
		<div class="form__terms">
			<div class="field-checkbox">
				<label>				
					<?php echo do_shortcode('[pallet_agreement]');?>
					<input type="checkbox" name="agreement" checked="checked">					
					<span class="field-checkbox__checkmark"></span>					
				</label>
			</div>
		</div>		
	</div>
</form>