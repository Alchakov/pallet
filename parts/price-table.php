<div class="price-table">	
	<?php if ($heading) echo "<h2>{$heading}</h2>";?>
	<div class="scroll-table">				
		<table>
			<tbody>
				<?php foreach ($table as $value): ?>
					<tr>
						<td><?php echo esc_html( $value['label'] ); ?></td>
						<td><span class="price-table__price"><?php echo esc_html( $value['price'] ); ?></span></td>
						<td><a href="#" class="price-table__apply" data-toggle="modal" data-target="#modal-apply" data-title="<?php echo esc_attr(strip_tags($value['label'].' - '.$value['price']));?>">Оставить заявку</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>