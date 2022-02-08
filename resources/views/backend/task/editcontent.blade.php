{{-- редактирование --}}

<form class="form-horizontal" name="form_task" id="form_task" action="{{ route('task.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Task::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новая
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('task.index') }}">{{ App\Models\Task::title2 }}</a></li>
            <li class="breadcrumb-item active">Редактирование</li>
        </ol>

        {{-- действия на странице --}}
        <div class="page-header-actions">
            {{-- кнопка сохранить --}}
            @include('backend.blocks_edit.b_save')
        </div>

    </div>

    {{-- содержимое --}}
    <div class="page-content">

        <input type="hidden" name="id" value="{{ $content->id }}">

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-bordered form-icons">
                    <div class="panel-body">

                        <div class="example-wrap">
                            <div class="nav-tabs-horizontal" data-plugin="tabs">
                                <ul class="nav nav-tabs" role="tablist">

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-toggle="tab" href="#tabsTask" aria-controls="tabsTask" role="tab" aria-selected="true">
                                            Задача
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabsSubstation" aria-controls="tabsSubstation" role="tab" aria-selected="true">
                                            ТП
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabsConnector" aria-controls="tabsConnector" role="tab" aria-selected="true">
                                            Фидер
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabJson" aria-controls="tabJson" role="tab" aria-selected="false">
                                            Мобильное приложение
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabMap" aria-controls="tabMap" role="tab" aria-selected="false">
                                            На карте
                                        </a>
                                    </li>

                                    @if (false)
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-toggle="tab" href="#tabImages" aria-controls="tabImages" role="tab" aria-selected="false">
                                                Изображения
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Задача --}}
                                    <div class="tab-pane active" id="tabsTask" role="tabpanel">

                                        <div class="row">

                                            <div class="col">

                                                {{-- редактирование названия  --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Наименование',
                                                'myFieldName' => 'title',
                                                'myRequired' => 1,
                                                ])

                                                <div class="row">

                                                    <div class="col">
                                                        {{-- редактирование tasktype (типа задачи) --}}
                                                        @include('backend.blocks_edit.options_tasktype')
                                                    </div>

                                                    <div class="col">
                                                        {{-- редактирование user (пользователя) --}}
                                                        @include('backend.blocks_edit.options_user')
                                                    </div>

                                                </div>

                                                {{-- редактирование названия  --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Link',
                                                'myFieldName' => 'link',
                                                ])

                                                {{-- редактирование цифрового значения --}}
                                                @include('backend.blocks_edit.number',[
                                                'myFieldTitle' => 'Сортировка',
                                                'myFieldName' => 'sorting',
                                                ])

                                                {{-- редактирование цифрового значения --}}
                                                @include('backend.blocks_edit.number',[
                                                'myFieldTitle' => 'Статус',
                                                'myFieldName' => 'status',
                                                ])

                                                @if (false)
                                                    {{-- редактирование поля дата --}}
                                                    @include('backend.blocks_edit.date',[
                                                    'myFieldTitle' => 'Начало',
                                                    'myFieldName' => 'startdate',
                                                    ])

                                                    {{-- редактирование поля дата --}}
                                                    @include('backend.blocks_edit.date',[
                                                    'myFieldTitle' => 'Завершение',
                                                    'myFieldName' => 'enddate',
                                                    ])
                                                @endif

                                            </div>

                                            <div class="col">

                                                {{-- редактирование описания --}}
                                                @include('backend.blocks_edit.description',[
                                                'myFieldTitle' => 'Описание',
                                                'myFieldName' => 'description',
                                                ])

                                            </div>

                                        </div>
                                    </div>

                                    {{-- вкладка Подстанция --}}
                                    <div class="tab-pane" id="tabsSubstation" role="tabpanel">

                                        {{-- редактирование substation (подстанции) --}}
                                        @include('backend.blocks_edit.options_substation')
                                        <input type="button" value="Новая ТП" onclick="myFunAjaxOpenContent('substation')">

                                    </div>

                                    {{-- вкладка Фидер --}}
                                    <div class="tab-pane" id="tabsConnector" role="tabpanel">


                                        {{-- редактирование connector (фидеры) --}}
                                        @include('backend.blocks_edit.options_connector')
                                        <input type="button" value="Новый фидер" onclick="myFunAjaxOpenContent('connector')">

                                        {{-- связанные данные - конечные точки --}}
                                        <div id="relatedEndpoint" style="margin-top: 20px;"></div>

                                        <input type="button" value="Новая конечная точка" onclick="myFunAjaxOpenContent('endpoint')">

                                    </div>

                                    {{-- вкладка Json-файл --}}
                                    <div class="tab-pane" id="tabJson" role="tabpanel">

                                        {{-- json текстовый комментарий с фото --}}
                                        @include('backend.task.edit_json_comment')

                                    </div>

                                    {{-- вкладка Объект на карте --}}
                                    <div class="tab-pane" id="tabMap" role="tabpanel">

                                        {{-- json на карте --}}
                                        @include('backend.task.edit_json_in_map')

                                    </div>

                                    @if (false)
                                        {{-- вкладка Изображения --}}
                                        <div class="tab-pane" id="tabImages" role="tabpanel">

                                            {{-- выбор изображения из таблицы file и сохранение в pivot --}}
                                            @include('backend.blocks_edit.img_as_pivot')

                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- модальное окно --}}
@include('backend.lib.modal',[
    'modalTitle' => 'Ввод новой строчки',
])

{{-- секция моих скриптов --}}
@section("scripts")
    @parent

    <script type="text/javascript">

        // после загрузки страницы
        $(document).ready(function () {
            // загрузка конечные точки фидера
            myFunAjaxLoadRelatedData();
        });

        // назначить событие выпадающему списку фидеров
        $('#connector_id').bind('change', '', function (e) {
            // загрузка конечные точки фидера
            myFunAjaxLoadRelatedData();
        });

        // получение контента зависимых сущностей
        function myFunAjaxOpenContent(objectName) {

            switch (objectName) {
                case 'substation':
                    var myUrl = '{{ route('ajaxNewSubstation') }}';
                    var myTitle = 'Новая ТП';
                    break;
                case 'connector':
                    var myUrl = '{{ route('ajaxNewConnector') }}';
                    var myTitle = 'Новый фидер';
                    break;
                case 'endpoint':
                    var myUrl = '{{ route('ajaxNewEndpoint') }}';
                    var myTitle = 'Новая конечная точка';
                    break;
            }

            // очистить модальное окно
            $('#modalMessageContent').empty();
            // заголовок модального окна
            $('#modalMessageTitle').text(myTitle);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: myUrl,
                data: {
                    'thisModal': objectName,
                },
                async: false, // ??????????????????????????
                context: document.body,
                method: "POST",
            }).done(function (result) {
                // нужно для отладки
                //console.log(result.html);
                // записать полученный результат в модальное окно
                $('#modalMessageContent').html(result.html);
                // показать модальное окно
                $('#modalMessage').modal('show');
                // всплывающая подсказка
                //toastr.success('Готово...');
            });
        }

        // сохранение новой введенной сущности
        function myFunAjaxSaveInModal(objectName) {

            switch (objectName) {
                case 'substation':
                    var myUrl = '{{ route('ajaxNewSubstationSave') }}';
                    break;
                case 'connector':
                    var myUrl = '{{ route('ajaxNewConnectorSave') }}';
                    break;
                case 'endpoint':
                    var myUrl = '{{ route('ajaxNewEndpointSave') }}';
                    break;
            }
            // данные формы, с котрой принимаю введенные данные
            var myData = $("#form_" + objectName).serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: myUrl,
                data: myData,
                //async: false, // ??????????????????????????
                method: "POST",
            }).done(function (result) {
                // нужно для отладки
                //console.log(result);
                // скрыть модальное окно
                $('#modalMessage').modal("hide");
                // очистить модальное окно
                $('#modalMessageContent').empty();

                // добавить элемент в конец списка <select>
                var mySelect = objectName + '_id';
                $("#" + mySelect).append('<option value="' + result['id'] + '">' + result['name'] + '</option>');
                // сделать выбранным этот последний элемент
                $("#" + mySelect + " :last").attr("selected", "selected");

                // загрузка конечные точки фидера
                myFunAjaxLoadRelatedData();

                // всплывающая подсказка
                toastr.success('Данные успешно сохранены...');
            });
        }

        // загрузка конечные точки фидера
        function myFunAjaxLoadRelatedData() {
            // текущий фидер
            connector_id = $('#connector_id').val();

            // всплывающая подсказка
            //toastr.success('Идет обновление списка конечных точек...');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '{{ route('ajaxLoadRelatedEndpoint') }}',
                data: {'connector_id': connector_id},
                method: "POST",
            }).done(function (result) {
                // нужно для отладки
                //console.log(result);
                // очистить поле вывода
                $('#relatedEndpoint').empty();
                // проверить полученный ответ
                if (result['html'].length > 0) {
                    var myText = result['html']; // +
                    //"<p><i>Примечание: Наименование обьектов и справочников - активные ссылки, по которым Вы можете перейти в отдельное окно для редактирования.</i></p>";
                }
                else {
                    var myText = 'У данного фидера конечных точек не обнаружено!';

                }
                // записать полученный результат в поле вывода
                $('#relatedEndpoint').html(myText);
            });
        }
    </script>
@endsection





