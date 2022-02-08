{{-- редактирование --}}

<form class="form-horizontal" name="form_todo" id="form_todo" action="{{ route('todo.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Todo::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новая
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('todo.index') }}">{{ App\Models\Todo::title2 }}</a></li>
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
                                        <a class="nav-link" data-toggle="tab" href="#tabStages" aria-controls="tabStages" role="tab" aria-selected="true">
                                            Этапы / ФИО
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabFiles" aria-controls="tabFiles" role="tab" aria-selected="true">
                                            Файлы
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Задача --}}
                                    <div class="tab-pane active" id="tabsTask" role="tabpanel">

                                        <div class="row">
                                            <div class="col">

                                                {{-- редактирование названия  --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Наименование',
                                                'myFieldName' => 'name',
                                                'myRequired' => 1,
                                                ])

                                                <div class="row">
                                                    <div class="col">
                                                        {{-- редактирование дататайм --}}
                                                        @include('backend.blocks_edit.datetime',[
                                                        'myFieldTitle' => 'Начало',
                                                        'myFieldName' => 'date_begin',
                                                        ])
                                                    </div>
                                                    <div class="col">
                                                        {{-- редактирование дататайм --}}
                                                        @include('backend.blocks_edit.datetime',[
                                                        'myFieldTitle' => 'Завершение',
                                                        'myFieldName' => 'date_end',
                                                        ])
                                                    </div>
                                                </div>

                                                {{-- выбор из справочника vue-компонент --}}
                                                <options-sprav-component get-sprav='TodoStatus' get-title='Статус' get-field='status_id' get-current-id='{{ $content->status_id }}'>
                                                </options-sprav-component>

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

                                    {{-- вкладка Этапы / ФИО --}}
                                    <div class="tab-pane" id="tabStages" role="tabpanel">

                                        {{-- этапы задач vue-компонент --}}
                                        <todostagefiopivot-component :get-model-id='{{ isset($content->id) ? $content->id : 0 }}'>
                                        </todostagefiopivot-component>

                                    </div>

                                    {{-- вкладка Файлы --}}
                                    <div class="tab-pane" id="tabFiles" role="tabpanel">

                                        {{-- прикрепленные файлы vue-компонент --}}
                                        <todo-file-upload-component :get-model-id='{{ isset($content->id) ? $content->id : 0 }}'>
                                        </todo-file-upload-component>

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




