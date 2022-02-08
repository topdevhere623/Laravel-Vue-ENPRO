{{-- главная страница - админка --}}

<div class="page-header" style="display: none">
    {{-- заголовок --}}
    <h2 class="page-title">Главная</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
    </ol>
</div>

{{-- содержимое --}}
<div class="page-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="header-page">
            </div>

            {{-- фильтр стилизация карты яндекса --}}
            @include("backend.lib.filter")
            {{-- карта --}}
            <div id="mapMany" class="map map-auto-height"></div>

        </div>
    </div>
</div>

{{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <!-- карта Яндекса -->
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('API_KEY_YANDEX') }}"></script>

    <script type="text/javascript">
        let allSubstationsZoom1 = [];
        let allSubstationsZoom2 = [];
        let allSubstationsZoom3 = [];
        let polylines6 = [];
        let polylines4 = [];
        let oldZoom = 0;
        let showed400 = false;

        function changeZoomTo(zoom) {
            if (zoom >= 18) zoom = 16;
            else if (zoom >= 15) zoom = 14;
            else zoom = 8;
            if (oldZoom != zoom) {
                oldZoom = zoom;
                /*
                for (let num in allSubstationsZoom1) {
                    if(zoom != 16) allSubstationsZoom1[num].options.set('visible', false);
                    else allSubstationsZoom1[num].options.set('visible', true);

                    if(zoom != 14) allSubstationsZoom2[num].options.set('visible', false);
                    else allSubstationsZoom2[num].options.set('visible', true);

                    if(zoom != 8) allSubstationsZoom3[num].options.set('visible', false);
                    else allSubstationsZoom3[num].options.set('visible', true);
                } */
                if (zoom == 8 || zoom == 14) {
                    if (zoom == 8) $('.hidIfNeed').hide();
                    else $('.hidIfNeed').show();
                    for (let i = 0; i < polylines6.length; i++) {
                        if (polylines6[i].options) polylines6[i].options.set('strokeWidth', 2);
                    }
                    for (let i = 0; i < polylines4.length; i++) {
                        if (polylines4[i].options.get('strokeWidth') <= 1) polylines4[i].options.set('strokeWidth', 0.75);
                        else polylines4[i].options.set('strokeWidth', 2);
                    }
                } else {
                    $('.hidIfNeed').show();
                    for (let i = 0; i < polylines6.length; i++) {
                        if (polylines6[i].options) polylines6[i].options.set('strokeWidth', 3);
                    }
                    for (let i = 0; i < polylines4.length; i++) {
                        if (polylines4[i].options.get('strokeWidth') <= 1) polylines4[i].options.set('strokeWidth', 1);
                        else polylines4[i].options.set('strokeWidth', 3);
                    }
                }

            }

        }

        function show400() {
            if (showed400) {
                for (let i = 0; i < polylines4.length; i++) {
                    polylines4[i].options.set('visible', false);
                }
                showed400 = false;
            } else {
                for (let i = 0; i < polylines4.length; i++) {
                    polylines4[i].options.set('visible', true);
                }
                showed400 = true;
            }
        }


        function addSubstationYandex() {
            let addy = -0.0000;
            let addx = 0.0000;
            let delta = 1;
            let leftTextPosition = 21;
            for (let i = 0; i < substations.length; i++) {
                if (substations[i][1].substr(0, 2) == 'ПС') {
                    leftTextPosition = 29;
                }
                let color = substations[i][4];
                let textColor = '';
                switch (color) {
                    case 'green':
                        textColor = '#00FF38';
                        break;
                    case 'purple':
                        textColor = '#AB8AF5';
                        break;
                    case 'turquoise':
                        textColor = '#00FFB2';
                        break;
                    default:
                        textColor = '#00D1FF'
                        break;
                }
                let iconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: ' + textColor + '; font-size:11px; font-family:Roboto; position:absolute; display:none; white-space:nowrap;  left:' + leftTextPosition + 'px; top:2px; background-color:rgba(255,255,255,0) " class="hidIfNeed">$[properties.iconContent]</div>'
                );
                let height = 20;
                let width = 20;
                //alert(substations[i][1].substr(2));
                if (substations[i][1].substr(0, 2) == 'ПС') {
                    height = 28;
                    width = 28;
                }
                let substation = new ymaps.Placemark(
                    [substations[i][2] + addy, substations[i][3] + addx],
                    {
                        balloonContentHeader: '<div>' + substations[i][1] + '</div>',
                        balloonContentBody: '<a href="/public/admin/substation/parse/' + substations[i][0] + '" target="blank" class="btn btn-primary">Схема</a>' +
                            '<a href="#" onclick="show400(); return false;" class="btn btn-success">0,4 кВ</a>',
                        balloonContentFooter: '',
                        iconContent: substations[i][1]
                    }, {
                        hideIconOnBalloonOpen: false,
                        iconLayout: 'default#imageWithContent',
                        iconImageHref: substations[i][5],
                        iconImageSize: [width, height],
                        iconImageOffset: [width / -2, height / -2],
                        iconContentLayout: iconContentLayout
                    }
                );

                // записать новую наносимую точку на карту в массив
                let newMapID = mmObjects.length;
                mmObjects[newMapID] = {
                    'viewName': String(substations[i][1]),
                    'object': substation,
                }

                // добавить точку на карту
                myMap.geoObjects.add(substation);

            }
        }


        const substations = jQuery.parseJSON(atob('{!! $substations !!}'));
        const lines = jQuery.parseJSON(atob('{!! $lines !!}'));
        const lineWithSegments = jQuery.parseJSON(atob('{!! $lineWithSegments !!}'));
        const lineNames = jQuery.parseJSON(atob('{!! $lineNames !!}'));

        function streamline(lines) {
            const newLines = [];
            const linesCount = lines.length;
            lines.forEach((item, index) => {
                const line = [];
                if (index < linesCount - 1) {
                    if (item[1] === lines[index + 1][3] && item[2] === lines[index + 1][4]) {
                        line[0] = item[0];
                        line[1] = item[3];
                        line[2] = item[4];
                        line[3] = item[1];
                        line[4] = item[2];
                        line[5] = item[5];
                        line[6] = [item[7][0], item[7][1]];
                        line[7] = [item[6][0], item[6][1]];

                        newLines.push(line);
                    } else if (item[1] === lines[index + 1][1] && item[2] === lines[index + 1][2]) {
                        line[0] = item[0];
                        line[1] = item[3];
                        line[2] = item[4];
                        line[3] = item[1];
                        line[4] = item[2];
                        line[5] = item[5];
                        line[6] = [item[7][0], item[7][1]];
                        line[7] = [item[6][0], item[6][1]];

                        newLines.push(line);
                    } else if (item[3] === lines[index + 1][3] && item[4] === lines[index + 1][4]) {
                        newLines.push(item);
                    } else {
                        newLines.push(item);
                    }
                } else {
                    if (item[3] === newLines[newLines.length - 1][3] && item[4] === newLines[newLines.length - 1][4]) {
                        line[0] = item[0];
                        line[1] = item[3];
                        line[2] = item[4];
                        line[3] = item[1];
                        line[4] = item[2];
                        line[5] = item[5];
                        line[6] = [item[7][0], item[7][1]];
                        line[7] = [item[6][0], item[6][1]];

                        newLines.push(line);
                    } else {
                        newLines.push(item);
                    }
                }
            });
            return newLines
        }

        if (true) {
            var myMap;

            function init() {

                // прочитать  с локального хранилища браузера координаты центра и масштаб
                let savedZoom = Number(localStorage.getItem('mainmap.ymap.zoom'));
                let savedCenter = localStorage.getItem('mainmap.ymap.center');

                // проверить, сохранялось ли ранее координаты
                let myHaveSavedCoord = null;
                let myZoom = null;
                let myCenter = null;
                if (savedZoom === 0 && savedCenter == null) {
                    // ранее не сохранялось
                    myHaveSavedCoord = false;
                    // значения по-умолчанию
                    myZoom = 12.8;
                    myCenter = [(57.390448 - 57.361534) / 2 + 57.345534, (61.441726 - 61.328958) / 2 + 61.348958];
                } else {
                    // ранее сохранялось
                    myHaveSavedCoord = true;
                    myZoom = savedZoom;
                    myCenter = savedCenter.split(',').map((el) => Number(el));
                    myCenter = [myCenter[0], myCenter[1]];
                }

                // иницилизация карты
                myMap = new ymaps.Map('mapMany', {
                    center: myCenter,
                    zoom: myZoom
                });

                if (myHaveSavedCoord == false) {
                    // ранее координаты не сохранялись - переместить в позицию Пользовтаеля

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

                // события карты
                myMap.events.add('actionend', function (e) {
                    // если было перемещение по карте
                    // сохранить в локальном хранилище браузера координаты центра и масштаб
                    localStorage.setItem('mainmap.ymap.zoom', e.originalEvent.map.getZoom());
                    localStorage.setItem('mainmap.ymap.center', e.originalEvent.map.getCenter());
                });

                // создаем свой контрол поиска
                mySearchControl = new ymaps.control.SearchControl({
                    options: {
                        // заменяем стандартный провайдер данных (геокодер) нашим собственным.
                        provider: new MapSearchProvider(),
                        // не будем показывать еще одну метку при выборе результата поиска,
                        // т.к. метки коллекции myCollection уже добавлены на карту.
                        noPlacemark: true,
                        resultsPerPage: 5
                    }
                });

                // добавляем контрол в верхний правый угол
                myMap.controls.add(mySearchControl, {float: 'right'});
                myMap.controls.add(new ymaps.control.ZoomControl());
                const typeSelector = myMap.controls.get('typeSelector');
                const fullscreenControl = myMap.controls.get('fullscreenControl');

                // переключение спутник/гибрид/схема - разные стили
                myMap.events.add('typechange', function () {
                    //console.log("Соьытие переключение типа карты, стиль: " + myMap.getType());

                    // размер карты
                    let myIsFullscreen = myMap.container.isFullscreen();
                    //console.log("Размер карты: " + myIsFullscreen);

                    // сменить фильтры
                    if (myMap.getType() == 'yandex#map') {
                        // переключили на схему
                        if (myIsFullscreen) {
                            // на полном экране
                            $("body").removeClass('withoutFilters');
                        } else {
                            // в окне
                            $("#mapMany").removeClass('withoutFilters');
                        }

                    } else {
                        // переключили на спутник или гибрид
                        if (myIsFullscreen) {
                            // на полном экране
                            $("body").addClass('withoutFilters');
                        } else {
                            // в окне
                            $("#mapMany").addClass('withoutFilters');
                        }
                    }
                });

                // распахивание карты на весь экран
                fullscreenControl.events.add('fullscreenenter', function (e) {
                    //console.log("Событие распахивания карты");

                    // размер карты
                    let myIsFullscreen = myMap.container.isFullscreen();
                    //console.log("Размер карты: " + myIsFullscreen);

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
                        $("#mapMany").removeClass('withoutFilters');
                    } else {
                        $("#mapMany").addClass('withoutFilters');
                    }
                });

                for (let numb in lines[400]) {
                    let line = lines[400][numb];
                    //polylines6[]
                    if (!line || !line[0]) continue;
                    for (let s = 0; s < line.length; s++) {
                        let span = line[s];
                        let coordinates = [];
                        if (span[0] !== 702) {
                            coordinates = [
                                [span[2], span[1]],
                                [span[4], span[3]]
                            ];
                        } else if (span[0] === 702) {
                            coordinates = span[5];
                            //coordinates = coordinates.concat(span[6]);
                        }
                        //console.log(numb);
                        try {
                            let spanPolyline = new ymaps.Polyline(
                                coordinates
                                , {
                                    balloonContentHeader: '<div>' + lineNames[400][numb] + '</div>',
                                    balloonContentBody: '<a href="/public/admin/acline/map/edit/' + numb + '" target="blank" class="btn btn-primary">Схема</a>' +
                                        '',
                                    balloonContentFooter: ''
                                }, {
                                    visible: false,
                                    strokeWidth: (span[7][2] || span[6][2]) ? 0.75 : 2,
                                    strokeColor: "#17FF03",
                                    strokeStyle: (span[0] == 702 ? 'shortdash' : '')
                                }
                            );
                            //if(n < 800) spanPolyline.options.set('visible', false);
                            polylines4.push(spanPolyline);
                            try {
                                myMap.geoObjects.add(spanPolyline);
                            } catch (e) {
                                throw e;
                            }
                        } catch (e) {
                            alert(e);
                        }
                    }
                }
                let g = 0;
                for (let numb in lineWithSegments[6000]) {
                    //console.log('v' + numb);
                    g++
                    if (g > 62) {
                        //continue;
                    }
                    let line = lineWithSegments[6000][numb];
                    Object.keys(line).forEach((key, index) => {
                        // Пробегаемся по сегментам
                        let segment = line[key];
                        let completedLines = [];

                        let lastPoint = [];
                        let segmentStarted = false;

                        let acc;
                        let reverseCounter = 1;

                        segment.forEach((item, index) => {
                            if (acc) {
                                if (item[4] === acc[2] && item[3] === acc[1]) {
                                    // console.log('Начало предыдущего совпадает с концом текущего');
                                    reverseCounter++;
                                } else if (item[2] === acc[4] && item[1] === acc[3]) {
                                    // console.log('Все верно')
                                } else if (item[4] === acc[4] && item[3] === acc[3]) {
                                    // console.log('Конец предыдущего совпадает с концом текущего');
                                } else {
                                    //console.log('Иное', acc, item, index)
                                }
                            }

                            acc = item;
                        });

                        if (segment.length > 1) {
                            if (segment.length === reverseCounter) {
                                segment.reverse();
                            } else {
                                segment = streamline(segment);
                            }
                        }

                        if (segment.length > 0 && segment[0]) {
                            for (let s = 0; s < segment.length; s++) {
                                let span = segment[s];
                                let coordinates = [];

                                if (span[0] !== 702) {
                                    coordinates = [
                                        [span[2], span[1]],
                                        [span[4], span[3]]
                                    ];

                                    if (span[6][1] !== -1 && span[7][1] !== -1) {
                                        completedLines.push({coordinates})
                                    } else if (span[7][1] === -1) {
                                        if (!segmentStarted) {
                                            if (s === segment.length - 1) {
                                                completedLines.push({coordinates});
                                            } else {
                                                lastPoint = [span[2], span[1]];
                                                segmentStarted = true;
                                            }
                                        } else { // Если сегмент стартовал и последняя точка линии -1
                                            if (s === segment.length - 1) { //Если линяя начата и это последний элемент
                                                completedLines.push({coordinates: [lastPoint, [span[4], span[3]]]});
                                                segmentStarted = false;
                                                lastPoint = [];
                                            }
                                            //Пока ничего -
                                        }
                                    } else if (span[7][1] !== -1) {
                                        if (span[6][1] === -1) {
                                            completedLines.push({coordinates: [lastPoint, [span[4], span[3]]]});
                                            segmentStarted = false;
                                            lastPoint = [];
                                        }
                                    }

                                } else if (span[0] === 702) {
                                    coordinates = span[5];
                                    completedLines.push({coordinates, type: 'shortdash'})
                                }
                            }

                            completedLines.forEach((item, index) => {
                                if (item.coordinates[0].length && item.coordinates[1].length) {
                                    let spanPolyline = new ymaps.Polyline(
                                        item.coordinates
                                        , {
                                            balloonContentHeader: '<div>' + lineNames[6000][numb] + '</div>',
                                            balloonContentBody: '<br><a href="/public/admin/acline/map/edit/' + numb + '" target="blank" class="btn btn-primary">Схема</a>' +
                                                '',
                                            balloonContentFooter: ''
                                        }, {
                                            strokeWidth: 2,
                                            strokeColor: "#FF7C03",
                                            strokeStyle: (item.type)
                                        }
                                    );

                                    // spanPolyline.events.add('click', function() {
                                    //     console.log('Completed lines: ',  completedLines);
                                    //     console.log( 'Segment:', segment)
                                    // });
                                    //console.log( item.coordinates);
                                    polylines6.push(item.coordinates);
                                    myMap.geoObjects.add(spanPolyline);
                                }
                            })
                        }
                    });
                }


                //addSubstatios(14);
                //addSubstatios(8);
                //addSubstatios(16);
                addSubstationYandex();
                myMap.events.add('boundschange', function () {
                    //return;
                    // Запустим перестраивание макета при изменении уровня зума.
                    var currentZoom = myMap.getZoom();
                    changeZoomTo(currentZoom);
                    //changeZoomTo(16);
                    //changeZoomTo(8);
                }, this);


            }

            ymaps.ready(init);
        }


    </script>

    {{-- скрипт для поиска на карте --}}
    <script type="text/javascript">

        // глобальный массив обьектов на карте
        mmObjects = [];

        // ---------------------------------------------------------------
        // провайдер данных для элемента управления ymaps.control.SearchControl
        // осуществляет поиск геообъектов в по массиву points
        // реализует интерфейс IGeocodeProvider
        function MapSearchProvider(points) {
            this.points = mmObjects;
        }

        // ---------------------------------------------------------------
        // провайдер ищет по имени стандартным методом String.ptototype.indexOf
        MapSearchProvider.prototype.geocode = function (request, options) {

            var deferred = new ymaps.vow.defer(),
                geoObjects = new ymaps.GeoObjectCollection(),
                // сколько результатов нужно пропустить.
                offset = options.skip || 0,
                // количество возвращаемых результатов.
                limit = options.results || 20;

            var points = [];
            // поиск в имени точки
            for (var i = 0, l = this.points.length; i < l; i++) {
                var point = this.points[i];
                if (point['viewName'].toLowerCase().indexOf(request.toLowerCase()) != -1) {
                    points.push(point);
                }
            }
            // при формировании ответа можно учитывать offset и limit.
            points = points.splice(offset, limit);
            // добавляем точки в результирующую коллекцию.
            for (var i = 0, l = points.length; i < l; i++) {

                var point = points[i],
                    coords = point['object'].geometry._coordinates,
                    text = point['viewName'];

                geoObjects.add(new ymaps.Placemark(coords, {
                    name: text, // + ' name',
                    //description: text + ' description',
                    balloonContentBody: '<p>' + text + '</p>',
                    boundedBy: [coords, coords]
                }));
            }

            deferred.resolve({
                // геообъекты поисковой выдачи
                geoObjects: geoObjects,
                // метаинформация ответа
                metaData: {
                    geocoder: {
                        // строка обработанного запроса
                        request: request,
                        // кол-во найденных результатов.
                        found: geoObjects.getLength(),
                        // кол-во возвращенных результатов
                        results: limit,
                        // кол-во пропущенных результатов
                        skip: offset
                    }
                }
            });

            // возвращаем объекты
            return deferred.promise();
        };

    </script>

@endsection
