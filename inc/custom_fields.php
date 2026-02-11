<?php
add_action( 'carbon_fields_register_fields', function() {	
	require 'custom-fields/variables.php';
	require 'custom-fields/gutenberg.php';
	require 'custom-fields/post-meta.php';
	require 'custom-fields/term-meta.php';
	require 'custom-fields/theme-options.php';		
	require 'custom-fields/front-page.php';
});

add_filter('carbon_fields_association_field_options', function( $options, $name ) {
	$active = false;
	switch ($name) {
		case 'plt_front_reviews_video_items':
			$active = true;
			$type = 'video';
			break;
		case 'plt_front_reviews_photo_items':
			$active = true;
			$type = 'photo';
			break;
		case 'plt_front_reviews_text_items':
			$active = true;
			$type = 'text';
			break;
	}
	
	if ($active) :
		$output = [];
		foreach ($options as $value) 
			if (carbon_get_post_meta($value['id'], 'plt_review_type') == $type)
				$output[] = $value;
		return $output;		
	endif;
	return $options;
},10,2);

function carbon_date_local_ru() {
	return array(
		'firstDayOfWeek' => 1,
		'yearAriaLabel' => "Год",
		'weekAbbreviation' =>  "Нед.",
		'weekdays' => array(
			'shorthand' => array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"),
			'longhand' => array("Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"),
		),
		'months' => array(
			'shorthand' => array("Янв", "Фев", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сен", "Окт", "Ноя", "Дек"),
			'longhand' => array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"),
		)
	);
}