{{-- редактирование --}}

<form class="form-horizontal" name="form_identifiedobject" action="{{ route('identifiedobject.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Identifiedobject::title1 }}
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('identifiedobject.index') }}">{{ App\Models\Identifiedobject::title2 }}</a></li>
            <li class="breadcrumb-item active">{{ $content->id ? 'Редактирование - '.App\Models\Identifiedobject::title2 : 'Создание - '.App\Models\Identifiedobject::title2 }}</li>
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
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabOtherData" aria-controls="tabOtherData" role="tab" aria-selected="false">
                                            Другие данные
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabsMain" role="tabpanel">

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Диспетчерское имя',
                                        'myFieldName' => 'name',
                                        'myRequired' => 1,
                                        ])
                                        @error('name')
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <strong>Ошибка!</strong> Длина поля должна быть больше 2-х символов.
                                        </div>
                                        @enderror

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Диспетчерский номер',
                                        'myFieldName' => 'localname',
                                        ])

                                        {{-- выбор из справочника vue-компонент --}}
                                        <options-sprav-component get-sprav='Acline' get-title='ЛЭП' get-field='acline_id' get-current-id='{{ $content->acline_id }}'>
                                        </options-sprav-component>

                                        {{-- редактирование описания  --}}
                                        @include('backend.blocks_edit.description',[
                                        'myFieldTitle' => 'Описание',
                                        'myFieldName' => 'description',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Keylink',
                                        'myFieldName' => 'keylink',
                                        ])

                                    </div>

                                    {{-- вкладка Дополнительные параметры --}}
                                    <div class="tab-pane" id="tabParameters" role="tabpanel">

                                        {{-- редактирование classname (имя класса) --}}
                                        @include('backend.blocks_edit.options_classname')

                                        {{-- редактирование subclass (имя подкласса) --}}
                                        @include('backend.blocks_edit.options_subclass')

                                        {{-- редактирование basevoltage (базовые напряжения) --}}
                                        @include('backend.blocks_edit.options_basevoltage',[
                                        'myFieldName' => 'voltage_id',
                                        'myFieldValue' => $content->voltage_id,
                                        ])

                                        {{-- редактирование asset (общие данные) --}}
                                        @include('backend.blocks_edit.options_asset')

                                        {{-- редактирование enobj (???) // нет такого!!! --}}
                                        {{-- @include('backend.blocks_edit.options_enobj') --}}

                                        {{-- редактирование subcontrollarea (???) --}}
                                        @include('backend.blocks_edit.options_subcontrolarea')

                                        {{-- редактирование bay (???) --}}
                                        @include('backend.blocks_edit.options_bay')

                                        {{-- редактирование role (роль) --}}
                                        @include('backend.blocks_edit.options_role')

                                        {{-- редактирование connector (фидер) --}}
                                        @include('backend.blocks_edit.options_connector')

                                    </div>

                                    {{-- вкладка Другие данные --}}
                                    <div class="tab-pane" id="tabOtherData" role="tabpanel">

                                        {{-- редактирование координат: долготы и широты --}}
                                        @include('backend.blocks_edit.lat_long',[
                                        'myMapObject' => 'IO',
                                        'myFieldName1' => 'address',
                                        'myFieldName2' => 'lat',
                                        'myFieldName3' => 'long',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Eqcid',
                                        'myFieldName' => 'eqcid',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'EntityType',
                                        'myFieldName' => 'entitytype',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Group',
                                        'myFieldName' => 'group',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Кол-во фаз (phaseno)',
                                        'myFieldName' => 'phaseno',
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



