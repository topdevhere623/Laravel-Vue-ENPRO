{{-- json в табличной форме расшифровка точек--}}

<div class="row">
    <div class="col-lg-12">

        @if(isset($content->json_parse_file))

            <p>
                Полученные данные с мобильного приложения:
            </p>

            <table class="table table-hover" data-plugin="selectable" data-row-selectable="false">
                <thead>
                <tr>
                    <th>Обьект
                    <th>Id
                    <th>Диспетчерское имя / номер
                    <th>Начальная точка
                    <th>Конечная точка
                    <th>Конечная точка Id
                    <th>Долгота
                    <th>Широта
                    <th>Признак
                </thead>
                <tbody>

                {{-- точки --}}
                @if (isset($content->json_parse_file['points']) and count($content->json_parse_file['points'])>0)
                    @foreach($content->json_parse_file['points'] as $item)
                        <tr>
                            <td> Точка
                            <td> {{ $item['id'] }}
                            <td> {{ $item['dispatcherName'] }}
                            <td>
                            <td>
                            <td>
                            <td> {{ $item['lat'] }}
                            <td> {{ $item['long'] }}
                            <td>
                                @if (isset($item['consumerInputDTOS']) and count($item['consumerInputDTOS']) > 0)
                                    есть потребитель
                        @endif

                        {{-- потребители --}}
                        @if (isset($item['consumerInputDTOS']) and count($item['consumerInputDTOS']) > 0)
                            @foreach ($item['consumerInputDTOS'] as $item2)
                                <tr>
                                    <td> - потребитель (от точки выше)
                                    <td>
                                    <td> {{ $item2['name'] }}
                                    <td>
                                    <td>
                                    <td>
                                    <td> {{ $item2['lat'] }}
                                    <td> {{ $item2['long'] }}
                                    <td>
                            @endforeach
                        @endif
                    @endforeach
                @endif

                {{-- линии 701--}}
                @if (isset($content->json_parse_file['lines701']) and count($content->json_parse_file['lines701'])>0)
                    @foreach($content->json_parse_file['lines701'] as $item)
                        <tr>
                            <td> Воздушная линия 701
                            <td>
                            <td>
                            <td> {{ $item['startPoint'] }}
                            <td> {{ $item['endPoint'] }}
                            <td>
                            <td>
                            <td>
                            <td>
                                @if (isset($item['isSubline']) and $item['isSubline'] == true)
                                    есть отпайка ({{ $item['isSubline'] }})<br>
                                @endif
                                @if (isset($item['cables']) and count($item['cables'])>0)
                                    есть кабельный переход<br>
                                @endif
                                @if (isset($item['transAndInterpas']) and count($item['transAndInterpas'])>0)
                                    есть переходы и пересечения
                        @endif

                        {{-- кабельный переход --}}
                        @if(isset($item['cables']) and count($item['cables']) > 0)
                            @foreach($item['cables'] as $item2)
                                <tr>
                                    <td> - кабельный переход (у линии выше)
                                    <td>
                                    <td>
                                    <td>
                                    <td>
                                    <td>
                                    <td> {{ $item2['lat'] }}
                                    <td> {{ $item2['long'] }}
                                    <td>
                            @endforeach
                        @endif

                        {{-- переходы и пересечения --}}
                        @if(isset($item['transAndInterpas']) and count($item['transAndInterpas']) > 0)
                            @foreach($item['transAndInterpas'] as $item2)
                                <tr>
                                    <td> - переход или пересечение (у линии выше)
                                    <td>
                                    <td> {{ $item2['crossedObjectName'] }}
                                    <td>
                                    <td>
                                    <td>
                                    <td> {{ $item2['lat'] }}
                                    <td> {{ $item2['long'] }}
                                    <td>
                            @endforeach
                        @endif
                    @endforeach
                @endif

                {{-- линии 702--}}
                @if (isset($content->json_parse_file['lines702']) and count($content->json_parse_file['lines702'])>0)
                    @foreach($content->json_parse_file['lines702'] as $key =>$item)
                        <tr>
                            <td> Кабельная линия 702
                            <td>
                            <td>
                            <td> {{ $item['startPoint'] }}
                            <td> {{ $item['endPoint'] }}
                            <td>
                            <td>
                            <td>
                            <td>
                                @if (isset($item['isSubline']) and $item['isSubline'] == true)
                                    есть отпайка ({{ $item['isSubline'] }})<br>
                                @endif
                                @if (isset($item['points']) and count($item['points'])>0)
                                    есть характерные точки
                        @endif

                        {{-- переходы и пересечения --}}
                        @if(isset($item['points']) and count($item['points']) > 0)
                            @foreach($item['points'] as $item2)
                                <tr>
                                    <td> - характерная точка (у линии выше)
                                    <td>{{ $item2['id'] }}
                                    <td> {{ $item2['name'] }}
                                    <td>
                                    <td>
                                    <td>
                                    <td> {{ $item2['lat'] }}
                                    <td> {{ $item2['long'] }}
                                    <td>
                            @endforeach
                        @endif
                    @endforeach
                @endif

            </table>
        @else
            <p>
                {{ __('edit.no_json_file') }}
            </p>
        @endif

    </div>
</div>
