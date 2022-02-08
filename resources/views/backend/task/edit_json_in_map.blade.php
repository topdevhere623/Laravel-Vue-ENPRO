{{-- json на карте --}}

@if (isset($content->json_parse_file) and !is_null($content->json_parse_file))
    <div class="row">
        <div class="col-md-12">

            {{-- кнопки над картой --}}
            <input type="button" id="b_edit_on" value="Разрешить редактирование">
            <input type="button" id="b_edit_off" value="Запретить редактирование" disabled>
            <input type="button" id="b_point_save" value="Сохранить точки" disabled>
            <input type="button" id="b_auto_scale" value="Масштабировать">

            {{-- карта --}}
            <div id="mapTask" class='map'>
            </div>

            {{-- json в табличной форме расшифровка точек--}}
            @include('backend.task.edit_json_in_table')

        </div>
    </div>

    {{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <!-- карта Яндекса -->
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('API_KEY_YANDEX') }}"></script>

    <script type="text/javascript">

        // id текущей задачи
        task_id = {!! $content->id !!};

        // массив точек
        myArrPoints = [[]];
        pointN = -1;
        myPlacemark = [];
        // массив линий (зависимость от точек)
        myArrLines = [[]];
        lineN = -1;
        myPolyline = [];

        ymaps.ready(function () {

                // новый json
                jsonObject = {!! json_encode($content->json_parse_file, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
                // массив координат потребителей (только для проверки, потому что с приложения несколько раз одинаковых приходило)
                arrForTestPotrebitel = [[]];
                potrebitelN = 0; // это не удалять, нужно!

                // иницилизация карты
                myMap = new ymaps.Map("mapTask", {
                    center: [{{ $setting_map_center }}], // координаты центра карты
                    zoom: {{ $setting_map_scale }} // маштаб карты
                });

                // точки
                if (true) {
                    if (typeof(jsonObject['points']) != "undefined" && jsonObject['points'].length > 0) {

                        pointCurrentN = 0;
                        for (i = 0; i < jsonObject['points'].length; ++i) {

                            // записать в массив точек
                            pointN++;
                            pointCurrentN = pointN;
                            myArrPoints[pointN] =
                                {
                                    'lat': jsonObject['points'][i]['lat'],
                                    'long': jsonObject['points'][i]['long'],
                                    'comment': 'точка',
                                    'json_id': jsonObject['points'][i]['id'],
                                    'json_name': jsonObject['points'][i]['dispatcherName'],
                                    'json1': i,
                                    'json2': null,
                                };

                            myPlacemark[pointN] = new ymaps.Placemark(
                                [myArrPoints[pointN]['lat'], myArrPoints[pointN]['long']], // координаты метки объекта
                                {
                                    hintContent: myArrPoints[pointN]['json_name'], // подсказка метки
                                    iconContent: myArrPoints[pointN]['json_name'],
                                    balloonContent:
                                    '<div class="ya_map">' +
                                    'Точка: ' + myArrPoints[pointN]['json_name'] + '<br \/>' +
                                    (jsonObject['points'][i]['address'] != '' ? ('Адрес: ' + jsonObject['points'][i]['address'] + '<br \/>') : '') +
                                    '</div>'
                                }, {
                                    preset: 'islands#blueStretchyIcon', // тип метки // стандартная светло-голубая twirl#blueIcon // желтый кружок islands#yellowStretchyIcon // темно-синий с точкой islands#nightDotIcon
                                    // draggable: true, // можно передвигать
                                },
                            );
                            // добавление метки
                            myMap.geoObjects.add(myPlacemark[pointN]);
                            // открытие подсказки метки
                            myPlacemark[pointN].balloon.open();

                            // потребители
                            placemarkConsumers = [];
                            if (typeof(jsonObject['points'][i]['consumerInputDTOS']) != "undefined" && jsonObject['points'][i]['consumerInputDTOS'].length > 0) {
                                for (j = 0; j < jsonObject['points'][i]['consumerInputDTOS'].length; ++j) {

                                    // текущий потребитель
                                    lat = jsonObject['points'][i]['consumerInputDTOS'][j]['lat'];
                                    long = jsonObject['points'][i]['consumerInputDTOS'][j]['long'];

                                    // проверка, не было ли уже этого потребителя (а то по несколько раз с приложения приходил)
                                    mayContinue = true;
                                    for (k = 0; k < arrForTestPotrebitel.length; ++k) {
                                        if (arrForTestPotrebitel[k][0] == lat && arrForTestPotrebitel[k][1] == long) {
                                            mayContinue = false;
                                            break;
                                        }
                                    }

                                    if (mayContinue) {

                                        // нет, потребитель только первый раз
                                        // записать в массив
                                        potrebitelN++;
                                        arrForTestPotrebitel [potrebitelN] = [lat, long];

                                        // поставить точку
                                        // записать в массив точек
                                        pointN++;
                                        myArrPoints[pointN] =
                                            {
                                                'lat': lat,
                                                'long': long,
                                                'comment': 'потребитель',
                                                'json_id': null,
                                                'json_name': jsonObject['points'][i]['consumerInputDTOS'][j]['name'],
                                                'json1': i,
                                                'json2': j,
                                            };
                                        myPlacemark[pointN] = new ymaps.Placemark(
                                            [myArrPoints[pointN]['lat'], myArrPoints[pointN]['long']], // координаты метки объекта
                                            {
                                                hintContent: 'Потребитель: ' + myArrPoints[pointN]['json_name'], // подсказка метки
                                                iconContent: 'Потребитель: ' + myArrPoints[pointN]['json_name'],
                                                balloonContent:
                                                '<div class="ya_map">' +
                                                'Потребитель: ' + myArrPoints[pointN]['json_name'] + '<br \/>' +
                                                '</div>'
                                            }, {
                                                preset: 'islands#redDotIcon', // тип метки
                                                // draggable: true, // можно передвигать
                                            },
                                        );
                                        // добавление метки
                                        myMap.geoObjects.add(myPlacemark[pointN]);

                                        // соединить линией с опорой
                                        // записать в массив линий (зависимость от точек)
                                        lineN++;
                                        myArrLines[lineN] =
                                            {
                                                'start': pointCurrentN,
                                                'end': pointN,
                                                'comment': 'потребитель',
                                            };

                                        startCoordinat = [myArrPoints[myArrLines[lineN]['start']]['lat'], myArrPoints[myArrLines[lineN]['start']]['long']];
                                        endCoordinat = [myArrPoints[myArrLines[lineN]['end']]['lat'], myArrPoints[myArrLines[lineN]['end']]['long']];

                                        myPolyline[lineN] = new ymaps.Polyline(
                                            [startCoordinat, endCoordinat],
                                            properties =
                                                {
                                                    hintContent: "Линия до Потребителя"
                                                },
                                            options =
                                                {
                                                    // draggable: true, // можно передвигать
                                                    strokeColor: '#ed4543',
                                                    strokeWidth: 5,
                                                }
                                        );
                                        myMap.geoObjects.add(myPolyline[lineN]);
                                    }
                                }
                            }
                        }
                    }
                }

                // 701 воздушные линии
                if (true) {
                    if (typeof(jsonObject['lines701']) != "undefined" && jsonObject['lines701'].length > 0) {
                        for (i = 0; i < jsonObject['lines701'].length; ++i) {

                            pointNstart = myFunFind(jsonObject['lines701'][i]['startPoint']);
                            pointNend = myFunFind(jsonObject['lines701'][i]['endPoint']);

                            // записать в массив линий (зависимость от точек)
                            lineN++;
                            myArrLines[lineN] =
                                {
                                    'start': pointNstart,
                                    'end': pointNend,
                                    'comment': 'воздушая линия 701',
                                };

                            startCoordinat = [myArrPoints[myArrLines[lineN]['start']]['lat'], myArrPoints[myArrLines[lineN]['start']]['long']];
                            endCoordinat = [myArrPoints[myArrLines[lineN]['end']]['lat'], myArrPoints[myArrLines[lineN]['end']]['long']];

                            myPolyline[lineN] = new ymaps.Polyline(
                                [startCoordinat, endCoordinat],
                                properties =
                                    {
                                        hintContent: "Воздушная линия" + lineN,
                                    },
                                options =
                                    {
                                        // draggable: true, // можно передвигать
                                        strokeColor: '#1e98ff',
                                        strokeWidth: 5,
                                    }
                            );
                            myMap.geoObjects.add(myPolyline[lineN]);
                        }
                    }
                }

                // 702 кабельные линии
                if (true) {
                    if (typeof(jsonObject['lines702']) != "undefined" && jsonObject['lines702'].length > 0) {

                        for (i = 0; i < jsonObject['lines702'].length; ++i) {

                            pointNstart = myFunFind(jsonObject['lines702'][i]['startPoint']);
                            pointNend = myFunFind(jsonObject['lines702'][i]['endPoint']);

                            // записать в массив линий (зависимость от точек)
                            lineN++;
                            myArrLines[lineN] =
                                {
                                    'start': pointNstart,
                                    'end': pointNend,
                                    'comment': 'кабельная линия 702',
                                };

                            startCoordinat = [myArrPoints[myArrLines[lineN]['start']]['lat'], myArrPoints[myArrLines[lineN]['start']]['long']];
                            endCoordinat = [myArrPoints[myArrLines[lineN]['end']]['lat'], myArrPoints[myArrLines[lineN]['end']]['long']];

                            myPolyline[lineN] = new ymaps.Polyline(
                                [startCoordinat, endCoordinat],
                                properties =
                                    {
                                        hintContent: "Кабельная линия" + lineN,
                                    },
                                options =
                                    {
                                        // draggable: true, // можно передвигать
                                        strokeColor: '#1e98ff',
                                        strokeWidth: 5,
                                        strokeStyle: {
                                            style: 'dot',
                                            offset: 10
                                        },
                                    }
                            );
                            myMap.geoObjects.add(myPolyline[lineN]);
                        }
                    }
                }

                // массив отслеживания координат ломанной при перемещении точек
                myArrForEvents = [[]];
                nn = -1;
                for (pointNCurrent = 0; pointNCurrent < myArrPoints.length; ++pointNCurrent) {

                    // поиск в массиве кривой
                    for (lineNCurrent = 0; lineNCurrent < myArrLines.length; ++lineNCurrent) {

                        if (myArrLines[lineNCurrent]['start'] == pointNCurrent) {
                            // точка встречается в начале линии
                            nn++;
                            myArrForEvents[nn] =
                                {
                                    'point': pointNCurrent,
                                    'line': lineNCurrent,
                                    'startEnd': 0,
                                };
                        }

                        if (myArrLines[lineNCurrent]['end'] == pointNCurrent) {
                            // точка встречается в конце линии
                            nn++;
                            myArrForEvents[nn] =
                                {
                                    'point': pointNCurrent,
                                    'line': lineNCurrent,
                                    'startEnd': 1
                                };
                        }
                    }
                }

                // генерация событий для точек (переменных не видит внутри, поэтому eval)
                for (nn = 0; nn < myArrForEvents.length; ++nn) {

                    point = myArrForEvents[nn]['point'];
                    line = myArrForEvents[nn]['line'];
                    startEnd = myArrForEvents[nn]['startEnd'];

                    // текст события
                    myCommand = "myPlacemark[" + point + "].geometry.events.add('change', function (e) {var newCoords = e.get('newCoordinates'); myPolyline[" + line + "].geometry.set(" + startEnd + ", newCoords);});";
                    // создать событие
                    eval(myCommand);
                }

                //console.log(myArrPoints);
                //console.log(myArrLines);
                //console.log(myArrForEvents);

                // клик на разрешить редактирование
                $('#b_edit_on').on('click', function () {

                    // установить всем точкам свойство возможности перемещаться
                    myFunPointDraggable(1);

                    // достпуность кнопок
                    $('#b_edit_on').attr("disabled", true);
                    $('#b_edit_off').attr("disabled", false);
                    $('#b_point_save').attr("disabled", false);
                });

                // клик на запретить редактирование
                $('#b_edit_off').on('click', function () {

                    // установить всем точкам свойство возможности перемещаться
                    myFunPointDraggable(0);

                    // достпуность кнопок
                    $('#b_edit_on').attr("disabled", false);
                    $('#b_edit_off').attr("disabled", true);
                    $('#b_point_save').attr("disabled", true);
                });

                // клик на сохранить точки
                $('#b_point_save').on('click', function () {

                    // обновить в массиве
                    for (point = 0; point < myArrPoints.length; ++point) {
                        coordinates = myPlacemark[point].geometry.getCoordinates();
                        myArrPoints[point]['lat'] = coordinates[0];
                        myArrPoints[point]['long'] = coordinates[1];
                    }
                    //console.log(myArrPoints);

                    // всплывающая подсказка
                    toastr.success('Идет сохранение данных...');
                    // обновить в первоисточнике (в полученном json)
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        url: '{{ route('ajaxUpdateTaskJsonMap') }}',
                        method: "POST",
                        dataType: 'json',
                        data: {
                            'myArrPoints': myArrPoints,
                            'task_id': task_id,
                        }
                    }).done(function (result) {
                        // нужно для отладки
                        //console.log(result);
                        // всплывающая подсказка
                        toastr.success('Данные успешно сохранены...');
                    });

                });

                // клик на маштабирование
                $('#b_auto_scale').on('click', function () {
                    // авто маштабирование
                    myFunAutoMapScale();
                });
            }
        );

        // авто маштабирование
        function myFunAutoMapScale() {
            myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true}).then(function () {
                if (myMap.getZoom() > 15) myMap.setZoom(15); // Если значение zoom превышает 17, то устанавливаем 17
            });
        }

        // функция поиска json_id и возврат номера строки из массива точек
        function myFunFind(json_id) {
            for (k = 0; k < myArrPoints.length; ++k) {

                if (myArrPoints[k]['json_id'] == json_id) return k;
            }
            // возвращаемый параметр
            return null;
        }

        // установить всем точкам свойство возможности перемещаться
        function myFunPointDraggable(regim) {
            for (point = 0; point < myArrPoints.length; ++point) {
                myPlacemark[point].options.set({draggable: regim});
            }
        }

    </script>

@endsection
@else
    {{-- данных дл карты еще нет --}}
    <p>
        {{ __('edit.no_json_file') }}
    </p>
@endif
}