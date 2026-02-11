<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

//Product attribute
Container::make( 'term_meta', 'Фон стикера' )		
	->where( 'term_taxonomy', '=', 'pa_sticker' )
	->add_fields(array(
		Field::make( 'color', 'plt_sticker_color', 'Фон стикера' )->set_palette( ['#ff587b', '#6fe46f', '#71bdee', '#a071ee'] )->set_default_value('#ff587b'),
	));

//Product Category

Container::make( 'term_meta', 'Настройки' )
	->where( 'term_taxonomy', '=', 'product_cat' )
    ->add_tab('Отображение', array(
        Field::make( 'text', 'plt_product_cat_title_short', 'Короткий заголовок' )->set_width(100),
		Field::make( 'checkbox', 'plt_product_cat_title_button', 'Кнопка возле заголовка?' )->set_width(50)->set_option_value( 'no' ),
		Field::make( 'text', 'plt_product_cat_title_button_text', 'Текст кнопки возле заголовка' )->set_width(50),
		Field::make( 'complex', 'plt_product_cat_gallery', 'Изображения сбоку от текста' )
			->set_width(100)
			->add_fields( array(
				Field::make( 'image', 'image', 'Изображение' )->set_width(15),
				Field::make( 'text', 'title', 'Заголовок' )->set_width(85),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
	))
	->add_tab('Блок под текстом', array(		
		Field::make( 'checkbox', 'plt_product_cat_benefits_active', 'Выводить?' )->set_option_value( 'no' ),
		Field::make( 'text', 'plt_product_cat_benefits_title', 'Заголовок' )
			->set_default_value('Почему стоит выбрать нас?')
			->set_conditional_logic([['field' => 'plt_product_cat_benefits_active', 'value' => true]])
			->set_width(100),
		Field::make( 'complex', 'plt_product_cat_benefits_list', 'Список' )
			->set_conditional_logic([['field' => 'plt_product_cat_benefits_active', 'value' => true]])
			->set_width(100)
			->add_fields( array(
				Field::make( 'image', 'image', 'Иконка' )->set_width(20),			
				Field::make( 'text', 'title', 'Заголовок' )->set_width(30),
				Field::make( 'textarea', 'content', 'Текст' )->set_width(50),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
			->set_header_template('<% if (title) { %><%- title %><% } %>'),
	))
	->add_tab('Баннер вверху', $hero_fields)
	->add_tab('Преимущества', array(
		Field::make( 'checkbox', 'plt_product_cat_advantages_active', 'Выводить преимущества?' )->set_option_value( 'yes' ),
		Field::make( 'complex', 'plt_product_cat_advantages', '' )
			->set_conditional_logic([['field' => 'plt_product_cat_advantages_active', 'value' => true]])
			->add_fields( array(
				Field::make( 'image', 'image', 'Иконка' )->set_width(15),
				Field::make( 'text', 'title', 'Заголовок' )->set_width(85),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
			->set_help_text('Если не заполнено - выводятся общие')
	));