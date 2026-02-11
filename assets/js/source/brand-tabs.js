const $ = jQuery.noConflict();

(() => {
    const $window = $(window);

    class Brand_Tabs {
        constructor() {
            this.init();
        }

        init() {
            const self = this;

            // Обработка клика по табам
            $(document).on('click', '.brand-tabs__nav a', function(e) {
                e.preventDefault();
                const target = $(this).attr('href');
                
                // Убираем активный класс со всех табов
                $('.brand-tabs__nav li').removeClass('active');
                $(this).parent().addClass('active');
                
                // Скрываем все контенты табов
                $('.brand-tab-content').removeClass('active');
                
                // Показываем выбранный таб
                $(target).addClass('active');
                
                // Если это таб с картой, инициализируем карту если еще не инициализирована
                if (target === '#geography' && $('#brand-map').length) {
                    setTimeout(function() {
                        // Карта инициализируется автоматически при загрузке страницы
                        // Если карта не загрузилась, можно перезагрузить страницу или вызвать init вручную
                        if (typeof ymaps !== 'undefined' && !myMap) {
                            // Переинициализация карты
                            location.reload();
                        }
                    }, 100);
                }
            });
        }
    }

    $window.on('DOMContentLoaded', function() {
        if ($('.brand-tabs').length) {
            new Brand_Tabs();
        }
    });

})();
