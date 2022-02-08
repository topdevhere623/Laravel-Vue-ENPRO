{{-- редактирование --}}

<form class="form-horizontal" name="form_aclinesegmentinfo" action="{{ route('aclinesegmentinfo.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Aclinesegmentinfo::title1 }}
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('aclinesegmentinfo.index') }}">{{ App\Models\Aclinesegmentinfo::title2 }}</a></li>
            <li class="breadcrumb-item active">{{ $content->id ? 'Редактирование - '.App\Models\Aclinesegmentinfo::title2 : 'Создание - '.App\Models\Aclinesegmentinfo::title2 }}</li>
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
                                        'myFieldTitle' => 'Марка (assetinfokey)',
                                        'myFieldName' => 'assetinfokey', 
                                        'myRequired' => 1,
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Подкласс (subclass)',
                                        'myFieldName' => 'subclass',
                                        ])

                                        {{-- редактирование статуса --}}
                                        @include('backend.blocks_edit.status')

                                    </div>

                                    {{-- вкладка Дополнительные параметры --}}
                                    <div class="tab-pane" id="tabParameters" role="tabpanel">

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Voltageid',
                                        'myFieldName' => 'voltageid',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Unom',
                                        'myFieldName' => 'unom',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'R',
                                        'myFieldName' => 'r',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'X',
                                        'myFieldName' => 'x',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'G',
                                        'myFieldName' => 'g',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'B',
                                        'myFieldName' => 'b',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Сечение (S)',
                                        'myFieldName' => 's',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Idd',
                                        'myFieldName' => 'idd',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Df',
                                        'myFieldName' => 'df',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Dpkor',
                                        'myFieldName' => 'dpkor',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Sf',
                                        'myFieldName' => 'sf',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Nf',
                                        'myFieldName' => 'nf',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'N',
                                        'myFieldName' => 'n',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldTitle' => 'Sst',
                                        'myFieldName' => 'sst',
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



