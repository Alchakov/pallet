<?php

class PLT_Admin {
	
	function __construct() {	
		
		$this->login();
		$this->style();
		
		add_filter('upload_mimes', [$this, 'upload_mimes'] );
		add_filter('admin_init', [$this, 'hide_editor'] );		
	}
	
	function login() {		
		add_action('login_head', function() {
			echo '<style type="text/css">#login h1 {display:none;}</style>';
		});		
		add_filter('login_headerurl',  function() {
			return get_home_url();
		});	
	}
	
	function style() {
		add_action('admin_head', function () {
		  echo '<style>
			body.gutenberg-editor-page .editor-post-title__block, body.gutenberg-editor-page .editor-default-block-appender, body.gutenberg-editor-page .editor-block-list__block {
				max-width: none !important;
			}			
			.block-editor__container .wp-block {
				max-width: none !important;
			}			
			.editor-post-featured-image .components-responsive-wrapper > div {
				padding:100% 0 0 !important;
			}
			#edittag {
			    max-width: 100%;
			}
		  </style>';
		});	
	}
	
	
	function upload_mimes($mimes) {	
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;		
	}
	
	function hide_editor() {		
		$pages = [27, 23];
		$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
		if( !isset( $post_id ) ) return;	
				
	    if (in_array($post_id, $pages))
	    	remove_post_type_support('page', 'editor');	    
	}
	
}