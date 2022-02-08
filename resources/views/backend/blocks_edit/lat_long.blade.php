{{-- редактирование координат: долготы и широты --}}

<div class="row">
    <div class="col-md-12">
        <div class="row">

            {{-- адрес, долгота и широта --}}
            <div class="form-group col-md-4">

                {{-- адрес --}}
                <div class="row">
                    <div class="form-group col">

                        <h4 class="example-title">Адрес</h4>
                        <input type="text" class="form-control"
                               name="{{ $myFieldName1 }}" id="{{ $myFieldName1 }}"
                               value="{{ old($myFieldName1, (isset($myFieldValue1) ? $myFieldValue1 : $content->$myFieldName1)) }}"
                               placeholder="адрес"
                               @if (isset($myRequired1))
                               required
                            @endif
                        >
                    </div>
                </div>

                {{-- широта --}}
                <div class="row">
                    <div class="form-group col">

                        <h4 class="example-title">Широта</h4>
                        <input type="number" min="-90" max="90" step="0.00000000000001" class="form-control"
                               name="{{ $myFieldName2 }}" id="{{ $myFieldName2 }}"
                               value="{{ old($myFieldName2, (isset($myFieldValue2) ? $myFieldValue2 : (isset($content->$myFieldName2) ? $content->$myFieldName2 : 0))) }}"
                               placeholder="широта"
                               @if (isset($myRequired2))
                               required
                            @endif
                        >
                        @error($myFieldName2)
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>Ошибка!</strong> Значение широты должно быть в диапазоне от -90 до 90.
                        </div>
                        @enderror

                    </div>
                </div>

                {{-- долгота --}}
                <div class="row">
                    <div class="form-group col">

                        <h4 class="example-title">Долгота</h4>
                        <input type="number" min="-180" max="180" step="0.00000000000001" class="form-control"
                               name="{{ $myFieldName3 }}" id="{{ $myFieldName3 }}"
                               value="{{ old($myFieldName3, (isset($myFieldValue3) ? $myFieldValue3 : (isset($content->$myFieldName3) ? $content->$myFieldName3 : 0))) }}"
                               placeholder="долгота"
                               @if (isset($myRequired3))
                               required
                            @endif
                        >
                        @error($myFieldName3)
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>Ошибка!</strong> Значение долготы должно быть в диапазоне от -180 до 180.
                        </div>
                        @enderror

                    </div>
                </div>

            </div>

            {{-- карта --}}
            <div class="form-group col-md-8">

                <h4 class="example-title">Карта</h4>

                {{-- фильтр стилизация карты яндекса --}}
                @include("backend.lib.filter")
                {{-- карта --}}
                <div id="map{{ $myMapObject }}" style="height: 300px;"></div>

                <input type="button" value="Определить местоположение" onclick="myFunClickMarkerGoToCurrentGeo()">
                <input type="button" value="Перейти по заданным координатам" onclick="myFunClickMarkerGoToPoint()">
                <input type="button" value="Сохранить адрес и координаты" onclick="myFunSaveAddressAndCoordinat()">

            </div>
        </div>
    </div>
</div>


{{-- секция моих скриптов --}}
{{--надо отключать, а то карта у зависимых сущностей в задаче от отображается--}}
@section("scripts")
    @parent

    <!-- карта Яндекса -->
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('API_KEY_YANDEX') }}"></script>

    <script type="text/javascript">

        // иницилизация карты в DOM с параметрами по-умолчанию
        var myMap = [];
        var mapN = myMap.length + 1;
        var hasMarker; // признак наличия маркера на карте
        ymaps.ready(init);

        function init() {
            myMap['mapN'] = new ymaps.Map("map{{ $myMapObject }}", {
                center: [{{ $setting_map_center }}], // координаты центра карты
                zoom: {{ $setting_map_scale }} // маштаб карты
            });
            // признак наличия маркера на карте
            hasMarker = false;

            // прочитать долготу и широту с текстовых полей
            if (document.getElementById('{{ $myFieldName2 }}').value != 0 && document.getElementById('{{ $myFieldName2 }}').value != '' && document.getElementById('{{ $myFieldName3 }}').value != 0 && document.getElementById('{{ $myFieldName3 }}').value != '') {

                lat = document.getElementById('{{ $myFieldName2 }}').value;
                long = document.getElementById('{{ $myFieldName3 }}').value;

                // разместить маркер первый раз на карте
                myFunAddPlacemark(lat, long);
            } else {
                // определелить текущее местоположения пользователя
                myFunGetCurrentGeo();
            }
        }

        // определелить текущее местоположения пользователя
        function myFunGetCurrentGeo() {

            geolocation = ymaps.geolocation;
            var location = geolocation.get({
                // выставляем опцию для определения положения по ip
                provider: 'yandex',
                // карта автоматически отцентрируется по положению пользователя
                mapStateAutoApply: true,
                // включим автоматическое геокодирование результата
                autoReverseGeocode: true
            }).then(function (result) {

                    // выведем результат геокодирования на карту
                    myMap['mapN'].geoObjects.add(result.geoObjects);

                    // данные о текущем местоположении
                    address = result.geoObjects.get(0).properties.get('text');
                    lat = result.geoObjects.position[0];
                    long = result.geoObjects.position[1];

                    // выведем в консоль результат геокодирования
                    console.log('Автоположение маркера:');
                    console.log(result.geoObjects.get(0).properties.get('metaDataProperty'));
                    console.log(result.geoObjects.position);
                    console.log(address);
                    console.log(lat);
                    console.log(long);

                    // проверка, был ли уже маркер
                    if (hasMarker == false) {
                        // разместить маркер первый раз на карте
                        myFunAddPlacemark(lat, long);
                    }

                    // масштабирование карты так, чтобы было видно все объекты
                    myFunAutoMapScale();
                }
            );
        }

        // разместить маркер первый раз на карте
        function myFunAddPlacemark(lat, long) {

            myPlacemark = new ymaps.Placemark(
                [lat, long], // координаты метки объекта
                {
                    hintContent: 'Текущая координата', // подсказка метки
                    balloonContent:
                        '<div class="ya_map">' +
                        'Текущая координата' + '<br \/>' +
                        '</div>'
                }, {
                    draggable: true // можно передвигать
                }, {
                    preset: 'islands#greenDotIcon', // тип метки
                },
            );
            // добавление метки на карту
            myMap['mapN'].geoObjects.add(myPlacemark);
            // признак наличия маркера на карте
            hasMarker = true;

            // масштабирование карты так, чтобы было видно все объекты
            myFunAutoMapScale();
        }

        // изменить положение маркера на новые координаты
        function myFunEditPlacemark(lat, long) {

            // установить новые координаты
            myPlacemark.geometry.setCoordinates([lat, long]);

            // масштабирование карты так, чтобы было видно все объекты
            myFunAutoMapScale();
        }

        // клик - переместить маркер в указанные координаты
        function myFunClickMarkerGoToPoint() {

            // прочитать долготу и широту с текстовых полей
            if (document.getElementById('{{ $myFieldName2 }}').value != 0 && document.getElementById('{{ $myFieldName2 }}').value != '' && document.getElementById('{{ $myFieldName3 }}').value != 0 && document.getElementById('{{ $myFieldName3 }}').value != '') {

                lat = document.getElementById('{{ $myFieldName2 }}').value;
                long = document.getElementById('{{ $myFieldName3 }}').value;

                // изменить положение маркера на новые координаты
                myFunEditPlacemark(lat, long);
            }
        }

        // клик - переместить маркер в авто определенное местоположение
        function myFunClickMarkerGoToCurrentGeo() {

            // определелить текущее местоположения пользователя
            myFunGetCurrentGeo();

            // изменить положение маркера на новые координаты
            myFunEditPlacemark(lat, long);
        }

        // масштабирование карты так, чтобы было видно все объекты
        function myFunAutoMapScale() {

            myMap['mapN'].setCenter([lat, long], 15);

            // так как на карте 2-а маркера: автоопределения и текущей точки - то код ниже не совсем подходит
            // myMap['mapN'].setBounds(myMap['mapN'].geoObjects.getBounds(), {checkZoomRange: true}).then(function () {
            //     if (myMap['mapN'].getZoom() > 15) myMap['mapN'].setZoom(15); // Если значение zoom превышает 15, то устанавливаем 15.
            // });
        }

        // клик - записать адрес и координаты маркера в текстовые поля
        function myFunSaveAddressAndCoordinat() {
            // сохранение адреса от положения маркера
            myFunSaveAddress();
            // сохранение координат от положения маркера
            myFunSaveCoordinat();
        }

        // сохранение адреса от положения маркера
        function myFunSaveAddress() {

            // получение координаты точки с карты
            coordinates = myPlacemark.geometry.getCoordinates();

            // определение адреса
            myReverseGeocoder = ymaps.geocode([coordinates[0], coordinates[1]]);
            myReverseGeocoder.then(function (res) {

                // записать в поле
                document.getElementById('{{ $myFieldName1 }}').value = res.geoObjects.get(0).properties.get('text');
            });
        }

        // сохранение координат от положения маркера
        function myFunSaveCoordinat() {

            // получение координаты точки с карты
            coordinates = myPlacemark.geometry.getCoordinates();
            lat = coordinates[0];
            long = coordinates[1];

            // записать в поля
            document.getElementById('{{ $myFieldName2 }}').value = lat;
            document.getElementById('{{ $myFieldName3 }}').value = long;
        }

    </script>
@endsection
