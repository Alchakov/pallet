<?php $price_display = ($retail_price) ? $retail_price : $price;?>
<div class="buy-card">
	<div class="row">
		<div class="col-md-5">
			<img src="<?php echo $image;?>" alt="" class="buy-card__image" />
		</div>
		<div class="col-md-7">
			<div class="buy-card__title"><?php echo $name;?></div>
			
			<?php if ($retail_price) : 
				?>
				<div class="buy-card__item">
					<div class="buy-card__item-label">					
						Розничная цена
						<?php if ($limit) echo ("<span><br> (до {$limit} товаров)</span>");?>
					</div>
					<div class="product-price buy-card__price">
						<span class="product-price__amount"><?php echo $retail_html;?></span>
					</div>
				</div>
				<?php 
			endif;?>
			
			<div class="buy-card__item">
				<div class="buy-card__item-label">
					Оптовая цена					
					<?php if ($limit && $retail_price) echo ("<span><br> (от {$limit} товаров)</span>");?>
				</div>
				<div class="product-price buy-card__price">
					<span class="product-price__amount"><?php echo $price_html;?></span>
				</div>
			</div>	
			<div class="buy-card__item">
				<div class="buy-card__item-label">Количество</div>							
				<div class="product-qty buy-card__qty">
					<input 
						type="number" 
						step="1" 
						min="1" 
						name="product_quantity" 
						value="1" 
						inputmode="numeric" 
						data-price="<?php echo $price;?>" 						
						data-retail="<?php echo $retail_price;?>" 						
						data-limit="<?php echo $limit;?>"
					/>
				</div>
			</div>
			<div class="buy-card__item">
				<div class="buy-card__item-label">Итого</div>
				<div class="product-price">
					 <span class="product-price__amount"><span class="woocommerce-Price-amount amount"><span class="buy-card__total"><?php echo $price_display;?></span>&nbsp;<?php echo get_woocommerce_currency_symbol();?></span></span>
				</div>
			</div>			
		</div>
	</div>
</div>
<input type="hidden" value="<?php echo $name;?>" name="product_name">
<input type="hidden" value="<?php echo $price;?>" name="product_price">
<input type="hidden" value="<?php echo $retail_price;?>" name="product_retail_price">
<input type="hidden" value="<?php echo $limit;?>" name="product_price_limit">
<input type="hidden" value="<?php echo $price_display;?>" name="product_total">	