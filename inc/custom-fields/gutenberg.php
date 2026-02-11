<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

Block::make( 'pallet-price-table', __( 'Таблица цен' ) )
	->add_fields( array(
		Field::make( 'text', 'heading', __( 'Заголовок таблицы' ) ),
		Field::make('complex', 'table', 'Элементы таблицы')
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
			->add_fields(array(
				Field::make('text', 'label', 'Название')->set_width(50),
				Field::make('text', 'price', 'Цена')->set_width(50),
			))
			->set_header_template('
			<% if (label) { %>
				<%- label %>
			<% } %>
			')
	) )
	->set_icon( 'editor-table' )
	->set_render_callback( function($block) { plt_site()->tpl('/parts/price-table', $block); } );
	
Block::make( 'pallet-benefits', 'Преимущества' )
	->add_fields(array(
		Field::make( 'text', 'title', 'Заголовок' )
			->set_default_value('Почему стоит выбрать нас?'),
		Field::make( 'complex', 'list', 'Список' )
			->add_fields( array(
				Field::make( 'image', 'image', 'Иконка' )->set_width(20),			
				Field::make( 'text', 'title', 'Заголовок' )->set_width(30),
				Field::make( 'textarea', 'content', 'Текст' )->set_width(50),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
			->set_header_template('<% if (title) { %><%- title %><% } %>'),
	))
	->set_icon( 'megaphone' )
	->set_render_callback( function($block) { plt_site()->tpl('/parts/box-benefits', $block); } );	
	
Block::make( 'pallet-documents', 'Документы' )
	->add_fields(array(
		Field::make( 'complex', 'list', 'Список' )
			->add_fields( array(
				Field::make('file', 'file', 'Файл')->set_width(15)->set_required( true )->set_help_text( 'Рекомендуемые форматы: pdf, doc, docx, xls, xlsx'),
				Field::make('text', 'title', 'Название')->set_width(85)->set_required( true ),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
			->set_header_template('<% if (title) { %><%- title %><% } %>'),
	))
	->set_icon( 'media-default' )
	->set_render_callback( function($block) { plt_site()->tpl('/parts/box-document', $block); } );		