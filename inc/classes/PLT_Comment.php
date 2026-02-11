<?php

class PLT_Comment {
		
	function __construct() {			
		add_filter( 'comment_form_fields', [$this, 'comment_form_fields'] );
		add_filter( 'preprocess_comment', [$this, 'preprocess_comment'] );		
		add_filter('get_comment_author', [$this, 'get_comment_author'], 10, 3);
		
		if (wp_doing_ajax()) {				
			add_action('wp_ajax_run_comment', array($this, 'handle_comment'));
			add_action('wp_ajax_nopriv_run_comment', array($this, 'handle_comment'));
		}

	}	
	
	function handle_comment() {			
		$_POST = array_map("trim", $_POST);		
						
		if (!check_ajax_referer( 'form-comment-nonce', '', false))
			wp_send_json_error('<strong style="font-size:20px">Ошибка! Ваш токен устарел. Обновите страницу и отправьте форму еще раз</strong>');
			
		$_POST['plt-site-comment'] = true;
							
		$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
		
		if ( is_wp_error( $comment ) ) {
			$data = intval( $comment->get_error_data() );
			if ( ! empty( $data ) ) {
				wp_send_json_error($comment->get_error_message());
			} else {
				wp_send_json_error('<strong>Ошибка!</strong');
			}
		}

		$user = wp_get_current_user();
		$cookies_consent = (isset($_POST['wp-comment-cookies-consent']));

		do_action('set_comment_cookies', $comment, $user, $cookies_consent);

		$modal = plt_site()->tpl('parts/modals/modal-thankyou', ['text' => $_POST['successMsg']], false);
		wp_send_json_success(['modal' => $modal, 'location' => get_comment_link( $comment )]);
	}
	
	function preprocess_comment($commentdata) {	
		if (!isset($_POST['plt-site-comment']) && !$commentdata->user_ID)		
			wp_die();				
		return $commentdata;	
	}
		
	static function callback( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		echo '<li>';
		plt_site()->tpl('parts/comment', ['comment' => $comment]);
	}
	
	function get_comment_author($author, $comment_ID, $comment ) {							
		if (!empty($comment->user_id)){
			$user = get_userdata($comment->user_id);
			if ( user_can( $comment->user_id, 'administrator' ) ) 
			    return $user->nickname;	
		}
	    return $author;
	}
				
}

new PLT_Comment();