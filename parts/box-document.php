<div class="box-documents">   
	<ul class="two-col --nolist">      
		<?php foreach($list as $item) {
			$url = wp_get_attachment_url($item['file']);
			$ext = pathinfo($url, PATHINFO_EXTENSION);	
			if (!in_array($ext, ['xlsx', 'xls', 'docx', 'doc', 'pdf']))
				$ext = 'document';			
			echo "<li><span class=\"icon-{$ext}\"></span> <a href=\"{$url}\" target=\"_blank\">{$item['title']}</a></li>";
		}?>
	</ul> 
</div> 