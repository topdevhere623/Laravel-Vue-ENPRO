{{-- карта yandex --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">[{{ $mapTitle }}] на карте</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">[{{ $mapTitle }}] на карте</li>
    </ol>

    {{-- действия на странице --}}
    @if ($mapRoutNew <> '')
        <div class="page-header-actions">
            <a href="{{ route($mapRoutNew) }}" class="btn btn-lg btn-icon btn-primary btn-round" data-toggle="tooltip"
               data-original-title="Создать новую запись">
                <i class="icon md-plus" aria-hidden="true"></i>
            </a>
        </div>
    @endif

</div>

{{-- содержимое --}}
<div class="page-content">
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-lg-12">

                    @if ($mapContent <> '')
                        {{-- данные дл карты есть --}}

                        {{-- кнопка маштабирование --}}
                        <input type="button" id="b_auto_scale" value="Масштабировать">

                        {{-- карта --}}
                        <div id="mapMany" class="map">
                        </div>

                    @else
                        {{-- данных дл карты еще нет --}}
                        <p>
                            {{ __('edit.no_map_objects') }}
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <!-- карта Яндекса -->
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('API_KEY_YANDEX') }}"></script>

    <script type="text/javascript">
        var myMap;
        ymaps.ready(init);

        function init() {
            myMap = new ymaps.Map('mapMany', {
                center: [{{ $setting_map_center }}], // координаты центра карты
                zoom: {{ $setting_map_scale }} // маштаб карты
            });
            myMap.controls.add(
                new ymaps.control.ZoomControl()  // добавление элемента управления картой
            );

            var pointers = {!! json_encode($mapContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
            console.log(pointers);

            for (i = 0; i < pointers.length; ++i) {

                myPlacemark = new ymaps.Placemark(
                    [pointers[i][0], pointers[i][1]],
                    { // Координаты метки объекта
                        balloonContent: "<div class='ya_map'>" + pointers[i][2] + "</div>" // Подсказка метки
                    });
                myMap.geoObjects.add(myPlacemark); // Добавление метки
                //myPlacemark.balloon.open(); // Открытие подсказки метки
            }

            // авто маштабирование
            myFunAutoMapScale();

            $('#b_auto_scale').on('click', function () {
                // авто маштабирование
                myFunAutoMapScale();
            });
        };

        // авто маштабирование
        function myFunAutoMapScale() {
            myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true}).then(function () {
                if (myMap.getZoom() > 15) myMap.setZoom(15); // Если значение zoom превышает 15, то устанавливаем 15.
            });
        }

    </script>

@endsection