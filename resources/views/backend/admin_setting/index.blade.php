{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\AdminModels\AdminSetting::title2 }}
@endsection

{{-- секция контента --}}
@section("content")

    {{-- проверка прав доступа к странице --}}
    @if (
        Auth::user()->isVendor()
        )
        {{-- права есть --}}

        <div class="page-header">
            {{-- заголовок --}}
            <h2 class="page-title">{{ App\AdminModels\AdminSetting::title2 }}</h2>

            {{-- хлебные крошки --}}
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                <li class="breadcrumb-item active">{{ App\AdminModels\AdminSetting::title2 }}</li>
            </ol>
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
                                    <th>{{ App\AdminModels\AdminSetting::title1 }}
                                    <th>Значение
                                    <th>Комментарий
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
                                            <a href="{{ route('admin_setting.edit',['id'=>$item->id]) }}">
                                                {{ $item->key }}
                                            </a>
                                        <td>
                                        {{ $item->value }}
                                        <td>
                                {{ Str::limit($item->comment, 30) }}
                                @endforeach

                            </table>

                            {{-- пагинация в списке данных и сообщение Пользователю, если данных нет --}}
                            @include('backend.lib.index_paginate')

                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection
