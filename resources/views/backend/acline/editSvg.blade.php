{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main_full_screen")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    SVG-карта
@endsection

{{-- секция контента --}}
@section("content")

    {{-- проверка прав доступа к странице --}}
    @if (
        Auth::user()->isVendor() or
        Auth::user()->isAdmin() or
        Auth::user()->isManager() or
        Auth::user()->isDispatcher() or
        Auth::user()->isOperator() or
        Auth::user()->isMaster() or
        Auth::user()->isWorking()
        )
        {{-- права есть --}}

        {{-- управление картой--}}
        <map-control-component ref="mapControl_component"></map-control-component>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection

{{-- секция моих скриптов --}}
@section("scripts")
    @parent

    {{-- карта yandex api --}}
    <script src="https://api-maps.yandex.ru/1.1/index.xml" type="text/javascript"></script>

    <script type="text/javascript">
        // все справочники, полученные с PHP
        mmAllSpravs = {!! json_encode($allSpravs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT)  !!};
    </script>

    <script type="text/javascript">

        // переменные, чтобы были как глобальные
        var myMap, YMaps, myArea;

        // параметры карты по-умолчанию
        // [57.373772, 61.391639] - Реж
        // [56.838011, 60.597465] - Екатеринбург
        let myMapDefault = {
            centerLat: 57.373772,
            centerLong: 61.391639,
            zoom: 16, // 21 // 16
        };

        let zoomSynch = {
            22: 42.56,
            21: 21.28,
            20: 10.64,
            19: 5.32,
            18: 2.66, // max
            17: 1.33,
            16: 0.665, // default
            15: 0.3325,
            14: 0.16625,
            13: 0.083125,
            12: 0.0415625,
            11: 0.02078125,
            10: 0.010390625,
            9: 0.0051953125,
            8: 0.00259765625,
            7: 0.001298828125,
            6: 0.0006494140625,
            5: 0.00032470703125,
            4: 0.000162353515625,
            3: 0.000162353515625,
            2: 0.000162353515625,
            1: 0.000162353515625,
            0: 0.000162353515625, // min
        }


        // создание обработчика для события window.onLoad
        YMaps.jQuery(function () {
            // создание карты
            funMakeMap();
            // загрузка vue-компонента svg-карты
            funMakeSVG();
        });

        // создание карты
        function funMakeMap() {
            //console.log("Функция [funMakeMap]");

            // создание экземпляра карты и его привязка к созданному контейнеру
            myMap = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);

            //console.log("Создаю карту с центром:");
            //console.log(myMapDefault.centerLong);
            //console.log(myMapDefault.centerLat);

            // установка центра и масштаба карты
            myMap.setCenter(
                new YMaps.GeoPoint(
                    myMapDefault.centerLong,
                    myMapDefault.centerLat
                ),
                myMapDefault.zoom);

            // добавление элементов управления
            myMap.enableScrollZoom();
            myMap.addControl(new YMaps.TypeControl());
            myMap.addCopyright('Энергопроект 2021')
            myMap.addCursor(YMaps.Cursor.CROSSHAIR); // CROSSHAIR // MOVE

            // создание оверлея
            funMakeNewArea();

            // ------------------------------------------------------
            // для отладки
            if (false) {
                // ----------
                // для отладки получить координаты при клике
                YMaps.Events.observe(myMap, myMap.Events.Click, function (myMap, mEvent) {
                    console.log(mEvent.getGeoPoint());
                });

                // ----------
                /* определение максимального значения
                коэффициента масштабирования
                для заданной области просмотра
                карты */
                var maxZoom = myMap.getMaxZoom(
                    new YMaps.GeoBounds(
                        new YMaps.GeoPoint(0, 0),
                        new YMaps.GeoPoint(40, 40)
                    )
                );

                /* определение минимального значения коэффициента
                масштабирования для заданной области просмотра
                карты */
                var minZoom = myMap.getMinZoom(
                    new YMaps.GeoBounds(
                        new YMaps.GeoPoint(0, 0),
                        new YMaps.GeoPoint(40, 40)
                    )
                );
                console.log('maxZoom = ' + maxZoom);
                console.log('minZoom = ' + minZoom);
                // ----------

                // отключить движение карты и зум
                //myMap.disableDragging();
                //myMap.disableScrollZoom();
            }
        }

        // создание оверлея
        function funMakeNewArea() {
            //console.log("Функция [funMakeNewArea]");

            myArea = new SimpleOverlayArea(
                new YMaps.GeoPoint(
                    myMapDefault.centerLong,
                    myMapDefault.centerLat
                )
            );

            // добавить оверелей на карту
            myMap.addOverlay(myArea);
        }

        // класс оверелея
        function SimpleOverlayArea(geoPoint) {
            //console.log("Функция [SimpleOverlayArea]");
            //console.log("geoPoint = ");
            //console.log(geoPoint);

            let myMap;
            let offset_0_px = new YMaps.Point(0, 0);

            // вызывается при добавления оверлея на карту
            this.onAddToMap = function (pMap, parentContainer) {
                //console.log("Функция [onAddToMap]");
                //console.log("pMap =");
                //console.log(pMap);
                //console.log("parentContainer =");
                //console.log(parentContainer);

                myMap = pMap;
                getElement().appendTo(parentContainer);
                this.onMapUpdate();
            };

            // вызывается при удаление оверлея с карты
            this.onRemoveFromMap = function () {
                if (getElement().parent()) {
                    getElement().remove();
                }
            };

            // вызывается при обновлении карты
            this.onMapUpdate = function (getGeoPointNew = null) {
                //console.log("Функция [onMapUpdate]");
                //console.log("geoPoint = ");
                //console.log(geoPoint);

                // если поменялся центр карты
                if (getGeoPointNew !== null) {
                    geoPoint = getGeoPointNew;
                }

                // смена позиции оверлея
                var position_overlay_px = myMap.converter.coordinatesToMapPixels(geoPoint).moveBy(offset_0_px);
                //console.log('position_overlay_px = ');
                //console.log(position_overlay_px);
                //console.log("scale = " + zoomSynch[myMap.getZoom()]);

                //const width = document.querySelector('#YMapsID').offsetWidth
                //const height = document.querySelector('#YMapsID').offsetHeight
                //const newWidth = width/zoomSynch[myMap.getZoom()]
                //const newHeight = height/zoomSynch[myMap.getZoom()]
                //const style = {
                //    width: newWidth + 'px',
                //    height: newHeight + 'px'
                //}
                //setTimeout(() => {
                //    document.querySelector('.area').style.width = style.width
                //    document.querySelector('.area').style.height = style.height
                //}, 0)
                // document.querySelector('.svg_area').style = style
                // console.log(newWidth)
                // console.log(newHeight)
                getElement().css({
                    // left: fullWidth/2,
                    // top: fullHeight/2,
                    left: position_overlay_px.x,
                    top: position_overlay_px.y,
                    transform: 'scale(' + zoomSynch[myMap.getZoom()] + ')'
                })


                // ------------------------------------------------------
                // для отладки
                if (false) {

                    console.log("---------------------");
                    console.log("Центр: ");
                    console.log(myMapDefault.centerLong + ', ' + myMapDefault.centerLat);
                    console.log("Текущий масштаб: " + myMap.getZoom());
                    console.log("Коэфициент: " + zoomSynch[myMap.getZoom()]);
                    console.log('offset_0_px = ');
                    console.log(offset_0_px);

                    let my_0_geo = myMap.converter.mapPixelsToCoordinates(offset_0_px);
                    console.log('my_0_geo = ');
                    console.log(my_0_geo);

                    var myPlacemark;

                    // 1) создание метки - центр
                    // myPlacemark = new YMaps.Placemark(
                    //     new YMaps.GeoPoint(myMapDefault.centerLong, myMapDefault.centerLat), {}
                    // );
                    // myPlacemark.name = "Center";
                    // myMap.addOverlay(myPlacemark);

                    // 2) создание метки - верхний левый угол карты
                    // myPlacemark = new YMaps.Placemark(
                    //     new YMaps.GeoPoint(my_0_geo.__lng, my_0_geo.__lat), {}
                    // );
                    // myPlacemark.name = "0";
                    // myMap.addOverlay(myPlacemark);

                    // 3) создание метки - TL
                    let my_TL_px = new YMaps.Point(position_overlay_px.x - 300, position_overlay_px.y - 300);
                    let my_TL_geo = myMap.converter.mapPixelsToCoordinates(my_TL_px);
                    console.log('my_TL_geo = ');
                    console.log(my_TL_geo);
                    myPlacemark = new YMaps.Placemark(
                        new YMaps.GeoPoint(
                            my_TL_geo.__lng,
                            my_TL_geo.__lat
                        ), {}
                    );
                    myPlacemark.name = "TL";
                    myMap.addOverlay(myPlacemark);

                    // 4) создание метки - BR
                    let my_BR_px = new YMaps.Point(position_overlay_px.x + 300, position_overlay_px.y + 300);
                    let my_BR_geo = myMap.converter.mapPixelsToCoordinates(my_BR_px);
                    console.log('my_BR_geo = ');
                    console.log(my_BR_geo);
                    myPlacemark = new YMaps.Placemark(
                        new YMaps.GeoPoint(
                            my_BR_geo.__lng,
                            my_BR_geo.__lat
                        ), {}
                    );
                    myPlacemark.name = "BR";
                    myMap.addOverlay(myPlacemark);
                }
            };

            // получить ссылку на DOM-ноду оверлея
            function getElement() {
                //console.log("Функция [getElement]");

                // const fullWidth = document.querySelector('#YMapsID').offsetWidth
                // const fullHeight = document.querySelector('#YMapsID').offsetHeight
                // console.log(fullWidth, fullHeight)
                let myElement = YMaps.jQuery(
                    "<div class='overlay-area'>" +
                    "<div class='area' id='svgContent'>" +
                    "<map-component></map-component>" +
                    "</div>" +
                    "</div>");

                // когда было в загружаемом template с svg
                //:width="mapAttributes.settings.svgWidthOriginalPx"-->
                //:height="mapAttributes.settings.svgHeightOriginalPx"-->
                //preserveAspectRatio="xMinYMin meet"-->
                //:viewBox="'0 0 ' + mapAttributes.settings.svgWidthOriginalPx * mapAttributes.settings.svgScale + ' ' + mapAttributes.settings.svgHeightOriginalPx * mapAttributes.settings.svgScale"-->

                // устанавливаем z-index как у метки
                myElement.css("z-index", YMaps.ZIndex.Overlay);

                //console.log("myElement = ");
                //console.log(myElement);

                // после первого вызова метода, он переопределяется,
                // чтобы дважды не создавать DOM-ноду
                return (getElement = function () {
                    return myElement
                })();
            }
        }

        // загрузка vue-компонента svg-карты
        function funMakeSVG() {
            new Vue({el: '#svgContent'});
            $('#svgContent').show();
        }

    </script>

    <style>
        /* общее */

        .YMaps {
            pointer: crosshair;
        }

        /* контейнер для карты (начало) */

        #YMapsID {
            height: 100%;
            width: 100%;
        }

        /* внешний вид оверлея */

        .overlay-area {
            position: absolute;
            z-index: 1;
            width: 0;
            height: 0;
            cursor: pointer; /* pointer crosshair */
            transform: scale(0.665)
        }

        .overlay svg {
            transition: all .3s;
        }

        .overlay:hover svg {
            transform: scale(1.3);
        }

        .area {
            position: absolute;
            /*width: 900px; !* было 300 *!*/
            /*height: 900px; !* было 300 *!*/
            opacity: .9;
            transform: translate(-50%, -50%);
            border: solid 1px green;
        }

        .overlay-area .area {
            transition: all .3s;
        }

        .overlay-area:hover .area {
            /*transform: translate(-50%, -50%) scaleX(2);*/
        }
    </style>
@endsection
