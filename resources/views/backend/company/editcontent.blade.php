{{-- редактирование --}}

<form class="form-horizontal" name="form_company" id="form_company"
    action="{{ route('company.update', ['id' => $content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Company::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новый
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('company.index') }}">{{ App\Models\Company::title2 }}</a></li>
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
                                            Лого
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
                                                'myFieldTitle' => 'Наименование',
                                                'myFieldName' => 'name',
                                                'myRequired' => 1,
                                                ])

                                                {{-- редактирование названия
                                                --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Телефон',
                                                'myFieldName' => 'phone',
                                                ])

                                                <div class="row">
                                                    <div class="col">
                                                        {{-- редактирование названия
                                                        --}}
                                                        @include('backend.blocks_edit.name',[
                                                        'myFieldTitle' => 'Адрес',
                                                        'myFieldName' => 'address',
                                                        ])
                                                    </div>

                                                    <div class="col">
                                                        {{-- редактирование названия
                                                        --}}
                                                        @include('backend.blocks_edit.name',[
                                                        'myFieldTitle' => 'Координаты',
                                                        'myFieldName' => 'coordinates',
                                                        'myPlaceHolder' => 'широта, долгота',
                                                        ])
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        {{-- редактирование названия
                                                        --}}
                                                        @include('backend.blocks_edit.name',[
                                                        'myFieldTitle' => 'Email',
                                                        'myFieldName' => 'email',
                                                        ])
                                                    </div>

                                                    <div class="col">
                                                        {{-- редактирование названия
                                                        --}}
                                                        @include('backend.blocks_edit.name',[
                                                        'myFieldTitle' => 'Web',
                                                        'myFieldName' => 'web',
                                                        ])
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="col">

                                                {{-- редактирование описания
                                                --}}
                                                @include('backend.blocks_edit.description',[
                                                'myFieldTitle' => 'Описание',
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
