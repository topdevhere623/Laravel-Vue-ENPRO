{{-- список --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">{{ App\Models\Task::title2 }}</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">{{ App\Models\Task::title2 }}</li>
    </ol>

    {{-- действия на странице --}}
    <div class="page-header-actions">
        <a href="{{ route('task.edit') }}" class="btn btn-lg btn-icon btn-primary btn-round" data-toggle="tooltip"
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
                            <th>{{ App\Models\Task::title1 }}
                            <th>Тип
                            <th>Пользователь
                            <th>ТП
                            <th>Адрес ТП
                            <th colspan="2" style="text-align: center;">Фидер
                            <th>Кол.кон.точ.
                            <th>Описание
                            <th colspan="2" style="text-align: center;">Файлов с моб.прилож.
                            <th>Link
                            <th class="w-50">Статус
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
                                    <a href="{{ route('task.edit',['id'=>$item->id]) }}">
                                        {{ $item->id }}
                                    </a>
                                <td>
                                    <a href="{{ route('task.edit',['id'=>$item->id]) }}">
                                        {{ $item->title }}
                                    </a>
                                <td> {{ $item->tasktype->title }}
                                <td> {{ substr($item->user->username, 0, 30) }}
                                <td> {{ substr($item->substation->identifiedobject->name, 0, 30) }}
                                <td> {{ substr($item->substation->identifiedobject->address, 0, 30) }}
                                <td> {{ substr($item->connector->identifiedobject->name, 0, 30) }}
                                <td>
                                    <a href="/public/{{ $item->connector->getImage('hd','img') }}" target="_blank" title="Открыть в отдельном окне">
                                        <img src="/public/{{ $item->connector->getImage('thumb','img') }}" class="img-index">
                                    </a>
                                <td> {{ $item->connector->endpoint->count() }}
                                <td> {{ substr($item->description, 0, 35) }}
                                <td>
                                    <small>
                                        {{ $item->json_file }}
                                    </small>
                                <td>
                                    <small>
                                        <a href="{{ route('task.jsonFiles',['id'=>$item->id]) }}">
                                            {{ $item->jsonFilesKol }}
                                        </a>
                                    </small>
                                <td> {{ $item->link }}
                                <td> {{ $item->status }}
                                <td class="text-nowrap">
                                    <form action="{{ route('task.destroy',['id'=>$item->id]) }}" method="post">
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