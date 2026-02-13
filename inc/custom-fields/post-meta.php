<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

//Video
Container::make('post_meta', 'Настройки')
 ->where('post_type', '=', 'video')
 ->add_fields(array(
 Field::make('image', 'plt_video_image', 'Изображение' )->set_help_text('По умолчанию вставляется с YouTube'), 
 Field::make('image', 'plt_video_image_hover', 'Изображение при наведении')->set_help_text('По умолчанию вставляется с YouTube'),
 Field::make('oembed', 'plt_video_embed', 'Ссылка на видео' )->set_required()
 ));

//Office 
Container::make('post_meta', 'Настройки' )
 ->where( 'post_type', '=', 'office' )
 ->add_fields(array(
 Field::make('radio', 'plt_office_type', 'Тип' )->set_options(array('office' => 'Офис', 'warehouse' => 'Склад')),
 Field::make('text', 'plt_office_address', 'Адрес' ),
 Field::make('text', 'plt_office_phone', 'Телефон' ),
 Field::make('text', 'plt_office_email', 'E-mail' ),
 Field::make('text', 'plt_office_timetable', 'Режим работы'), 
 ));

//Review 
Container::make('post_meta', 'Настройки' )
 ->where( 'post_type', '=', 'review' )
 ->add_tab('Бренд', array(
 Field::make( 'association', 'plt_review_brand', 'Привязка к бренду' )
 ->set_types(array('brand'))
 ))
 ->add_fields(array(
 Field::make('radio', 'plt_review_type', 'Тип' )->set_options(array('photo' => 'Фото', 'video' => 'Видео', 'text' => 'Текст')),
 Field::make('text', 'plt_review_video_caption', 'Подпись')
 ->set_conditional_logic([['field' => 'plt_review_type', 'value' => 'video']]),
 Field::make('image', 'plt_review_image', 'Изображение'), 
 Field::make('image', 'plt_review_video_image_hover', 'Изображение при наведении')
 ->set_conditional_logic([['field' => 'plt_review_type', 'value' => 'video']]),
 Field::make('oembed', 'plt_review_video_embed', 'Ссылка на видео' )
 ->set_conditional_logic([['field' => 'plt_review_type', 'value' => 'video']]),
 Field::make('rich_text', 'plt_review_text_content', 'Текст' )
 ->set_conditional_logic([['field' => 'plt_review_type', 'value' => 'text']]),
 Field::make('radio', 'plt_review_rating', 'Оценка' )
 ->set_options(array('1', '2', '3', '4', '5'))
 ));

//Discount
Container::make( 'post_meta', 'Подпись сверху')
 ->show_on_post_type('discount')
 ->add_fields(array(
 Field::make( 'text', 'plt_discount_caption', '' ),
 ));
Container::make( 'post_meta', 'Дата и время показа' )
 ->show_on_post_type('discount')
 ->add_fields(array(
 Field::make( 'date', 'plt_discount_start', 'Начало' )
 ->set_required( true )
 ->set_input_format( 'd.m.Y', 'd.m.Y' )
 ->set_picker_options(array('locale' => carbon_date_local_ru())),
 Field::make( 'date', 'plt_discount_end', 'Завершениe' )
 ->set_required( true )
 ->set_input_format( 'd.m.Y', 'd.m.Y' )
 ->set_picker_options(array('locale' => carbon_date_local_ru())),
 ));

//Worker
Container::make( 'post_meta', 'Информация')
 ->show_on_post_type('worker')
 ->add_fields(array( 
 Field::make( 'text', 'plt_worker_position', 'Должность' ),
 ));

//Brand (Производитель)
Container::make( 'post_meta', 'Основная информация' )
 ->show_on_post_type('brand')
 ->add_tab('Контакты', array(
 Field::make( 'text', 'plt_brand_phone', 'Телефон' ),
 Field::make( 'text', 'plt_brand_email', 'E-mail' ),
 Field::make( 'text', 'plt_brand_website', 'Сайт' ),
 Field::make( 'text', 'plt_brand_address', 'Адрес и город' )
 ->set_help_text('Например: село Павловская Слобода, Истра, Московская область'),
 Field::make( 'text', 'plt_brand_work_hours', 'График работы' ),
 ))
 ->add_tab('Карта', array(
 Field::make( 'text', 'plt_brand_map_address', 'Адрес для карты' )
 ->set_help_text('Введите адрес, координаты определятся автоматически'),
 Field::make( 'text', 'plt_brand_map_coords', 'Координаты (широта,долгота)' )
 ->set_help_text('Формат: 55.751279,37.621131 или оставьте пустым для автозаполнения'),
 Field::make( 'number', 'plt_brand_map_zoom', 'Масштаб карты' )
 ->set_help_text('Число от 5 (вся Россия) до 18 (улица)')
 ->set_default_value(15),
 ))
 ->add_tab('Рейтинг и отзывы', array(
 Field::make( 'radio', 'plt_brand_rating_source', 'Источник рейтинга' )
 ->set_options(array(
 'reviews' => 'На основе отзывов',
 'yandex' => 'Виджет Яндекс',
 ))
 ->set_default_value('reviews'),
 Field::make( 'textarea', 'plt_brand_rating_widget', 'Код виджета Яндекс' )
 ->set_help_text('Вставьте HTML/JS код виджета рейтинга из Яндекса')
 ->set_rows(4)
 ->set_conditional_logic(array(
 array(
 'field' => 'plt_brand_rating_source',
 'value' => 'yandex',
 ),
 )),
 ))
 ->add_tab('Галерея', array(
 Field::make( 'media_gallery', 'plt_brand_gallery', 'Фотогалерея' ),
 ));
