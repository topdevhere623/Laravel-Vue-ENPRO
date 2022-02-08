{{-- список json файлов --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">Файлы к задачам с мобильного приложения</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item"><a href="{{ route('task.index') }}">{{ App\Models\Task::title2 }}</a></li>
        <li class="breadcrumb-item active">Файлы к задачам с мобильного приложения</li>
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
                            <th>#
                            <th>{{ App\Models\Task::title1 }}
                            <th>Файл
                            <th>Размер (байт)
                            <th>Комментарий
                            <th class="w-50">Действия
                        </thead>

                        <tbody>
                        @php $row = 0; @endphp
                        @foreach($content as $item)
                            @php $row++; @endphp
                            <tr id="tr_{{ $row }}">
                                <td> {{ $row }}
                                <td> {{ $item['taskN'] }}
                                <td>
                                    <span onclick="jsonParse({{ $row}}, {{ $item['taskN'] }}, '{{ $item['file'] }}')" class="span_as_ahref">
                                        {{ $item['file'] }}
                                    </span>
                                <td> {{ number_format(round($item['size'], 0)) }}
                                <td id="td_{{ $row }}">
                                <td class="text-nowrap">
                                    <button class="btn btn-danger btn-xs" onclick="jsonDelete({{ $row }}, '{{ $item['file'] }}')">
                                        Удалить
                                    </button>
                        @endforeach

                    </table>

                    @if(!count($content) > 0)

                        {{-- сообщение Пользователю --}}
                        <div class="card card-inverse bg-primary">
                            <div class="card-block">
                                <h4 class="card-title">Внимание!</h4>
                                <p class="card-text">
                                    {{ __('edit.no_data_in_razdel') }}
                                </p>
                            </div>
                        </div>

                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

{{-- модальное окно --}}
@include('backend.lib.modal',[
    'modalTitle' => 'Содержимое json-файла',
])

{{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <script type="text/javascript">

        // просмотр json-файла
        function jsonParse(row, taskN, fileName) {

            // всплывающая подсказка
            toastr.success('Идет парсинг файла...');

            // очистить модальное окно
            $('#modalMessageContent').empty();
            // заголовок модального окна
            $('#modalMessageTitle').text('Задача: [' + taskN + ']. Файл: [' + fileName + ']');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '{{ route('task.jsonParse') }}',
                data: {
                    'fileName': fileName
                },
                method: "POST",
                success: function (result) {
                    // запрос прошел успешно

                    // проверить полученный ответ
                    if (result['code'] != 200) {
                        var myText = 'Ошибка, не получилось прочитать файл!';
                    }
                    else {
                        var myText = result['html'];
                    }

                    $('#td_' + row).text('просмотрен');

                    // записать полученный результат в модальное окно
                    $('#modalMessageContent').html(myText);
                },
                error: function (result) {
                    // возникла ошибка

                    // нужно для отладки
                    console.log(result);

                    $('#td_' + row).text('ошибка');

                    // записать полученный результат в модальное окно
                    $('#modalMessageContent').html('Ошибка, не получилось прочитать файл!');
                }
            });
            // показать модальное окно
            $('#modalMessage').modal('show');
        }

        // удаление json-файла
        function jsonDelete(row, fileName) {

            if (!confirm('Вы уверены?')) return;

            // всплывающая подсказка
            toastr.success('Идет удаление...');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '{{ route('task.jsonDelete') }}',
                data: {
                    'fileName': fileName
                },
                method: "POST",
            }).done(function (result) {
                // нужно для отладки
                //console.log(result);
                // удалить строку
                //$('#tr' + row).remove(); // без эффекта
                $('#tr_' + row).animate({'line-height': 0}, 100).hide(1); // с эффектом
                // всплывающая подсказка
                toastr.success('Идет завершено...');
            });
        }

    </script>
@endsection