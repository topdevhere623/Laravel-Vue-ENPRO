{{-- карта --}}

{{-- лайоут --}}
@extends("backend.layouts.main_full_screen")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Создание / редактирование ЛЭП
@endsection

{{-- имя линии по-умолчанию --}}
@php
    $myAclineNameDefault = 'Новая ЛЭП';
@endphp

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

        {{-- содержимое --}}
        <div class="custom-page">
            <div class="page-content main-content">

                {{--индикатор ожидания ajax--}}
                <img src='/public/uploads/loading.gif' id='loading' style='display:none; width: 150px; position:fixed; margin:auto; top:0; bottom:0; left:0; right:0; z-index:9999;'/>

                {{-- панель навигации --}}
                @include('backend.acline.map.view_nav')

                <div>

                    {{-- множественный выбор --}}
                    @include('backend.acline.map.view_clipboard')

                    {{-- фильтр стилизация карты яндекса --}}
                    @include("backend.lib.filter")
                    {{-- карта --}}
                    <div id="map" class="map-auto-height"></div>

                    {{-- примечание и легенда карты --}}
                    {{--@include('backend.acline.map.view_legent')--}}

                </div>
            </div>

            <div class="panel-body p-0">
                <div class="sidebar-panel">
                    <div class="form-group">

                        {{-- данные ЛЭП --}}
                        @include('backend.acline.map.view_dRBAcline')

                        {{-- создание нового обьекта --}}
                        @include('backend.acline.map.view_dRBMarkerNewPoint')

                        {{-- данные выбранной точки - Опоры, ТП или Потребителя --}}
                        @include('backend.acline.map.view_dRBPlacemark')

                        {{-- данные выбранного пролета --}}
                        @include('backend.acline.map.view_dRBPolyline')

                        {{-- данные выбранного сегмента --}}
                        @include('backend.acline.map.view_dRBSegment')

                    </div>

                    <hr>

                    {{-- кнопка импортировать --}}
                    @include('backend.acline.map.view_dRBImport')
                </div>
            </div>
        </div>

        {{-- модальное окно --}}
        @include('backend.lib.modal',[
            'modalTitle' => 'Заголовок',
        ]);

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection

{{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <!-- карта Яндекса -->
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('API_KEY_YANDEX') }}"></script>
    <!-- галерея фото fancyBox JS -->
    <script src="../../../../../public/assets/common/libs/fancybox/jquery.fancybox.js"></script>
    <link rel="stylesheet" href="../../../../../public/assets/common/libs/fancybox/jquery.fancybox.css">

    <!-- мои JS в blade -->
    @include('backend.acline.map.body_events_checkbox')
    @include('backend.acline.map.body_events_multiselect')
    @include('backend.acline.map.css')
    @include('backend.acline.map.fun_ajaxLoadingImg')
    @include('backend.acline.map.fun_buttonInMapAdd')
    @include('backend.acline.map.fun_clipboard')
    @include('backend.acline.map.fun_globalVarsInit')
    @include('backend.acline.map.fun_histore')
    @include('backend.acline.map.fun_hotKey')
    @include('backend.acline.map.fun_import')
    @include('backend.acline.map.fun_loadSave')
    @include('backend.acline.map.fun_mapMarkerFly')
    @include('backend.acline.map.fun_mapMarkerNewPoint')
    @include('backend.acline.map.fun_mapObjectActive')
    @include('backend.acline.map.fun_mapObjectAdd')
    @include('backend.acline.map.fun_mapObjectDelete')
    @include('backend.acline.map.fun_mapObjectEvents')
    @include('backend.acline.map.fun_mapObjectLampAndDoubleAcline')
    @include('backend.acline.map.fun_mapObjectOtherNear')
    @include('backend.acline.map.fun_modal')
    @include('backend.acline.map.fun_other')
    @include('backend.acline.map.fun_RBapply')
    @include('backend.acline.map.fun_RBview')
    @include('backend.acline.map.fun_segmentsAnaliz')
    @include('backend.acline.map.fun_spravSelectUpdate')
    @include('backend.acline.map.fun_state')
    @include('backend.acline.map.fun_SVGPlacemerkIcon')
    {{--@include('backend.acline.map.fun_SVGMap')--}}
    @include('backend.acline.map.fun_url')
    @include('backend.acline.map.fun_ymap')
    @include('backend.acline.map.fun_ymapSearch')

    <script>
        // продублированная загрузка всех справочников (чтоб иметь массив JS, и не делать Ajax запросы, и не увеличивать длину страницу, если просто присвоить JSON-ом)
        funSpravsLoad();
        // иницилизация глобальных переменных, которые нельзя обнулять
        funGlobalVarsCannotResetInit();
        // иницилизация глобальных переменных, которые можно обнулять
        funGlobalVarsCanResetInit();

        // -------------------------
        // иницилизация карты
        ymaps.ready(function () {

                // значения по-умолчанию
                let myCenter = [{{ $setting_map_center }}];
                let myZoom = {{ $setting_map_scale }};

                // иницилизация карты
                myMap = new ymaps.Map("map",
                    {
                        center: myCenter,
                        zoom: myZoom,
                        controls: ['zoomControl', 'typeSelector', 'fullscreenControl', 'rulerControl'] // 'searchControl'
                    });

                if ($('#sRBAclineID').text().trim() === '') {
                    // id ЛЭП не определен - открыть в позиции Пользовтаеля

                    // определить координаты Пользователя
                    geolocation = ymaps.geolocation;
                    var location = geolocation.get({
                        // выставляем опцию для определения положения по ip
                        provider: 'yandex', // средствами Яндекс 'yandex' по ip. Либо средствами браузера 'browser'
                        // карта автоматически отцентрируется по положению пользователя
                        mapStateAutoApply: true,
                        // включим автоматическое геокодирование результата
                        autoReverseGeocode: true
                    }).then(function (result) {
                            // переместиться в определенное положение Пользователя
                            myMap.panTo(result.geoObjects.position, {flying: 1});
                        }
                    );
                }

                // создаем свой контрол поиска
                mySearchControl = new ymaps.control.SearchControl({
                    options: {
                        // заменяем стандартный провайдер данных (геокодер) нашим собственным
                        provider: new MapSearchProvider(),
                        // не будем показывать еще одну метку при выборе результата поиска
                        // т.к. метки уже добавлены на карту.
                        noPlacemark: true,
                        resultsPerPage: 5
                    }
                });
                // добавляем контрол в верхний правый угол
                myMap.controls.add(mySearchControl, {float: 'right'});
                const typeSelector = myMap.controls.get('typeSelector');
                const fullscreenControl = myMap.controls.get('fullscreenControl');

                // переключение спутник/гибрид/схема - разные стили
                myMap.events.add('typechange', function () {
                    //console.log("Соьытие переключение типа карты, стиль: " + myMap.getType());

                    // размер карты
                    let myIsFullscreen = myMap.container.isFullscreen();
                    //console.log("Размер карты: " + myIsFullscreen);

                    // ссылка на управляющий html-элемент
                    let myHTML = myMap.container.getElement();
                    //console.log("html-элемент карты: ");
                    //console.log(myHTML);

                    // сменить фильтры
                    if (myMap.getType() == 'yandex#map') {
                        // переключили на схему
                        if (myIsFullscreen) {
                            // на полном экране
                            $("body").removeClass('withoutFilters');
                        } else {
                            // в окне
                            $("#map").removeClass('withoutFilters');
                        }

                    } else {
                        // переключили на спутник или гибрид
                        if (myIsFullscreen) {
                            // на полном экране
                            $("body").addClass('withoutFilters');
                        } else {
                            // в окне
                            $("#map").addClass('withoutFilters');
                        }
                    }
                });

                // распахивание карты на весь экран
                fullscreenControl.events.add('fullscreenenter', function (e) {
                    //console.log("Событие распахивания карты");

                    // размер карты
                    let myIsFullscreen = myMap.container.isFullscreen();
                    //console.log("Размер карты: " + myIsFullscreen);

                    // ссылка на управляющий html-элемент
                    let myHTML = myMap.container.getElement();
                    //console.log("html-элемент карты: ");
                    //console.log(myHTML);

                    // сменить фильтры
                    if (myMap.getType() === 'yandex#map') {
                        $("body").removeClass('withoutFilters');
                    } else {
                        $("body").addClass('withoutFilters');
                    }
                });

                // сворачивание карты
                fullscreenControl.events.add('fullscreenexit', function (e) {
                    //console.log("Событие сворачивания карты");

                    // размер карты
                    let myIsFullscreen = myMap.container.isFullscreen();
                    //console.log("Размер карты: " + myIsFullscreen);

                    // удалить фильтры
                    $("body").removeClass('withoutFilters');

                    // сменить фильтры
                    if (myMap.getType() === 'yandex#map') {
                        $("#map").removeClass('withoutFilters');
                    } else {
                        $("#map").addClass('withoutFilters');
                    }
                });

                // подпишемся на событие клика на карте
                myMap.events.add('click', function (e) {

                    // закрыть все балуны
                    myMap.balloon.close();
                    // удалить маркер перелета с карты, если есть
                    myFunMarkerFlyDelete();

                    // записать новые координаты
                    mmCurrentCoords = e.get('coords');

                    //console.log("Событие клика на карте с координатами:");
                    //console.log(mmCurrentCoords);

                    // показать детали маркера предполагаемой новой точки
                    funRBviewMarkerNewPoint();
                });

                // подпишемся на событие изменения масштаба карты или ее сдвига
                myMap.events.add('boundschange', function (event) {

                    // новые координаты верхнего левого и нижнего правого угла
                    //let myCurrentBounds = event.get('newBounds');
                    //console.log("Событие смены масштаба или ее сдвига. Координаты видимой области карты:");
                    //console.log(myCurrentBounds);

                    // отрисовка других точек - запрос в базу и отображение на карте
                    funNearObjectsLoad();

                    if (event.get('newBounds') !== event.get('oldBounds')) {
                        // да, был сдвиг
                    }

                    if (event.get('newZoom') !== event.get('oldZoom')) {
                        // да, изменился масштаб

                        mmObjects.forEach(function (item) {
                            if (item.mapType === 'placemark' && item.deleted === false) {

                                // создать иконку svg
                                funSVGmake(item.mapID);
                            }
                        });
                    }
                });

                // создание коллекции обьектов данной ЛЭП
                mmCollection = new ymaps.GeoObjectCollection();
                myMap.geoObjects.add(mmCollection);
                // обработчик клика на обьекте коллекции данной ЛЭП
                mmCollection.events.add("click", funOnClickEventCollection);
                // обработчик добавления нового обьекта для данной ЛЭП
                mmCollection.events.add("add", funOnAddEventCollection);

                // создание коллекции обьектов других ЛЭП
                mmCollectionOther = new ymaps.GeoObjectCollection();
                myMap.geoObjects.add(mmCollectionOther);
                // обработчик клика на обьекте коллекции других ЛЭП
                mmCollectionOther.events.add("click", funOnClickEventCollectionOther);

                // создание коллекции для рисования опор с фонарями
                mmCollectionLamp = new ymaps.GeoObjectCollection();
                myMap.geoObjects.add(mmCollectionLamp);

                // создание коллекции для рисования опор участвующих в совместном подвесе
                mmCollectionDoubleAcline = new ymaps.GeoObjectCollection();
                myMap.geoObjects.add(mmCollectionDoubleAcline);

                // кнопки
                funButtonInMapAdd();

                if ($('#sRBAclineID').text().trim() !== '') {
                    // id ЛЭП определен - загрузить данные
                    setTimeout(function () {
                        funLoad();
                    }, 300)
                }

                // показать детали текущей ЛЭП
                funRBviewAcline();
                // прочитать строку url и осуществить "перелет" по карте, если требуется
                funGetUrlAndFly();
            }
        );

    </script>
@endsection
