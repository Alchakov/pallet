<?php

class PLT_Wrapper {
	
	function __construct() {
		
		foreach (['full_page_start', 'aside_page_start'] as $action) {			
			add_action($action, [ $this, 'breadcrumbs'], 10);		
			add_action($action, [ $this, 'page_top_blocks'], 15);
			add_action($action, [ $this, 'main_start'], 20);
			add_action($action, [ $this, 'page_start'], 25);
			add_action($action, [ $this, 'page_container_start'], 30);
		}		
		add_action('aside_page_start', [ $this, 'row_sidebar_start'], 35);
		
		foreach (['full_page_end', 'aside_page_end'] as $action) {
			add_action($action, [ $this, 'page_container_end'], 10);		
			add_action($action, [ $this, 'page_end'], 15);		
			add_action($action, [ $this, 'page_bottom_blocks'], 20);		
			add_action($action, [ $this, 'main_end'], 25);
		}		
		add_action('aside_page_end', [ $this, 'row_sidebar_end'], 5);				
		
		add_action('main_start', [ $this, 'main_start'], 10);	
		add_action('main_end', [ $this, 'main_end'], 20);					
		
	}
	
	function breadcrumbs() {
		get_template_part("parts/breadcrumbs");
	}
	
	function page_top_blocks() {		
		get_template_part('parts/hero');		
	}
	
	function main_start() { ?>
		<main role="main" class="site-main main">   	
	<?php }
	
	function main_end() { ?>
		</main>   		
	<?php }
	
	function page_start() {
		global $post;
		$layout = 'default-layout';
		if (is_singular()) {
			$layout = 'single-layout '.$post->post_type.'-layout '.$post->post_name.'-layout';
		} elseif (is_archive()) {
			$layout = 'archive-layout '.get_queried_object()->taxonomy.'-layout';
		}
		?>
		<div class="main-layout <?php echo $layout;?>">
	<?php }
	
	function page_end() { ?>	
	    </div>  		
	<?php }
	
	function page_container_start() { ?>
	    <div class="container">		
	<?php }
	
	function page_container_end() { ?>	
		</div> 		
	<?php }
	
	function row_sidebar_start() { ?>
	    <div class="row">			
			<div class="col-lg-9">	
	<?php }
	
	function row_sidebar_end() { ?>			
			</div>			
			<div class="col-lg-3 order-first order-lg-last">
				<?php get_sidebar();?>
			</div>	
		</div> 		
	<?php }
	
	function page_bottom_blocks() {		
		if (is_singular() && carbon_get_the_post_meta('plt_bottom_contacts')) {
			get_template_part('parts/bottom-contacts');
		} elseif (is_product()) {
			get_template_part('parts/bottom-contacts');
		} elseif (is_product_category()) {
			get_template_part('parts/delivery');
			get_template_part('parts/bottom-contacts');
		}
	}
		
}