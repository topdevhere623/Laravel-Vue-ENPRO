{{-- объект на карте --}}

@if (!is_null($myLat) and !is_null($myLong))
    <div class="row">
        <div class="col-md-12">

            {{-- карта --}}
            <div id="mapOne" class='map'>
            </div>

        </div>
    </div>

    {{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <!-- карта Яндекса -->
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('API_KEY_YANDEX') }}"></script>

    <script type="text/javascript">

        ymaps.ready(init);
        var myMap;

        function init() {

            myMap = new ymaps.Map("mapOne", {
                center: [{{ $myLat }}, {{ $myLong }}], // координаты центра карты
                zoom: {{ $setting_map_scale_one }} // маштаб карты
            });

            myMap.controls.add(
                new ymaps.control.ZoomControl()  // Добавление элемента управления картой
            );

            var pointer = [
                [{{ $myLat }}, {{ $myLong }}], 'Наименование: {{ $myName ?? 'Объект' }}<br \/>Адрес: {{ $myAddress }}'
            ];
            myPlacemark = new ymaps.Placemark(pointer[0], { // Координаты метки объекта
                balloonContent: "<div class='ya_map'>" + pointer[1] + "</div>" // Подсказка метки
            }, {
                preset: "twirl#redDotIcon" // Тип метки
            });

            myMap.geoObjects.add(myPlacemark); // Добавление метки
            myPlacemark.balloon.open(); // Открытие подсказки метки

        };

    </script>

@endsection
@else
    <p>
        {{ __('edit.no_map_coordinates') }}
    </p>
@endif
