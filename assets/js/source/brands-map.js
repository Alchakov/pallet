const $ = jQuery.noConflict();
(() => {
 const $window = $(window);
 let myMap;
 let brandsData;
 let objectManager;
 let baloonParametrs;
 let MyBalloonLayout;
 let MyBalloonContentLayout;
 let pins = {};

 class Brands_Map {
 constructor() {
 this.mapContainer = $('#brands-map');
 this.mapContainerSingle = $('#brand-map');
 }

 init = () => {
 const self = this;
 // Проверяем наличие контейнера карты
 if (!$('#brands-map').length && !$('#brand-map').length) {
 return;
 }

 ymaps.ready(function() {
 // Определяем контейнер карты
 const $brandsMap = $('#brands-map');
 const $brandMap = $('#brand-map');
 const containerId = $brandsMap.length ? 'brands-map' : ($brandMap.length ? 'brand-map' : null);
 
 if (!containerId) return;
 
 const isSingle = containerId === 'brand-map';
 
 // Стартовый центр (центр России) или Москва
 const defaultCenter = [61.52, 105.32]; // Центр России
 const defaultZoom = 5; // Для видимости всей России
 
 myMap = new ymaps.Map(containerId, {
 center: defaultCenter,
 zoom: isSingle ? 15 : defaultZoom,
 controls: ['zoomControl']
 });
 
 myMap.behaviors.disable('scrollZoom');
 
 objectManager = new ymaps.ObjectManager({
 clusterize: !isSingle,
 gridSize: 32,
 clusterDisableClickZoom: false
 });
 
 // Загружаем данные брендов
 self.loadBrandsData().then(function(data) {
 brandsData = data;
 self.baloonConfig();
 self.objectManagerConfig();
 myMap.geoObjects.add(objectManager);
 self.addPoints();
 objectManager.objects.events.add(['click'], self.moveToPoint);
 self.baloonTrigger();
 });
 });
 }

 loadBrandsData = () => {
 const self = this;
 const isSingle = this.mapContainerSingle.length > 0;
 
 if (isSingle) {
 // Для страницы одного бренда загружаем только его данные
 const brandId = $('body').data('brand-id') || null;
 if (!brandId) return Promise.resolve([]);
 
 return $.ajax({
 url: '/wp-json/pallet/v1/brand-map/' + brandId,
 method: 'GET',
 dataType: 'json'
 });
 } else {
 // Для архива загружаем все бренды или отфильтрованные
 const filters = self.getFilters();
 return $.ajax({
 url: '/wp-json/pallet/v1/brands-map',
 method: 'GET',
 data: filters,
 dataType: 'json'
 });
 }
 }

 getFilters = () => {
 const filters = {};
 
 // Регионы
 const regions = [];
 $('input[name="region[]"]:checked').each(function() {
 regions.push($(this).val());
 });
 if (regions.length) filters.regions = regions;
 
 // Категории
 const categories = [];
 $('.brands-filters__nav a.active').each(function() {
 const href = $(this).attr('href');
 const match = href.match(/kategorii-proizvoditeley\/([^\/]+)/);
 if (match) categories.push(match[1]);
 });
 if (categories.length) filters.categories = categories;
 
 // Минимальная поставка
 const minOrders = [];
 $('input[name="min_order[]"]:checked').each(function() {
 minOrders.push($(this).val());
 });
 if (minOrders.length) filters.min_order = minOrders;
 
 // Кастомизация
 const customizations = [];
 $('input[name="customization[]"]:checked').each(function() {
 customizations.push($(this).val());
 });
 if (customizations.length) filters.customization = customizations;
 
 // Есть прайс
 if ($('input[name="has_price"]:checked').length) {
 filters.has_price = 1;
 }
 
 return filters;
 }

 objectManagerConfig = () => {
 const self = this;
 objectManager = new ymaps.ObjectManager({
 clusterize: this.mapContainer.length > 0,
 gridSize: 32,
 clusterDisableClickZoom: false
 });
 
 let settingObj = {
 iconLayout: 'default#imageWithContent',
 iconImageSize: [40, 40],
 iconImageOffset: [-20, -20],
 balloonShadow: true,
 balloonLayout: MyBalloonLayout,
 balloonContentLayout: MyBalloonContentLayout,
 balloonPanelMaxMapArea: 0,
 hideIconOnBalloonOpen: false,
 balloonOffset: baloonParametrs['offset'],
 };
 
 let settingObjCl = {
 preset: 'islands#redClusterIcons',
 };
 
 objectManager.objects.options.set(settingObj);
 if (this.mapContainer.length > 0) {
 objectManager.clusters.options.set(settingObjCl);
 }
 }

 addPoints = () => {
 let pointList = {};
 pointList["type"] = "FeatureCollection";
 pointList["features"] = [];
 let item;
 
 $.each(brandsData, function (key, data) {
 if (!data.coords) return;
 pins[data.id] = '/wp-content/themes/pallet/assets/images/pin-brand.png';
 
 const coords = data.coords.split(',');
 if (coords.length !== 2) return;
 
 item = {
 'type': 'Feature',
 'id': data.id,
 'geometry': {
 'type': 'Point',
 'coordinates': [parseFloat(coords[1]), parseFloat(coords[0])],
 },
 'properties': {
 'balloonId': data.id,
 'balloonTitle': data.title,
 'balloonUrl': data.url,
 },
 'options': {
 'iconImageHref': pins[data.id],
 }
 };
 pointList["features"].push(item);
 });
 
 if (pointList["features"].length > 0) {
 objectManager.add(pointList);
 
 // Автоматически подстраиваем карту под все точки
 if (pointList["features"].length > 1) {
 const bounds = objectManager.getBounds();
 if (bounds) {
 myMap.setBounds(bounds, {
 checkZoomRange: true,
 duration: 300,
 zoomMargin: 50
 });
 }
 } else if (pointList["features"].length === 1) {
 const coords = pointList["features"][0].geometry.coordinates;
 myMap.setCenter([coords[1], coords[0]], 15);
 }
 }
 }

 moveToPoint = (e) => {
 if (e.get('type') == 'click') {
 let objectId = e.get('objectId'),
 obj = objectManager.objects.getById(objectId),
 coords = obj.geometry.coordinates;
 myMap.setCenter([coords[1], coords[0]], myMap.getZoom());
 }
 }

 baloonTrigger = () => {
 let objects = objectManager.objects;
 objects.events
 .add(['balloonopen'], function (e) {
 let objectId = e.get('objectId');
 objects.setObjectOptions(objectId, {
 iconImageHref: '/wp-content/themes/pallet/assets/images/pin-brand-active.png',
 });
 })
 .add(['balloonclose'], function (e) {
 let objectId = e.get('objectId');
 objects.setObjectOptions(objectId, {
 iconImageHref: pins[objectId],
 });
 });
 }

 baloonConfig = () => {
 baloonParametrs = {
 content: '<div class="brand-baloon__title">$[properties.balloonTitle]</div>',
 offset: [0, 0]
 };
 
 MyBalloonLayout = ymaps.templateLayoutFactory.createClass(
 '<div class="brand-baloon">' +
 '<div class="brand-baloon__arrow"></div>' +
 '$[[options.contentLayout]]' +
 '</div>', {
 build: function() {
 this.constructor.superclass.build.call(this);
 this._$element = $('.brand-baloon', this.getParentElement());
 this.applyElementOffset();
 this._$element.find('.close').on('click', $.proxy(this.onCloseClick, this));
 },
 clear: function() {
 this._$element.find('.close').off('click');
 this.constructor.superclass.clear.call(this);
 },
 onSublayoutSizeChange: function() {
 MyBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);
 if (!this._isElement(this._$element)) {
 return;
 }
 this.applyElementOffset();
 this.events.fire('shapechange');
 },
 applyElementOffset: function() {
 this._$element.css({
 left: ($(window).width() > 767) ? -220 : -110,
 top: 20
 });
 },
 onCloseClick: function(e) {
 e.preventDefault();
 this.events.fire('userclose');
 },
 getShape: function() {
 if (!this._isElement(this._$element)) {
 return MyBalloonLayout.superclass.getShape.call(this);
 }
 var position = this._$element.position();
 return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
 [position.left, position.top],
 [
 position.left + this._$element[0].offsetWidth,
 position.top + this._$element[0].offsetHeight + this._$element.find('.brand-baloon__arrow')[0].offsetHeight
 ]
 ]));
 },
 _isElement: function(element) {
 return element && element[0] && element.find('.brand-baloon__arrow')[0];
 }
 }),
 
 MyBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
 '<div class="brand-baloon__inner">' +
 '<a class="close" href="#"><span class="icon-cross"></span></a>' +
 '<div class="brand-baloon__content">' +
 '<p class="brand-baloon__title">$[properties.balloonTitle]</p>' +
 '<a href="$[properties.balloonUrl]" class="btn btn--yellow btn--small" target="_blank">Подробнее</a>' +
 '</div>' +
 '</div>'
 );
 }

 updateMap = () => {
 const self = this;
 if (!myMap) return;
 // Очищаем старые точки
 objectManager.removeAll();
 // Загружаем новые данные
 self.loadBrandsData().then(function(data) {
 brandsData = data;
 self.addPoints();
 });
 }
 }

 const brandsMap = new Brands_Map();
 
 $window.on('DOMContentLoaded', function() {
 brandsMap.init();
 });
 
 // Обработчик кнопки "Показать на карте"
 $(document).on('click', '#show-map-btn', function(e) {
 e.preventDefault();
 const $container = $('#brands-map-container');
 if ($container.is(':visible')) {
 $container.slideUp();
 $(this).find('.btn__text').text('Показать на карте');
 } else {
 $container.slideDown();
 $(this).find('.btn__text').text('Скрыть карту');
 if (!myMap) {
 brandsMap.init();
 } else {
 brandsMap.updateMap();
 }
 }
 });
 
 // Обновление карты при изменении фильтров
 $(document).on('change', '.brands-filters input', function() {
 if ($('#brands-map-container').is(':visible')) {
 brandsMap.updateMap();
 }
 });
})();
