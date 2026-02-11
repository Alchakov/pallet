<form action="#" method="post" name="form-comment" id="commentform" class="comment-form form" novalidate="" data-action="run_comment">
	<?php if (!is_user_logged_in()) : ?>
		<div class="row">
			<div class="col-md-6">
				<div class="form__group">
					<div class="form__field form__field--text">
						<input type="text" name="author" placeholder="Имя *">
					</div>	 
				</div>
			</div>
			<div class="col-md-6">
				<div class="form__group">
					<div class="form__field form__field--text">
						<input type="email" name="email" placeholder="E-mail *">
					</div>	 
				</div>
			</div>
		</div>
	<?php endif;?>			
	<div class="form__group">
		<div class="form__field form__field--textarea">
			<textarea name="comment" placeholder="Комментарий *"></textarea>
		</div>	 
	</div>		
	<div class="form__group form__group--footer"> 
		<div class="form__result"></div>
		<div class="clearfix"> 		
			<?php PLT_Content::nonce_field( 'form-comment-nonce');?>
			<button class="btn run-form" type="submit"><span class="btn__inner"><span class="btn__text">Отправить</span></span></button>
		</div>		
		<div class="form__terms">
			<div class="field-checkbox">
				<label>				
					<?php echo do_shortcode('[pallet_agreement]');?>
					<input type="checkbox" name="wp-comment-cookies-consent" value="yes" checked="checked">					
					<span class="field-checkbox__checkmark"></span>					
				</label>
			</div>
		</div>		
	</div>			
   <?php comment_id_fields();?>
</form>