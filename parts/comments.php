<div class="b-comments" id="comments">
	<div class="b-comments__title"><span class="icon-chat"></span>Комментарии</div>
	<div class="b-comments__info">
		<p><?php echo PLT_Helper::plural_form($post->comment_count, array('комментарий', 'коментария', 'комментариев'));?></p>
	</div>
   	<div class="b-comments__list">   
		<?php 
		if ( have_comments() ) : 
			?>   
			<ul class="comments-list">
				<?php
					wp_list_comments([
					'style' => 'li',         
					'callback' => ['PLT_Comment', 'callback'],         
					]);
				?>
			</ul>				        
			<?php 
			if ( ! comments_open() ) 
			echo '<p class="no-comments">Комментарии закрыты</p>';
		endif; 
		?>		
	</div>	
	<div class="b-comments__form">
		<div id="respond" class="comment-respond">		
		    <div id="reply-title" class="comment-reply-title h3">
				Добавить комментарий 			
				<small><?php cancel_comment_reply_link('Отменить ответ');?></small>
			</div>		
	  	  	<?php get_template_part('parts/forms/form', 'comment');?>	
		</div>
	</div>	
</div>
