<div class="comment" id="comment-<?php comment_ID();?>">
	<div class="comment__header">
		<div class="comment__icon">
			<?php echo (user_can( $comment->user_id, 'administrator' )) ? '<img src="'.plt_site()->get_img_url('logo-min.svg').'" alt=""/>' : '<span class="icon-person"></span>';?>
		</div>
		<div class="comment__title">
			<?php echo get_comment_author();?>
		</div>
		<div class="comment__date">
			<?php printf('%s в %s', get_comment_date(), get_comment_time());?>
		</div>
	</div>
	<div class="comment__body">
		<?php if ($comment->comment_approved == '0') echo '<div class="comment__alert"><p>Комментарий на проверке</p></div>';?>		
		<?php comment_text(); ?>
	</div>
	<div class="comment__footer">
		<?php comment_reply_link([ 'reply_text' => 'Ответить', 'depth' => 1, 'max_depth' => get_option('thread_comments_depth') ], get_comment_ID(), get_the_ID()); ?>
	</div>
</div> 