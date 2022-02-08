{{-- вывод нескольких таблиц в админке --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">{{ 'Список всех таблиц '.$typeTsbles }} ({{ count($content) }} шт.)</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">{{ 'Список всех таблиц '.$typeTsbles }}</li>
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
                            <th>Имя таблицы
                            <th>Комментарий
                            <th>Строк
                            <th>Список полей
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
                                        {{-- название таблицы с ссылкой на просмотр --}}
                                        <a href="
                                        @if ($typeTsbles == 'MySQL')
                                        {{ route('table.mysql', ['model'=>$item['model']]) }}
                                        @else
                                        {{ route('table.firebird', ['table'=>$item['table']]) }}
                                        @endif
                                                ">{{ $item['table'] }}</a>
                                    <td>
                                    {{-- название таблицы (только для MySql из модели) --}}
                                    {{ $item['comment'] }}
                                    <td>
                                    {{-- кол-во строк --}}
                                    {{ $item['count'] > 0 ? $item['count'] : '-' }}
                                    <td>
                                        {{-- имена полей --}}
                                        @php
                                            if (count($item['fields']) > 0) {
                                            echo "<br><small>";
                                                foreach($item['fields'] as $field) {
                                                echo $field;
                                                if (next($item['fields'])) echo ", ";
                                                }
                                                }
                                                echo "</small>";
                                        @endphp
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