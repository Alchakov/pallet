# Реализация фильтрации по региону, городу и карте

## Что уже сделано

1. ✅ **term-meta.php** - Добавлены поля координат для регионов:
   - `plt_region_lat` - Широта
   - `plt_region_lng` - Долгота  
   - `plt_region_zoom` - Масштаб карты (zoom)

## Что нужно сделать

### 1. Обновить templates/brand/archive-brand.php

Найти секцию `<div class="brands-results__header">` (примерно строка 130-160) и заменить на:

```php
<div class="brands-results__header">
    <div class="brands-location-filter">
        <div class="location-filter__region">
            <label for="brands-region-select" class="location-filter__label">
                <svg class="location-filter__icon" width="16" height="16" fill="currentColor">
                    <use xlink:href="#icon-location"></use>
                </svg>
                Регион
            </label>
            <select id="brands-region-select" class="location-filter__select">
                <option value="">Вся Россия</option>
                <?php
                $regions = get_terms(array(
                    'taxonomy' => 'region',
                    'hide_empty' => false,
                ));
                foreach ($regions as $region) :
                    $region_lat = carbon_get_term_meta($region->term_id, 'plt_region_lat');
                    $region_lng = carbon_get_term_meta($region->term_id, 'plt_region_lng');
                    $region_zoom = carbon_get_term_meta($region->term_id, 'plt_region_zoom') ?: '10';
                ?>
                    <option 
                        value="<?php echo esc_attr($region->term_id); ?>"
                        data-lat="<?php echo esc_attr($region_lat); ?>"
                        data-lng="<?php echo esc_attr($region_lng); ?>"
                        data-zoom="<?php echo esc_attr($region_zoom); ?>">
                        <?php echo esc_html($region->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="location-filter__city">
            <label for="brands-city-input" class="location-filter__label">
                <svg class="location-filter__icon" width="16" height="16" fill="currentColor">
                    <use xlink:href="#icon-pin"></use>
                </svg>
                Населённый пункт
            </label>
            <input
                type="text"
                id="brands-city-input"
                class="location-filter__input"
                placeholder="Введите город..."
            >
        </div>
        
        <button class="location-filter__map-button" id="show-map-button">
            <svg class="location-filter__icon" width="20" height="20" fill="currentColor">
                <use xlink:href="#icon-map"></use>
            </svg>
            Показать на карте
        </button>
    </div>
    
    <div class="brands-results__view">
        <button class="brands-view-btn active" data-view="grid" id="view-grid">
            <span class="icon-grid"></span>
        </button>
        <button class="brands-view-btn" data-view="list" id="view-list">
            <span class="icon-list"></span>
        </button>
    </div>
</div>
```

### 2. Добавить CSS стили (assets/css/brands.css)

Добавьте в конец файла:

```css
/* Фильтр по местоположению */
.brands-location-filter {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.location-filter__region,
.location-filter__city {
    flex: 1;
    min-width: 200px;
}

.location-filter__label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.location-filter__icon {
    color: #ffc800;
}

.location-filter__select,
.location-filter__input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #fff;
}

.location-filter__select:hover,
.location-filter__input:hover {
    border-color: #ffc800;
}

.location-filter__select:focus,
.location-filter__input:focus {
    outline: none;
    border-color: #ffc800;
    box-shadow: 0 0 0 3px rgba(255, 200, 0, 0.1);
}

.location-filter__map-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #ffc800 0%, #ffb800 100%);
    color: #000;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 200, 0, 0.3);
}

.location-filter__map-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 200, 0, 0.4);
}

.location-filter__map-button:active {
    transform: translateY(0);
}

/* Адаптивность */
@media (max-width: 768px) {
    .brands-location-filter {
        flex-direction: column;
    }
    
    .location-filter__region,
    .location-filter__city {
        width: 100%;
    }
    
    .location-filter__map-button {
        width: 100%;
        justify-content: center;
    }
}
```

### 3. Добавить JavaScript (assets/js/source/brands-map.js)

Добавьте в конец файла или создайте новый файл:

```javascript
// Фильтрация по региону и городу
(function($) {
    'use strict';
    
    const BrandsLocationFilter = {
        init: function() {
            this.regionSelect = $('#brands-region-select');
            this.cityInput = $('#brands-city-input');
            this.mapButton = $('#show-map-button');
            this.mapContainer = $('#brands-map-container');
            
            this.bindEvents();
        },
        
        bindEvents: function() {
            const self = this;
            
            // При выборе региона
            this.regionSelect.on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const regionId = selectedOption.val();
                
                if (regionId) {
                    // Фильтрация брендов по региону
                    self.filterBrandsByRegion(regionId);
                } else {
                    // Показать все бренды
                    self.showAllBrands();
                }
            });
            
            // При вводе города
            this.cityInput.on('input', $.debounce(300, function() {
                const city = $(this).val().trim();
                if (city.length >= 2) {
                    self.filterBrandsByCity(city);
                } else if (city.length === 0) {
                    self.filterBrandsByRegion(self.regionSelect.val());
                }
            }));
            
            // При нажатии кнопки "Показать на карте"
            this.mapButton.on('click', function(e) {
                e.preventDefault();
                self.scrollToMap();
                self.updateMapView();
            });
        },
        
        filterBrandsByRegion: function(regionId) {
            // AJAX запрос для фильтрации
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_brands_by_location',
                    region_id: regionId
                },
                beforeSend: function() {
                    $('#brands-list').addClass('loading');
                },
                success: function(response) {
                    if (response.success) {
                        $('#brands-list').html(response.data.html);
                        $('#brands-count').text(response.data.count_text);
                    }
                },
                complete: function() {
                    $('#brands-list').removeClass('loading');
                }
            });
        },
        
        filterBrandsByCity: function(city) {
            const regionId = this.regionSelect.val();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_brands_by_location',
                    region_id: regionId,
                    city: city
                },
                beforeSend: function() {
                    $('#brands-list').addClass('loading');
                },
                success: function(response) {
                    if (response.success) {
                        $('#brands-list').html(response.data.html);
                        $('#brands-count').text(response.data.count_text);
                    }
                },
                complete: function() {
                    $('#brands-list').removeClass('loading');
                }
            });
        },
        
        showAllBrands: function() {
            // Перезагрузка страницы без параметров
            window.location.href = window.location.pathname;
        },
        
        scrollToMap: function() {
            $('html, body').animate({
                scrollTop: this.mapContainer.offset().top - 100
            }, 800);
        },
        
        updateMapView: function() {
            const selectedOption = this.regionSelect.find('option:selected');
            const lat = parseFloat(selectedOption.data('lat'));
            const lng = parseFloat(selectedOption.data('lng'));
            const zoom = parseInt(selectedOption.data('zoom')) || 10;
            
            if (lat && lng && window.brandsMap) {
                // Обновление центра и зума карты
                window.brandsMap.setCenter([lat, lng], zoom);
                
                // Фильтрация маркеров на карте
                this.filterMapMarkers();
            }
        },
        
        filterMapMarkers: function() {
            const regionId = this.regionSelect.val();
            const city = this.cityInput.val().trim();
            
            // Логика фильтрации маркеров на карте
            if (window.brandsMap && window.brandMarkers) {
                window.brandMarkers.forEach(function(marker) {
                    const markerRegion = marker.properties.get('regionId');
                    const markerCity = marker.properties.get('city');
                    
                    let shouldShow = true;
                    
                    if (regionId && markerRegion != regionId) {
                        shouldShow = false;
                    }
                    
                    if (city && markerCity && !markerCity.toLowerCase().includes(city.toLowerCase())) {
                        shouldShow = false;
                    }
                    
                    if (shouldShow) {
                        marker.options.set('visible', true);
                    } else {
                        marker.options.set('visible', false);
                    }
                });
            }
        }
    };
    
    // Инициализация при загрузке страницы
    $(document).ready(function() {
        BrandsLocationFilter.init();
    });
    
})(jQuery);

// Debounce helper
jQuery.debounce = function(delay, fn) {
    var timer = null;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() {
            fn.apply(context, args);
        }, delay);
    };
};
```
