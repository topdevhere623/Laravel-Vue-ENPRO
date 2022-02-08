{{-- редактирование --}}

<form class="form-horizontal" name="form_classname" action="{{ route('classname.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Classname::title1 }}
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('classname.index') }}">{{ App\Models\Classname::title2 }}</a></li>
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

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabsMain" role="tabpanel">

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Classname',
                                        'myFieldName' => 'classname',
                                        'myRequired' => 1,
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Name (наименование)',
                                        'myFieldName' => 'name',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Keylink',
                                        'myFieldName' => 'keylink',
                                        ])

                                        {{-- редактирование названия  --}}
                                        @include('backend.blocks_edit.name',[
                                        'myFieldTitle' => 'Marktypekey',
                                        'myFieldName' => 'marktypekey',
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



