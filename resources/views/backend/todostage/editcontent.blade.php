{{-- редактирование --}}

<form class="form-horizontal" name="form_todostage" id="form_todostage" action="{{ route('todostage.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\TodoStage::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новый
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('todostage.index') }}">{{ App\Models\TodoStage::title2 }}</a></li>
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
                                        <a class="nav-link active" data-toggle="tab" href="#tabMain" aria-controls="tabMain" role="tab" aria-selected="true">
                                            Основное
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabMain" role="tabpanel">

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Наименование',
                                        'myFieldName' => 'name',
                                        'myRequired' => 1,
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Сортировка',
                                        'myFieldName' => 'sort',
                                        ])

                                        {{-- редактирование поля статус --}}
                                        @include('backend.blocks_edit.status')

                                    </div>

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

    </script>
@endsection





