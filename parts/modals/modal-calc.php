<div class="modal fade" id="modal-calc" tabindex="-1" role="dialog" aria-labelledby="calcLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-inner">
				<div class="modal-header">
					<a href="#" class="close" data-dismiss="modal" aria-label="Close"><span class="icon-cross"></span></a>
					<div class="modal-title" id="calcLabel">Расчет за 5 минут</div>
				</div>
				<div class="modal-body">
					<p>Опишите, что нужно расчитать</p>					
					<?php get_template_part('parts/forms/form', 'modal-calc');?>				
				</div>
			</div>			
		</div>
	</div>
</div>