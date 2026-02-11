<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

// Delivery	
Container::make( 'theme_options', '<!--plt_theme_options_delivery-->Способы доставки' )
	->set_page_menu_title( 'Способы доставки' )
	->set_icon( 'dashicons-admin-site' )
	->add_fields( array(
		Field::make( 'complex', 'plt_delivery', 'Список' )
			->add_fields( array(
				Field::make( 'image', 'image', 'Иконка' )->set_width(25),
				Field::make( 'text', 'title', 'Заголовок' )->set_width(25),
				Field::make( 'text', 'price', 'Цена' )->set_width(25),
				Field::make( 'text', 'caption', 'Подпись' )->set_width(25),
				Field::make( 'text', 'link', 'Ссылка' )->set_width(25),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
			->set_header_template('
				<% if (title) { %>
					<%- title %>
				<% } %>
			'),
	)); 

// Advantages common	
Container::make( 'theme_options', '<!--plt_theme_options_advantages-->Преимущества' )
	->set_page_menu_title( 'Преимущества' )
	->set_icon( 'dashicons-editor-ol' )
	->add_fields( array(
		Field::make( 'complex', 'plt_advantages', 'Список' )
			->add_fields( array(
				Field::make( 'image', 'image', 'Иконка' )->set_width(15),
				Field::make( 'text', 'title', 'Заголовок' )->set_width(85),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
	)); 
