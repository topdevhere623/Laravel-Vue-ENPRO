{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\AdminModels\AdminUserRole::title2 }}
@endsection

{{-- секция контента --}}
@section("content")

    {{-- проверка прав доступа к странице --}}
    @if (
        Auth::user()->isVendor() or
        Auth::user()->isAdmin() or
        Auth::user()->isManager() or
        Auth::user()->isDispatcher() or
        Auth::user()->isOperator() or
        Auth::user()->isMaster() or
        Auth::user()->isWorking()
        )
        {{-- права есть --}}

        <div class="page-header">
            {{-- заголовок --}}
            <h2 class="page-title">{{ App\AdminModels\AdminUserRole::title2 }}</h2>

            {{-- хлебные крошки --}}
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                <li class="breadcrumb-item active">{{ App\AdminModels\AdminUserRole::title2 }}</li>
            </ol>

        </div>

        {{-- содержимое --}}
        <div class="page-content main-content">
            <div class="row row-lg">
                <div class="col-lg-12">

                    <div class="table-wrapper table-auto-height">
                        <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">
                            <thead>
                            <tr>
                                <th class="w-50">
                                <span class="checkbox-custom checkbox-primary">
                                    <input class="selectable-all" type="checkbox">
                                    <label></label>
                                </span>
                                <th class="no-wrap">ID
                                <th>{{ App\AdminModels\AdminUserRole::title1 }}
                                <th>Комментарий
                                <th class="no-wrap">Импорт
                                <th class="no-wrap">API
                                <th class="no-wrap">Паспортизация
                                <th class="no-wrap">Справочники
                                <th class="no-wrap">Настройка
                                <th class="no-wrap">Пользователи
                            </thead>

                            <tbody>
                            @foreach($content as $item)
                                <tr>
                                    <td>
                                    <span class="checkbox-custom checkbox-primary">
                                        <input class="selectable-item" type="checkbox" id="row-{{ $item->id }}" value="{{ $item->id }}">
                                        <label for="row-{{ $item->id }}"></label>
                                    </span>
                                    </td>
                                    <td>
                                        {{ $item->id }}
                                    </td>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                    <td>
                                        {{ $item->comment }}
                                    </td>
                                    <td>
                                        @php
                                            switch ($item->import) {
                                                case 1:
                                                    echo "+";
                                                    break;
                                                case 2:
                                                    echo "ред.";
                                                    break;
                                                default:
                                                    echo "-";
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            switch ($item->api) {
                                                case 1:
                                                    echo "+";
                                                    break;
                                                case 2:
                                                    echo "ред.";
                                                    break;
                                                default:
                                                    echo "-";
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                    @php
                                        switch ($item->tasks) {
                                            case 1:
                                                echo "+";
                                                break;
                                            case 2:
                                                echo "ред.";
                                                break;
                                            default:
                                                echo "-";
                                        }
                                    @endphp
                                    <td>
                                        @php
                                            switch ($item->spravs) {
                                                case 1:
                                                    echo "+";
                                                    break;
                                                case 2:
                                                    echo "ред.";
                                                    break;
                                                default:
                                                    echo "-";
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            switch ($item->settings) {
                                                case 1:
                                                    echo "+";
                                                    break;
                                                case 2:
                                                    echo "ред.";
                                                    break;
                                                default:
                                                    echo "-";
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            switch ($item->users) {
                                                case 1:
                                                    echo "+";
                                                    break;
                                                case 2:
                                                    echo "ред.";
                                                    break;
                                                default:
                                                    echo "-";
                                            }
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>

                    {{-- пагинация в списке данных и сообщение Пользователю, если данных нет --}}
                    @include('backend.lib.index_paginate')

                </div>
            </div>
        </div>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection
