<?php
/**
 * Single Product Payment
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="single-product__payment">
	<div class="row">
		<div class="col-xl-6 offset-xl-6 d-flex justify-content-sm-between align-items-center">
			<span class="text-upper fs-15 d-none d-sm-inline">Варианты оплаты</span>
			<div class="c-icon"><?php plt_site()->get_img('/payment/purse.png');?></div>		
			<div class="c-icon"><?php plt_site()->get_img('/payment/pc.png');?></div>		
			<div class="c-icon"><?php plt_site()->get_img('/payment/visa.png');?></div>		
			<div class="c-icon"><?php plt_site()->get_img('/payment/mastercard.png');?></div>	
			<div class="c-icon"><?php plt_site()->get_img('/payment/mir.png');?></div>
		</div>
	</div>
</div>
