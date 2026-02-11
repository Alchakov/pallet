const $ = jQuery.noConflict();

(() => {
    const $window = $(window);
    let currentPage = 1;
    let isLoading = false;

    class Brands_Filter {
        constructor() {
            this.$container = $('#brands-list');
            this.$count = $('#brands-count');
            this.$searchInput = $('#brands-search-input');
            this.init();
        }

        init() {
            const self = this;

            // Поиск с задержкой
            let searchTimeout;
            this.$searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    self.loadBrands();
                }, 500);
            });

            // Фильтры
            $(document).on('change', '.brands-filters input', function() {
                currentPage = 1;
                self.loadBrands();
            });

            // Переключение вида (grid/list)
            $(document).on('click', '.brands-view-btn', function(e) {
                e.preventDefault();
                const view = $(this).data('view');
                $('.brands-view-btn').removeClass('active');
                $(this).addClass('active');
                self.$container.attr('data-view', view);
            });

            // Кнопка сброса фильтров
            $(document).on('click', '#reset-filters', function() {
                $('.brands-filters input').prop('checked', false);
                self.$searchInput.val('');
                currentPage = 1;
                self.loadBrands();
            });

            // Кнопка показать/скрыть фильтры
            $(document).on('click', '#toggle-filters-btn', function() {
                const $sidebar = $('#brands-filters');
                if ($sidebar.is(':visible')) {
                    $sidebar.slideUp();
                    $(this).find('.btn__text').text('Показать фильтры');
                } else {
                    $sidebar.slideDown();
                    $(this).find('.btn__text').text('Скрыть фильтры');
                }
            });
        }

        getFilters() {
            const filters = {};
            
            // Регионы
            const regions = [];
            $('input[name="region[]"]:checked').each(function() {
                regions.push($(this).val());
            });
            if (regions.length) filters.regions = regions.join(',');

            // Категории (из URL или активной ссылки)
            const activeCategory = $('.brands-filters__nav a.active');
            if (activeCategory.length) {
                const href = activeCategory.attr('href');
                const match = href.match(/kategorii-proizvoditeley\/([^\/]+)/);
                if (match) {
                    filters.categories = match[1];
                }
            }

            // Минимальная поставка
            const minOrders = [];
            $('input[name="min_order[]"]:checked').each(function() {
                minOrders.push($(this).val());
            });
            if (minOrders.length) filters.min_order = minOrders.join(',');

            // Кастомизация
            const customizations = [];
            $('input[name="customization[]"]:checked').each(function() {
                customizations.push($(this).val());
            });
            if (customizations.length) filters.customization = customizations.join(',');

            // Есть прайс
            if ($('input[name="has_price"]:checked').length) {
                filters.has_price = 1;
            }

            // Поиск
            const search = this.$searchInput.val().trim();
            if (search) {
                filters.search = search;
            }

            filters.paged = currentPage;
            filters.per_page = 12;

            return filters;
        }

        loadBrands() {
            const self = this;
            
            if (isLoading) return;
            isLoading = true;

            const filters = this.getFilters();
            const queryString = $.param(filters);

            // Показываем индикатор загрузки
            this.$container.addClass('loading');

            $.ajax({
                url: '/wp-json/pallet/v1/brands-filter',
                method: 'GET',
                data: filters,
                dataType: 'json',
                success: function(response) {
                    self.$container.removeClass('loading');
                    self.$container.html(response.brands);
                    self.$count.text('Найдено ' + response.found_posts + ' производителей');
                    
                    // Обновляем пагинацию если нужно
                    // Можно добавить пагинацию через AJAX здесь
                },
                error: function() {
                    self.$container.removeClass('loading');
                    self.$container.html('<p>Ошибка загрузки данных</p>');
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }
    }

    $window.on('DOMContentLoaded', function() {
        // Инициализируем фильтр только на странице архива брендов
        if ($('#brands-list').length) {
            new Brands_Filter();
        }
    });

})();
