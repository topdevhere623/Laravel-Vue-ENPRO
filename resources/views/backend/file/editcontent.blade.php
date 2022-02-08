{{-- редактирование --}}

<form class="form-horizontal" name="form_file" action="{{ route('file.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\File::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новый
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('file.index') }}">{{ App\Models\File::title2 }}</a></li>
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
                                        <a class="nav-link active" data-toggle="tab" href="#tabsMain" aria-controls="tabsMain" role="tab" aria-selected="true">
                                            Основное
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabParameters" aria-controls="tabParameters" role="tab" aria-selected="false">
                                            Дополнительные параметры
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabsMain" role="tabpanel">

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Наименование',
                                        'myFieldName' => 'title',
                                        'myRequired' => 1,
                                        ])

                                        {{-- редактирование поля изображение (из таблицы file) --}}
                                        @include('backend.blocks_edit.img',[
                                        'myFieldTitle' => 'Изображение',
                                        'myFieldName' => 'src',
                                        ])

                                    </div>

                                    {{-- вкладка Дополнительные параметры --}}
                                    <div class="tab-pane" id="tabParameters" role="tabpanel">

                                        {{-- редактирование описания --}}
                                        @include('backend.blocks_edit.description',[
                                        'myFieldTitle' => 'Описание',
                                        'myFieldName' => 'description',
                                        ])

                                        {{-- редактирование picturetype (типа изображения) --}}
                                        @include('backend.blocks_edit.options_picturetype')

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Тип',
                                        'myFieldName' => 'type',
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



