{{-- список --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">{{ App\Models\Company::title2 }}</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">{{ App\Models\Company::title2 }}</li>
    </ol>

    {{-- действия на странице --}}
    <div class="page-header-actions">
        <a href="{{ route('company.edit') }}" class="btn btn-lg btn-icon btn-primary btn-round" data-toggle="tooltip"
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
                            <th>{{ App\Models\Company::title1 }}
                            <th>Лого
                            <th>Описание
                            <th>Телефон
                            <th>Адрес
                            <th>Координаты
                            <th>Email
                            <th>Web
                            <th>Кол.сотр.
                            <th class="w-50">Сортировка
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
                                    <a href="{{ route('company.edit',['id'=>$item->id]) }}">
                                        {{ $item->id }}
                                    </a>
                                <td>
                                    <a href="{{ route('company.edit',['id'=>$item->id]) }}">
                                        {{ substr($item->name, 0, 30) }}
                                    </a>
                                <td>
                                    <a href="/public/{{ $item->getImage('hd','img') }}" target="_blank" title="Открыть в отдельном окне">
                                        <img src="/public/{{ $item->getImage('thumb','img') }}" class="img-index">
                                    </a>
                                <td> {{ substr($item->description, 0, 30) }}
                                <td> {{ substr($item->phone, 0, 30) }}
                                <td> {{ substr($item->address, 0, 30) }}
                                <td> {{ substr($item->coordinates, 0, 30) }}
                                <td> {{ substr($item->email, 0, 30) }}
                                <td> {{ substr($item->web, 0, 30) }}
                                <td> {{ $item->fio->count() }}
                                <td> {{ $item->sort }}
                                <td>
                                    <li class="list-inline-item mr-5">
                                        <input type="checkbox" data-plugin="switchery" data-size="small" @if ($item->status) checked @endif onchange="myFunAjaxChangeField( {{ $item->id }}, 'Company')" name="status"
                                               @if (
                                                    !Auth::user()->isVendor() and
                                                    !Auth::user()->isAdmin() and
                                                    !Auth::user()->isManager() and
                                                    !Auth::user()->isOperator()
                                                    )
                                               disabled
                                            @endif
                                        >
                                    </li>
                                <td class="text-nowrap">
                                    <form action="{{ route('company.destroy',['id'=>$item->id]) }}" method="post">
                                        {{ @csrf_field() }}
                                        {!! method_field('delete') !!}
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Вы уверены?')"
                                                @if (
                                                    !Auth::user()->isVendor() and
                                                    !Auth::user()->isAdmin() and
                                                    !Auth::user()->isManager() and
                                                    !Auth::user()->isOperator()
                                                    )
                                                disabled
                                            @endif
                                        >
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
