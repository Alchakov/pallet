<?php 
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

$hero_fields = [
	Field::make( 'checkbox', 'plt_hero_active', 'Активировать баннер сверху?' )->set_option_value( 'no' ),
	Field::make( 'text', 'plt_hero_sticker', 'Текст стикера')->set_conditional_logic([['field' => 'plt_hero_active', 'value' => true]])->set_width(50),
	Field::make( 'color', 'plt_hero_sticker_color', 'Фон стикера' )->set_conditional_logic([['field' => 'plt_hero_active', 'value' => true]])->set_width(50)
->set_palette( ['#ff587b', '#6fe46f', '#71bdee', '#a071ee'] )->set_default_value('#ff587b'),
	Field::make( 'text', 'plt_hero_title', 'Заголовок' )->set_conditional_logic([['field' => 'plt_hero_active', 'value' => true]])->set_width(100),
	Field::make( 'text', 'plt_hero_subtitle', 'Подзаголовок' )->set_conditional_logic([['field' => 'plt_hero_active', 'value' => true]])->set_width(100),
	Field::make( 'image', 'plt_hero_background', 'Фон' )->set_conditional_logic([['field' => 'plt_hero_active', 'value' => true]])->set_width(50),
	Field::make( 'image', 'plt_hero_image', 'Изображение' )->set_conditional_logic([['field' => 'plt_hero_active', 'value' => true]])->set_width(50),
	Field::make( 'complex', 'plt_hero_list', 'Список' )
		->set_width(100)
		->set_conditional_logic([['field' => 'plt_hero_active', 'value' => true]])
		->add_fields( array(
			Field::make( 'image', 'image', 'Иконка' )->set_width(30),
			Field::make( 'text', 'text', 'Подпись' )->set_width(70),
		))
		->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
		->set_header_template('<% if (text) { %><%- text %><% } %>'),
];