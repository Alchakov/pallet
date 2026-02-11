<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;


$front_page_options_container = Container::make( 'theme_options', '<!--plt_front_page_options-->Настройки главной страницы' )
	->set_icon( 'dashicons-admin-appearance' )
	->set_page_menu_title( 'Настройки главной страницы' )
	->add_fields(array(
		Field::make( 'html', 'plt_front_options' )
    		->set_html( '
				<ul>
					<li><a href="/wp-admin/admin.php?page=crb_carbon_fields_container_-plt_front_page_options_slider.php">Слайдшоу</a></li>
					<li><a href="/wp-admin/admin.php?page=crb_carbon_fields_container_-plt_front_page_options_catalog.php">Каталог</a></li>
					<li><a href="/wp-admin/admin.php?page=crb_carbon_fields_container_-plt_front_page_options_offer.php">Спец. предложение</a></li>
					<li><a href="/wp-admin/admin.php?page=crb_carbon_fields_container_-plt_front_page_options_steps-5.php">5 пунктов</a></li>
					<li><a href="/wp-admin/admin.php?page=crb_carbon_fields_container_-plt_front_page_options_reviews.php">Отзывы</a></li>
					<li><a href="/wp-admin/admin.php?page=crb_carbon_fields_container_-plt_front_page_options_clients.php">Клиенты</a></li>
					<li><a href="/wp-admin/admin.php?page=crb_carbon_fields_container_-plt_front_page_options_videos.php">Видео</a></li>
				</ul>
			' )
    ));
										
Container::make( 'theme_options', '<!--plt_front_page_options_slider-->Слайдшоу' )
    ->set_page_menu_title( 'Слайдшоу' )
    ->set_page_parent( $front_page_options_container )
    ->add_fields( array(
        Field::make( 'checkbox', 'plt_front_slider_active', 'Выводить?' ),
        Field::make( 'complex', 'plt_front_slider', 'Объекты' )
            ->add_fields( array(
                Field::make( 'text', 'title', 'Заголовок' )->set_width(50),
                Field::make( 'text', 'caption', 'Подпись' )->set_width(50),
                Field::make( 'text', 'button', 'Текст кнопки' )->set_width(50),
                Field::make( 'text', 'button_link', 'Ссылка кнопки' )->set_width(50),
                Field::make( 'image', 'background', 'Фон' )->set_width(20),
                Field::make( 'image', 'image', 'Изображение' )->set_width(20),

                // -------------------------------------
                // ВЫБОР ТИПА СЛАЙДА
                // -------------------------------------
                Field::make( 'select', 'type', 'Тип' )
                    ->set_options( array(
                        'image'      => 'Изображение',
                        'video'      => 'Видео (YouTube)',
                        'video_file' => 'Видео (по ссылке)'
                    ) )
                    ->set_width(60),

                // -------------------------------------
                // СТАРОЕ ПОЛЕ — YouTube (oEmbed)
                // -------------------------------------
                Field::make( 'oembed', 'video', 'Видео YouTube' )
                    ->set_conditional_logic([
                        [
                            'field' => 'type',
                            'value' => 'video'
                        ]
                    ])
                    ->set_width(80),

                // -------------------------------------
                // НОВОЕ ПОЛЕ — Видео по прямой ссылке
                // -------------------------------------
                Field::make( 'text', 'video_file', 'Ссылка на видео-файл' )
                    ->set_conditional_logic([
                        [
                            'field' => 'type',
                            'value' => 'video_file'
                        ]
                    ])
                    ->set_width(80),

                // -------------------------------------
                // HOVER — показывается и для YouTube, и для file-видео
                // -------------------------------------
                Field::make( 'image', 'video_image_hover', 'Изображение при наведении' )
                    ->set_width(20)
                    ->set_conditional_logic([
                        'relation' => 'OR',
                        [
                            'field' => 'type',
                            'value' => 'video'
                        ],
                        [
                            'field' => 'type',
                            'value' => 'video_file'
                        ],
                    ]),
            ))
            ->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
            ->set_header_template('
                <% if (title) { %>
                    <%- title %>
                <% } %>
            '),
    ));


Container::make( 'theme_options', '<!--plt_front_page_options_catalog-->Каталог' )
	->set_page_menu_title( 'Каталог' )
    ->set_page_parent( $front_page_options_container )
    ->add_fields( array(
        Field::make( 'checkbox', 'plt_front_catalog_active', 'Выводить?' ),
	    Field::make( 'text', 'plt_front_catalog_title', 'Заголовок' ),
		Field::make( 'association', 'plt_front_catalog_items' )
		    ->set_types( array(
		        array(
		            'type' => 'post',
		            'post_type' => 'product',
		        ),
		    ) )
    ) );
	
Container::make( 'theme_options', '<!--plt_front_page_options_offer-->Спец. предложение' )
	->set_page_menu_title( 'Спец. предложение' )
    ->set_page_parent( $front_page_options_container )
    ->add_fields( array(
        Field::make( 'checkbox', 'plt_front_offer_active', 'Выводить?' ),
		Field::make( 'image', 'plt_front_offer_image', 'Изображение' )
			->set_width(15),
	    Field::make( 'text', 'plt_front_offer_type', 'Тип' )
			->set_width(25),
	    Field::make( 'text', 'plt_front_offer_title', 'Заголовок' )
			->set_width(60),	
		Field::make( 'complex', 'plt_front_offer_list', 'Список' )
			->add_fields( array(
				Field::make( 'image', 'image', 'Иконка' )->set_width(15),
				Field::make( 'text', 'text', 'Подпись' )->set_width(85),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
			->set_header_template('<% if (text) { %><%- text %><% } %>'),
    ) );
	
Container::make( 'theme_options', '<!--plt_front_page_options_steps-->5 пунктов' )
	->set_page_menu_title( '5 пунктов' )
    ->set_page_parent( $front_page_options_container )
    ->add_tab('Настройки', array(
        Field::make( 'checkbox', 'plt_front_steps_active', 'Выводить?' ),
		Field::make( 'text', 'plt_front_steps_title', 'Заголовок' ),
		Field::make( 'text', 'plt_front_steps_subtitle', 'Подзаголовок' ),
    ))
	->add_tab('Блок №1', array(
        Field::make( 'text', 'plt_front_steps_step_1_title', 'Заголовок' ),
		Field::make( 'textarea', 'plt_front_steps_step_1_content', 'Контент' ),
    ))
	->add_tab('Блок №2', array(
        Field::make( 'text', 'plt_front_steps_step_2_title', 'Заголовок' ),
		Field::make( 'textarea', 'plt_front_steps_step_2_content', 'Контент' ),
    ))
	->add_tab('Блок №3', array(
        Field::make( 'text', 'plt_front_steps_step_3_title', 'Заголовок' ),
		Field::make( 'textarea', 'plt_front_steps_step_3_content', 'Контент' ),
    ))	
	->add_tab('Блок №4', array(
        Field::make( 'text', 'plt_front_steps_step_4_title', 'Заголовок' ),
		Field::make( 'textarea', 'plt_front_steps_step_4_content', 'Контент' ),
    ))
	->add_tab('Блок №5', array(
        Field::make( 'text', 'plt_front_steps_step_5_title', 'Заголовок' ),
		Field::make( 'textarea', 'plt_front_steps_step_5_content', 'Контент' ),
    ));

Container::make( 'theme_options', '<!--plt_front_page_options_reviews-->Отзывы' )
	->set_page_menu_title( 'Отзывы' )
    ->set_page_parent( $front_page_options_container )
    ->add_fields( array(
        Field::make( 'checkbox', 'plt_front_reviews_active', 'Выводить?' ),
	    Field::make( 'text', 'plt_front_reviews_title', 'Заголовок' ),
	    Field::make( 'text', 'plt_front_reviews_video_title', 'Заголовок для видеоотзывов' ),
		Field::make( 'association', 'plt_front_reviews_video_items', 'Объекты' )
		    ->set_types([['type' => 'post', 'post_type' => 'review']]),
		Field::make( 'text', 'plt_front_reviews_photo_title', 'Заголовок для фотоотзывов' ),
    	Field::make( 'association', 'plt_front_reviews_photo_items', 'Объекты')
    		 ->set_types([['type' => 'post', 'post_type' => 'review']]),
		Field::make( 'text', 'plt_front_reviews_text_title', 'Заголовок для текстовых отзывов' ),
		Field::make( 'association', 'plt_front_reviews_text_items', 'Объекты' )
			 ->set_types([['type' => 'post', 'post_type' => 'review']])
    ) );	
	
Container::make( 'theme_options', '<!--plt_front_page_options_clients-->Клиенты' )
	->set_page_menu_title( 'Клиенты' )
    ->set_page_parent( $front_page_options_container )
    ->add_fields( array(
        Field::make( 'checkbox', 'plt_front_clients_active', 'Выводить?' ),
	    Field::make( 'text', 'plt_front_clients_title', 'Заголовок' ),
		Field::make( 'association', 'plt_front_clients_items' )
		    ->set_types( array(
		        array(
		            'type' => 'post',
		            'post_type' => 'client',
		        ),
		    ) )
    ) );	


Container::make( 'theme_options', '<!--plt_front_page_options_videos-->Видео' )
	->set_page_menu_title( 'Видео' )
    ->set_page_parent( $front_page_options_container )
    ->add_fields( array(
        Field::make( 'checkbox', 'plt_front_videos_active', 'Выводить?' ),
	    Field::make( 'text', 'plt_front_videos_title', 'Заголовок' ),
	    Field::make( 'textarea', 'plt_front_videos_text', 'Описание' ),
	    Field::make( 'text', 'plt_front_videos_subtitle', 'Подзаголовок' ),
		Field::make( 'association', 'plt_front_videos_items' )
		    ->set_types( array(
		        array(
		            'type' => 'post',
		            'post_type' => 'video',
		        ),
		    ) )
    ) );	
	
	
	
Container::make( 'post_meta', 'Изображения сбоку от текста' )
	->where( 'post_id', '=', 9 )
	->add_fields(array(
		Field::make( 'complex', 'plt_front_gallery', '' )
			->set_width(100)
			->add_fields( array(
				Field::make( 'image', 'image', 'Изображение' )->set_width(15),
				Field::make( 'text', 'title', 'Заголовок' )->set_width(85),
			))
			->setup_labels(array('plural_name' => 'ничего', 'singular_name' => ''))
	));		
	