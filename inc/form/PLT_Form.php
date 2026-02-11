<?php
defined( 'ABSPATH' ) || exit;

class PLT_Form {
	
	public $to = 'info@online-tara.ru';
		
	public function __construct() {		
		//add_filter( 'wp_mail_from', array($this, 'get_email_from'), 10);
		//add_filter( 'wp_mail_from_name', array($this, 'get_email_from_name'), 10);		
		
		if (wp_doing_ajax()) {				
			add_action('wp_ajax_run_form', array($this, 'run_form'));
			add_action('wp_ajax_nopriv_run_form', array($this, 'run_form'));
		}		
	}
	
	public function get_email_from() {			
		return 'no-reply@online-tara.ru';
	}
	
	public function get_email_from_name() {			
		return 'online-tara.ru';
	}
	
	public function get_content_type() {
		return 'text/html';
	}
	
	
	public function run_form() {		
		$_POST = array_map("trim", $_POST);			
		
		if (!check_ajax_referer( $_POST['formName'].'-nonce', '', false))
			wp_send_json_error('<strong style="font-size:20px">Ошибка! Ваш токен устарел. Обновите страницу и отправьте форму еще раз</strong>');
		
		$_POST['referer'] = home_url($_POST['_wp_http_referer']);	
			
		$out = $this->_get_template(array(
			'name' => $_POST['formName'],
			'data' => $_POST,
		));
		$message = (is_string($out)) ? $out : '';
		
		add_filter( 'wp_mail_from', array( $this, 'get_email_from' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_email_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
		
		$sended = wp_mail( $this->to, $_POST['subject'] . ' - online-tara.ru', $message );
		
		remove_filter( 'wp_mail_from', array( $this, 'get_email_from' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_email_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
		
		if ($sended) {	
			do_action('pallet_form_add', $_POST);
			//AMO
			$amo = wp_safe_remote_post( 'https://api.amocore.in/poddonoptom/integration/poddonoptom/l3vql2v1s3fbrjzll01jrtj1t2xvqt09', [ 'body' => $_POST ] );

 		   	$modal = plt_site()->tpl('parts/modals/modal-thankyou', array('text' => $_POST['successMsg']), false);
			wp_send_json_success(array('modal'=>$modal));	
		} 		
		
		wp_send_json_error("<strong>Ошибка!</strong>");
		
	}
	
	private function _get_template($vars) {
        $tpl = __DIR__. '/tpl/' . $vars['name'] . '.tpl';
        if (file_exists($tpl)) {
            $template = file_get_contents($tpl);
            foreach ($vars['data'] as $name => $data) {
                $template = str_replace("%%" . $name . "%%", $data, $template);
            }
            return $template;
        } else {
            return false;
        }
    }
	
}

new PLT_Form();