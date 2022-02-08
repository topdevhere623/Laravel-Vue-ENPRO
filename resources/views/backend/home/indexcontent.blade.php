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
                <div class="header-page-bottom">
                    <div class="left">
                        <h3>Добро пожаловать, {{ Auth::user()->username ?? ''}}!</h3>
                        Вы вошли в Админ-панель CRM "{{ $setting_title }}"
                    </div>
                    <div class="right">
                        @if ($mapContent <> '')
                            {{-- кнопка маштабирование --}}
                            <input type="button" id="b_auto_scale" class="button" value="Масштабировать все объекты">
                            {{-- очистить кеш --}}
                            {{--<input type="button" class="btn-info" value="Очистить кеш" onclick="myFunAjaxClearCache()">--}}

                        @endif
                    </div>
                </div>
            </div>

            {{-- карта --}}
            <iframe class="map-auto-height" src="https://yandex.ru/map-widget/v1/?um=constructor%3A06f56ae8d09c61d739ae3c31fac057967404998ea98bfd6afd4899341137bb57&amp;source=constructor" width="100%" frameborder="0"></iframe>

            @if ($mapContent <> '')
                {{--<div id="mapMany" class="map map-auto-height"></div>--}}
            @endif

            @if (false)
                <div class="row">
                    <div class="col-sm-6">

                        @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isOperator()
                            )
                            <p>
                                <br>
                                <strong>Инструкция</strong> по работе с API <a href="{{ route('apiInstruction') }}">здесь...</a>

                                <br><br>
                                <strong>Отладка</strong> запросов по API <a href="{{ route('apiQueries') }}">здесь...</a>
                            </p>
                        @endif

                        @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isOperator()
                            )
                            <p>
                                <strong>Общий вид</strong> всех таблиц и связей:
                            </p>
                            <img src="/public/uploads/all_tables.png" width="75%">
                        @endif

                    </div>

                    <div class="col-sm-6">

                        @if (Auth::user()->role[0]->spravs > 0)
                            <div class="allTables">
                                <p>
                                    <br>
                                    <strong>Список всех таблиц, имеющихся в базе данных ({{ count($tablesMySQLwithFields) }} шт.):</strong>
                                </p>

                                @if (count($tablesMySQLwithFields) > 0)
                                    @foreach($tablesMySQLwithFields as $item)
                                        <a href="{{ route('table.mysql', ['model'=>$item['model']]) }}">
                                            {{ ucfirst(strtolower($item['table'])) }}
                                        </a>
                                        @if (!$item['comment'] == '')
                                        {{ " - " . $item['comment'] }}
                                        @endif
                                        @if ($item['count']>0)
                                        {{ " (стр.: " . $item['count'] . ")" }}
                                        @endif
                                        </a>
                                        <br>
                                    @endforeach
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <!-- карта Яндекса -->
    {{--<script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('API_KEY_YANDEX') }}"></script>--}}

    <script type="text/javascript">
        if (false) {
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
        }

    </script>

@endsection
