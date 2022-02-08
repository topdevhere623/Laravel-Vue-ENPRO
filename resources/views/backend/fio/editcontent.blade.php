{{-- редактирование --}}

<form class="form-horizontal" name="form_fio" id="form_fio" action="{{ route('fio.update', ['id' => $content->id]) }}"
    method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Fio::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новый
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fio.index') }}">{{ App\Models\Fio::title2 }}</a></li>
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
                                        <a class="nav-link active" data-toggle="tab" href="#tabMain"
                                            aria-controls="tabMain" role="tab" aria-selected="true">
                                            Основное
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabImg" aria-controls="tabImg"
                                            role="tab" aria-selected="true">
                                            Аватарка
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabMain" role="tabpanel">

                                        <div class="row">
                                            <div class="col">

                                                {{-- редактирование названия
                                                --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'ФИО',
                                                'myFieldName' => 'name',
                                                'myRequired' => 1,
                                                ])

                                                {{-- выбор из справочника
                                                --}}
                                                <options-sprav-component get-sprav='Company' get-title='Компания'
                                                    get-field='company_id' get-current-id='{{ $content->company_id }}'>
                                                </options-sprav-component>

                                                {{-- редактирование названия
                                                --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Должность',
                                                'myFieldName' => 'position',
                                                ])

                                                {{-- редактирование названия
                                                --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Телефон',
                                                'myFieldName' => 'phone',
                                                ])

                                                {{-- редактирование названия
                                                --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Email',
                                                'myFieldName' => 'email',
                                                ])

                                            </div>

                                            <div class="col">

                                                {{-- редактирование описания
                                                --}}
                                                @include('backend.blocks_edit.description',[
                                                'myFieldTitle' => 'Комментарий',
                                                'myFieldName' => 'description',
                                                ])

                                            </div>

                                        </div>
                                    </div>

                                    {{-- вкладка Изображение
                                    --}}
                                    <div class="tab-pane" id="tabImg" role="tabpanel">

                                        {{-- редактирование поля изображение (из таблицы
                                        file) --}}
                                        @include('backend.blocks_edit.img',[
                                        'myFieldTitle' => 'Аватарка',
                                        'myFieldName' => 'img-current',
                                        ])

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
