{{-- список --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">{{ App\Models\Connector::title2 }}</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">{{ App\Models\Connector::title2 }}</li>
    </ol>

    {{-- действия на странице --}}
    <div class="page-header-actions">
        <a href="{{ route('yandex_map.index',["model" => "connector"]) }}" class="btn btn-lg btn-icon btn-success" data-toggle="tooltip"
           data-original-title="На карте">
            <i class="icon md-google-maps" aria-hidden="true"></i>
        </a>
        <a href="{{ route('connector.edit') }}" class="btn btn-lg btn-icon btn-primary btn-round" data-toggle="tooltip"
           data-original-title="Создать новую запись">
            <i class="icon md-plus" aria-hidden="true"></i>
        </a>
    </div>

</div>

{{-- содержимое --}}
<div class="page-content">
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-lg-12">

                    <table class="table table-hover" data-plugin="selectable" data-row-selectable="false">
                        <thead>
                        <tr>
                            <th class="w-50">
                                <span class="checkbox-custom checkbox-primary">
                                    <input class="selectable-all" type="checkbox">
                                    <label></label>
                                </span>
                            <th>ID
                            <th>{{ App\Models\Connector::title1 }}
                            <th>Изображение
                            <th>IO Keylink
                            <th>IO Адрес
                            <th>IO Долгота
                            <th>IO Широта
                            <th>Базовое напряжение
                            <th>Конечн.точек (endpoint)
                            <th>Соединен
                            <th>Asset Id
                            <th class="w-50">Действия
                        </thead>

                        <tbody>
                        @foreach($content as $item)
                            <tr>
                                <td>
                                    <span class="checkbox-custom checkbox-primary">
                                        <input class="selectable-item" type="checkbox" id="row-{{ $item->id }}" value="{{ $item->id }}">
                                        <label for="row-{{ $item->id }}"></label>
                                    </span>
                                <td>
                                    <a href="{{ route('connector.edit',['id'=>$item->id]) }}">
                                        {{ $item->id }}
                                    </a>
                                <td>
                                    <a href="{{ route('connector.edit',['id'=>$item->id]) }}">
                                        {{ $item->identifiedobject->name }}
                                    </a>
                                <td>
                                    <a href="/public/{{ $item->getImage('hd','img') }}" target="_blank" title="Открыть в отдельном окне">
                                        <img src="/public/{{ $item->getImage('thumb','img') }}" class="img-index">
                                    </a>
                                <td> {{ $item->identifiedobject->keylink }}
                                <td> {{ substr($item->identifiedobject->address, 0, 30) }}
                                <td> {{ $item->identifiedobject->lat }}
                                <td> {{ $item->identifiedobject->long }}
                                <td> {{ $item->identifiedobject->basevoltage->name }}
                                <td> {{ $item->endpoint->count() }}
                                <td> {{ $item->connected }}
                                <td> {{ $item->asset->id }}
                                <td class="text-nowrap">
                                    <form action="{{ route('connector.destroy',['id'=>$item->id]) }}" method="post">
                                        {{ @csrf_field() }}
                                        {!! method_field('delete') !!}
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Вы уверены?')">
                                            Удалить
                                        </button>
                                    </form>
                        @endforeach

                    </table>

                    {{-- пагинация в списке данных и сообщение Пользователю, если данных нет --}}
                    @include('backend.lib.index_paginate')

                </div>
            </div>
        </div>
    </div>
</div>