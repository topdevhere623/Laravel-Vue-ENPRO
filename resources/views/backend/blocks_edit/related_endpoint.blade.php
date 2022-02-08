{{-- связанные данные - конечные точки --}}

{{-- секция для вывода связанных конечных точек --}}
@section("content_related_endpoint")

    <div class="form-group col-md-12">
        <h4 class="example-title">
            <a href="{{ route('endpoint.index') }}" target="_blank">
                Конечные точки данного фидера
            </a>
        </h4>

        @if (count($myRelatedEndpoint) > 0)

            <table class="table table-hover" data-plugin="selectable" data-row-selectable="false">
                <thead>
                <tr>
                    <th>ID
                    <th>IO Наименование
                    <th>IO Адрес
                    <th>IO Долгота
                    <th>IO Широта
                </thead>
                <tbody>
                @foreach($myRelatedEndpoint as $item)
                    <tr>
                        <td> {{ $item->id }}
                        <td>
                            <a href="{{ route('endpoint.edit',['id'=>$item->id]) }}" target="_blank">
                                {{ $item->identifiedobject->name }}
                            </a>
                        <td> {{ $item->identifiedobject->address }}
                        <td> {{ $item->identifiedobject->lat }}
                        <td> {{ $item->identifiedobject->long }}
                @endforeach

            </table>
        @else
            <p>
                Еще не указана ни одна конечная точка у фидера.
            </p>
        @endif

    </div>

@endsection

{{-- секция схемы фидера --}}
@section("content_connector_sheme")

    <h4 class="example-title">Схема фидера</h4>
    <a href="{{ $sheme_hd ?? '/public/uploads/default/default_hd.png' }}" target="_blank" title="Открыть в отдельном окне">
        <img src="{{ $sheme_thumb ?? '/public/uploads/default/default_thumb.png' }}" style="height: 200px;">
    </a>

@endsection

@if (isset($sheme_hd))

    <div class="row">

        <div class="col">
            {{-- область для вывода связанных конечных точек --}}
            @yield('content_related_endpoint')
        </div>

        <div class="col">
            {{-- область для вывода схемы фидера --}}
            @yield('content_connector_sheme')
        </div>

    </div>

@else

    <div class="row">
        <div class="form-group col-md-12">
            {{-- область для вывода связанных конечных точек --}}
            @yield('content_related_endpoint')
        </div>
    </div>

@endif