{{-- вывод одной таблицы в админке --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">{{ 'Просмотр таблицы "'.Str::title($table).'" ('.$typeTsbles.')' }}</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item"><a href="
            @if ($typeTsbles == 'MySQL')
            {{ route('tables.mysql') }}
            @else
            {{ route('tables.firebird') }}
            @endif
                    ">Все таблицы {{ $typeTsbles }}</a></li>
        <li class="breadcrumb-item active">Просмотр</li>
    </ol>
</div>

{{-- содержимое --}}
<div class="page-content">
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-lg-12">

                    <div class="scrollable-horizontal">

                        <table class="table table-hover" data-plugin="selectable" data-row-selectable="false">
                            <thead>
                            <tr>
                                @foreach ($fields as $field)
                                    <th>{{  $field }}
                            @endforeach
                            </thead>

                            <tbody>

                            @if (count($content) > 0)
                                @foreach($content as $item)
                                    <tr>
                                        @foreach ($fields as $field)
                                            <td>{{ $item->$field }}
                                                @endforeach
                                                @endforeach
                                                @else
                                                    <p class="red">
                                                        Таблица пустая!
                                                    </p>
                            @endif

                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>