<?php  
	get_header(); 
	the_post();
	do_action('aside_page_start');		
	list($day,$month,$year) = sscanf(get_the_time('j m Y'), "%s %s %s");		
?>

<article class="node node--post">
	<header class="node__header">
		<div class="link-back add-bottom-20">			
			<?php 
				$term = plt_site()->get_primary_term( get_the_ID(), 'category' );
				$term_link = get_term_link($term);
				$referer = $_SERVER['HTTP_REFERER'] ?? '';
				echo sprintf('<a href="%s">%s</a>', (strpos($referer, $term_link) === false) ? $term_link : $referer, $term->name);
			?>		
		</div>			
		<?php the_title("<h1>", "</h1>");?>  	
		
		<div class="node__header-info">
			<div class="node__header-time">
				<time datetime="<?php echo "{$year}-{$month}-{$day}";?>">
					<?php echo "{$day}.{$month}.{$year}";?>
				</time>	
			</div>			
			<div class="node__header-comment">
				<a href="#comments" class="link-scroll"><span class="icon-chat"></span><?php echo PLT_Helper::plural_form($post->comment_count, array('комментарий', 'коментария', 'комментариев'));?></a>
			</div>
		</div>
		
	</header>
	<section class="content clearfix">
		<?php the_content();?>   
	</section>
	<footer class="node__footer">  
		<div class="node__footer-share">
			<?php echo do_shortcode('[pallet_share title="Поделиться"]');?>  
		</div>	
		<div class="node__footer-relink add-top-50 clearfix">
			<?php 
				$relink = array( 
					'next' => array(
								'name' => 'Следующая статья',
								'post' => get_next_post(true)
							),
					'prev' => array(
								'name' => 'Предыдущая статья',
								'post' => get_previous_post(true)
							)														
				);
				foreach ($relink as $k => $v) {
					if( ! empty($v['post']) ) { ?>
						<a href="<?php echo get_permalink( $v['post'] ); ?>" class="article-relink article-relink--<?php echo $k;?>">									
							<div class="article-relink__dir article-relink__dir--<?php echo $k;?>">
								<span><?php echo $v['name'];?></span>
							</div>
							<div class="article-relink__date">
								<?php echo get_the_time('j.m.Y', $v['post']->ID);?>
							</div>
							<div class="article-relink__title">											
								<?php echo esc_html($v['post']->post_title); ?>
							</div>									
						</a>
					<?php }
				}						
			?>	 
		</div>
	</footer>
</article>


<?php if (( comments_open() || get_comments_number() )) comments_template('/parts/comments.php');?>

<?php
	do_action('aside_page_end');
	get_footer();
?>