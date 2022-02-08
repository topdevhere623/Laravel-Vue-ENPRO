{{-- редактирование --}}

<form class="form-horizontal" name="form_disconnector" action="{{ route('disconnector.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Disconnector::title1 }}
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('disconnector.index') }}">{{ App\Models\Disconnector::title2 }}</a></li>
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

                                        {{-- выбор из справочника vue-компонент --}}
                                        <options-sprav-component get-sprav='Tower' get-title='Опора' get-field='id' get-current-id='{{ $content->tower_id }}' get-sprav-field-name='id'>
                                        </options-sprav-component>

                                        {{-- выбор из справочника vue-компонент --}}
                                        <options-sprav-component get-sprav='DisconnectorInfo' get-title='Разъединитель' get-field='disconnectorinfo_id' get-current-id='{{ $content->disconnectorinfo_id }}' get-sprav-field-name='ASSETINFOKEY'>
                                        </options-sprav-component>

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



