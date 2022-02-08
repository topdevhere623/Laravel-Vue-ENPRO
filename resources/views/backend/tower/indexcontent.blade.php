{{-- список --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">{{ App\Models\Tower::title2 }}</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">{{ App\Models\Tower::title2 }}</li>
    </ol>

    {{-- действия на странице --}}
    {{--<div class="page-header-actions">--}}
        {{--<a href="{{ route('tower.edit') }}" class="btn btn-lg btn-icon btn-primary btn-round" data-toggle="tooltip"--}}
           {{--data-original-title="Создать новую запись">--}}
            {{--<i class="icon md-plus" aria-hidden="true"></i>--}}
        {{--</a>--}}
    {{--</div>--}}

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
                            <th>{{ App\Models\Tower::title1 }}
                            <th>Материал
                            <th>Назначение
                            <th>Марка
                            <th>Конструкция
                            <th>Кол-во стоек
                            <th>Оттяжка
                            <th>Подкос
                            <th>Приставка
                            <th>Разъединитель
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
                                    <a href="{{ route('tower.edit',['id'=>$item->id]) }}">
                                        {{ $item->id }}
                                    </a>
                                <td>
                                    <a href="{{ route('tower.edit',['id'=>$item->id]) }}">
                                        {{ Str::limit($item->identifiedobject->name, 30) }}
                                    </a>
                                <td>
                                {{ $item->towermaterial->name }}
                                <td>
                                {{ $item->towerkind->name }}
                                <td>
                                {{ $item->towerinfo->name }}
                                <td>
                                {{ $item->towerconstruction->name }}
                                <td>
                                {{ $item->propn }}
                                <td>
                                {{ $item->guy }}
                                <td>
                                {{ $item->strut }}
                                <td>
                                {{ $item->annex }}
                                <td>
                                {{ $item->disconnector }}

                                <td class="text-nowrap">
                                    <form action="{{ route('tower.destroy',['id'=>$item->id]) }}" method="post">
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