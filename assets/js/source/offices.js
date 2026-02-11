const $ = jQuery.noConflict();


(() => {

    const $window = $(window);  
    let myMap;
    let officesData;
    let objectManager;
    let baloonParametrs;
    let MyBalloonLayout;
    let MyBalloonContentLayout;
    let pins = {};

    class Office_Map {
        constructor() {
            
        }

        init = () => {
            const self = this;

              ymaps.ready(function() {

                myMap = new ymaps.Map('offces-map', {
                    center: [55.751279, 37.621131],
                    zoom: 9,
                    controls: ['zoomControl']
                });

                myMap.behaviors.disable('scrollZoom');

                objectManager = new ymaps.ObjectManager({
                    clusterize: true,
                    gridSize: 32,
                    clusterDisableClickZoom: false
                });

                $.ajax({
                    url: "/wp-content/themes/pallet/assets/offices.json",
                    async: false,
                    cache: false,
                }).done(function(data) { 
                   officesData = data;
                });


                self.baloonConfig();

                self.objectManagerConfig();

                myMap.geoObjects.add(objectManager);

                self.addPoints();

                objectManager.objects.events.add(['click'], self.moveToPoint);

                self.baloonTrigger();

            });           

        }  

        objectManagerConfig = () => {

            const self = this;

            objectManager = new ymaps.ObjectManager({
                clusterize: true,
                gridSize: 32,
                clusterDisableClickZoom: false
            });

            let settingObj = {
                iconLayout: 'default#imageWithContent',
                iconImageSize: [170, 171],
                iconImageOffset: [-76, -114],
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
            objectManager.clusters.options.set(settingObjCl);
          
        }


        addPoints = () => {
            let pointList = {};
            pointList["type"] = "FeatureCollection";
            pointList["features"] = [];
            let item;

            $.each(officesData, function (key, data) {
                pins[data.id] = '/wp-content/themes/pallet/assets/images/pin-'+ data.type +'.png';
                item = {
                    'type': 'Feature',
                    'id': data.id,
                    'geometry': {
                        'type': 'Point',
                        'coordinates':  data.coords.split(','),
                    },
                    'properties': {
                        'balloonId': data.balloonId,
                        'balloonTitle': data.title,
                        'balloonAdress': data.address,
                        'balloonPhone': data.phone,
                        'balloonWorkTime': data.timetable,
                        'balloonImgUrl': data.balloonImgUrl,
                    },
                    'options': {
                        'iconImageHref': pins[data.id],
                    }
                };             
                pointList["features"].push(item);
            });

            objectManager.add(pointList);
        }


        moveToPoint = (e) => {
            if (e.get('type') == 'click') { 
                let objectId = e.get('objectId'),
                    obj = objectManager.objects.getById(objectId),
                    coords = obj.geometry.coordinates;
                myMap.setCenter(coords, myMap.getZoom());
            };
        }


      baloonTrigger = () =>  {

        let objects = objectManager.objects;
        objects.events
            .add(['balloonopen'], function (e) {
                let objectId = e.get('objectId');
                objects.setObjectOptions(objectId, {
                    iconImageHref: '/wp-content/themes/pallet/assets/images/pin-active.png', 
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
                content:    '<div class="office-baloon__left">' +
                            '<p class="office-baloon__title">$[properties.balloonTitle]</p>' +
                            '<p class="office-baloon__address">$[properties.balloonAdress]</p>' +                            
                            '</div>' +
                            '<div class="office-baloon__right">' +
                            '<img class="office-baloon__pic" src="$[properties.balloonImgUrl]" alt="">' +
                            '</div>'+
                            '<div class="clearfix">' +
                            '<p class="office-baloon__phone">$[properties.balloonPhone]</p>' +
                            '<p class="office-baloon__timetable">$[properties.balloonWorkTime]</p>' +
                            '</div>',
                offset:     [0, 0]
            };

            MyBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="office-baloon">' +
                '<div class="office-baloon__arrow"> </div>' +
                '$[[options.contentLayout]]' +
                '</div>', {

                    build: function() {
                        this.constructor.superclass.build.call(this);
                        this._$element = $('.office-baloon', this.getParentElement());
                        this.applyElementOffset();
                        this._$element.find('.close').on('click', $.proxy(this.onCloseClick, this));
                    },

                    clear: function() {
                        this._$element.find('.close')
                            .off('click');
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
                                position.top + this._$element[0].offsetHeight + this._$element.find('.office-baloon__arrow')[0].offsetHeight
                            ]
                        ]));
                    },

                    _isElement: function(element) {
                        return element && element[0] && element.find('.office-baloon__arrow')[0];
                    }


                }),

                MyBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="office-baloon__inner">' +
                    '<a class="close" href="#"><span class="icon-cross"></span></a>' +
                     baloonParametrs['content'] +
                    '</div>'
                );
        }; 



    }

    const officeMap = new Office_Map();

    $window
        .on('DOMContentLoaded', officeMap.init())
})();