{{-- редактирование --}}

<form class="form-horizontal" name="form_substation" id="form_substation" @if (!isset($thisModal))
    action="{{ route('substation.update', ['id' => $content->id]) }}"
    @endif
    method="POST" enctype="multipart/form-data">

    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Substation::title1 }} -
            @if (isset($content->id))
                {{ $content->identifiedobject->name }}
            @else
                Новая
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('substation.index') }}">{{ App\Models\Substation::title2 }}</a></li>
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

        {{-- скрытые поля --}}
        <input type="hidden" name="id" value="{{ $content->id }}">
        @if (isset($thisModal))
            <input type="hidden" name="thisModal" value="{{ $thisModal }}">
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-bordered form-icons">
                    <div class="panel-body">

                        <div class="example-wrap">
                            <div class="nav-tabs-horizontal" data-plugin="tabs">
                                <ul class="nav nav-tabs" role="tablist">


                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-toggle="tab" href="#tabSubstationMain"
                                            aria-controls="tabSubstationMain" role="tab" aria-selected="true">
                                            ТП
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabSubstationIO"
                                            aria-controls="tabSubstationIO" role="tab" aria-selected="false">
                                            Общие технические данные IO
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabSubstationImages"
                                            aria-controls="tabSubstationImages" role="tab" aria-selected="false">
                                            Схема
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabSubstationBusbarsection"
                                            aria-controls="tabSubstationBusbarsection" role="tab" aria-selected="false">
                                            РУ
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabSubstationFile"
                                            aria-controls="tabSubstationFile" role="tab" aria-selected="false">
                                            Файлы
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabSubstationMain" role="tabpanel">

                                        <div class="row">
                                            <div class="col">

                                                {{-- редактирование substationinfo
                                                (информация о подстанции) --}}
                                                @include('backend.blocks_edit.options_substationinfo')

                                                {{-- редактирование address (адреса)
                                                --}}
                                                {{--
                                                @include('backend.blocks_edit.options_address')
                                                --}} {{--
                                                адрес решили из IO брать--}}

                                            </div>

                                            <div class="col">

                                                {{-- редактирование названия
                                                --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Паспорт (passport)',
                                                'myFieldName' => 'passport',
                                                ])

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane" id="tabSubstationIO" role="tabpanel">

                                        {{-- редактирование данных IO в дочернем обьекте
                                        --}}
                                        @include('backend.blocks_edit.io', [
                                        'myMapObject' => 'Substation'
                                        ])

                                    </div>


                                    {{-- вкладка Изображения и прикрепленные файлы
                                    --}}
                                    <div class="tab-pane" id="tabSubstationImages" role="tabpanel">

                                        {{-- редактирование поля изображение (из таблицы
                                        file) --}}
                                        @include('backend.blocks_edit.img',[
                                        'myFieldTitle' => 'Изображение',
                                        'myFieldName' => 'img',
                                        ])

                                    </div>
                                    {{-- вкладка Изображения и прикрепленные файлы
                                    --}}
                                    <div class="tab-pane" id="tabSubstationBusbarsection" role="tabpanel">

                                        {{-- add connectivity
                                        --}}
                                        @include('backend.blocks_edit.busbarsection')

                                    </div>
                                    {{-- вкладка Изображения и прикрепленные файлы
                                    --}}
                                    <div class="tab-pane" id="tabSubstationFile" role="tabpanel">

                                        {{-- редактирование поля изображение (из таблицы
                                        file) --}}
                                        @include('backend.blocks_edit.file',[
                                        'myFieldTitle' => 'Файлы',
                                        'myFieldName' => 'scheme',
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
