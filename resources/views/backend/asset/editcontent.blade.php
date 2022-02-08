{{-- редактирование --}}

<form class="form-horizontal" name="form_asset" action="{{ route('asset.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Asset::title1 }}
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('asset.index') }}">{{ App\Models\Asset::title2 }}</a></li>
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
                                        'myFieldTitle' => 'Keylink',
                                        'myFieldName' => 'keylink',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Corporatecode',
                                        'myFieldName' => 'corporatecode',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Серийный номер (serialnumber)',
                                        'myFieldName' => 'serialnumber',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Инвентарный номер (inventorynumber)',
                                        'myFieldName' => 'inventorynumber',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Orgmanagerkey',
                                        'myFieldName' => 'orgmanagerkey',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Fgc_parentkey',
                                        'myFieldName' => 'fgc_parentkey',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Orgassetownerkey',
                                        'myFieldName' => 'orgassetownerkey',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Тип (type)',
                                        'myFieldName' => 'type',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Assetinfokey',
                                        'myFieldName' => 'assetinfokey',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Manufactureddt',
                                        'myFieldName' => 'manufactureddt',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Assetcol',
                                        'myFieldName' => 'assetcol',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Комментарий (comment)',
                                        'myFieldName' => 'comment',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Кадастровый номер (cadastralnumber)',
                                        'myFieldName' => 'cadastralnumber',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Производитель (manufacturer)',
                                        'myFieldName' => 'manufacturer',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Inventorynumbermp',
                                        'myFieldName' => 'inventorynumbermp',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Inventorynumberbp',
                                        'myFieldName' => 'inventorynumberbp',
                                        ])

                                    </div>

                                    {{-- вкладка Дополнительные параметры --}}
                                    <div class="tab-pane" id="tabParameters" role="tabpanel">

                                        {{-- редактирование gost (гост) --}}
                                        @include('backend.blocks_edit.options_gost')

                                        {{-- редактирование manufacturer (производители) --}}
                                        @include('backend.blocks_edit.options_manufacturer')

                                        {{-- редактирование дататайм --}}
                                        @include('backend.blocks_edit.datetime',[
                                        'myFieldTitle' => 'Дата установки (installationdate)',
                                        'myFieldName' => 'installationdate',
                                        ])

                                        {{-- редактирование дататайм --}}
                                        @include('backend.blocks_edit.datetime',[
                                        'myFieldTitle' => 'Дата производства (manufactureddate)',
                                        'myFieldName' => 'manufactureddate',
                                        ])

                                        {{-- редактирование дататайм --}}
                                        @include('backend.blocks_edit.datetime',[
                                        'myFieldTitle' => 'Дата покупки (purchasedate)',
                                        'myFieldName' => 'purchasedate',
                                        ])

                                        {{-- редактирование дататайм --}}
                                        @include('backend.blocks_edit.datetime',[
                                        'myFieldTitle' => 'Дата получения (receiveddate)',
                                        'myFieldName' => 'receiveddate',
                                        ])

                                        {{-- редактирование дататайм --}}
                                        @include('backend.blocks_edit.datetime',[
                                        'myFieldTitle' => 'Дата списания (retireddate)',
                                        'myFieldName' => 'retireddate',
                                        ])

                                        {{-- редактирование дататайм --}}
                                        @include('backend.blocks_edit.datetime',[
                                        'myFieldTitle' => 'Дата доставки (deliverydate)',
                                        'myFieldName' => 'deliverydate',
                                        ])

                                    </div>

                                    {{-- вкладка Другие данные --}}
                                    <div class="tab-pane" id="tabOtherData" role="tabpanel">

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Initiallossoflife',
                                        'myFieldName' => 'initiallossoflife',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Initialcondition',
                                        'myFieldName' => 'initialcondition',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Цена покупки (purchaseprice)',
                                        'myFieldName' => 'purchaseprice',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Ownereqassetid',
                                        'myFieldName' => 'ownereqassetid',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Critical',
                                        'myFieldName' => 'critical',
                                        ])

                                        {{-- редактирование цифрового значения --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Склад (warehouse)',
                                        'myFieldName' => 'warehouse',
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



