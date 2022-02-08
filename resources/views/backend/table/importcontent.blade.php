{{-- импорт таблиц в админке --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">Таблицы с одинаковыми названиями для импорта ({{ count($content) }} шт.)</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">Таблицы с одинаковыми названиями для импорта</li>
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
                            <th>Таблица MySQL
                            <th width="50%">Таблица Firebird
                            <th class="w-50">Импорт
                        </thead>

                        <tbody>

                        @if (count($content) > 0)
                            @foreach($content as $item)
                                <tr>
                                    <td>
                                        <span class="checkbox-custom checkbox-primary">
                                        <input class="selectable-item" type="checkbox" id="" value="">
                                        <label for=""></label>
                                    </span>
                                    <td>
                                        {{-- таблица MySQL --}}
                                        <a href="{{ route('table.mysql', ['model'=>$item['mysql']['model']]) }}">
                                            {{ $item['mysql']['table'] }}
                                        </a>
                                        {{-- кол-во строк --}}
                                        <small>{{ $item['mysql']['count']>0 ? ' ('.$item['mysql']['count'].' стр.)' : '' }}</small>
                                    {{-- имена полей --}}
                                    @php
                                        if (count($item['mysql']['fields']) > 0) {
                                        echo "<br><small>";
                                            foreach($item['mysql']['fields'] as $field) {
                                            echo $field;
                                            if (next($item['mysql']['fields'])) echo ", ";
                                            }
                                            }
                                            echo "</small>";
                                    @endphp
                                    <td>
                                        {{-- таблица Firebird --}}
                                        <a href="{{ route('table.firebird', ['table'=>$item['firebird']['table']]) }}">
                                            {{ $item['firebird']['table'] }}
                                        </a>
                                        {{-- кол-во строк --}}
                                        <small>{{ $item['firebird']['count']>0 ? '   ('.$item['firebird']['count'].' стр.)' : '' }}</small>
                                    {{-- имена полей --}}
                                    @php
                                        if (count($item['firebird']['fields']) > 0) {
                                        echo "<br><small>";
                                            foreach($item['firebird']['fields'] as $field) {
                                            echo $field;
                                            if (next($item['firebird']['fields'])) echo ", ";
                                            }
                                            }
                                            echo "</small>";
                                    @endphp
                                    <td class="text-nowrap" style="vertical-align: middle;">
                                        <button type="button" class="btn btn-danger btn-xs" onclick="myFunAjaxImportTable('{{ $item['mysql']['model'] }}')">
                                            Импорт
                                        </button>
                                        @endforeach
                                        @else
                                            <p>
                                                Таблиц не обнаружено!
                                            </p>
                        @endif

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- модальное окно --}}
@php $modalTitle = 'Импорт данных'; @endphp
@include('backend.lib.modal')

