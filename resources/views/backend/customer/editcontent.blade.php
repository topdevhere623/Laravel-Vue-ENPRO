{{-- редактирование --}}

<form class="form-horizontal" name="form_customer" action="{{ route('customer.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Customer::title1 }}
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">{{ App\Models\Customer::title2 }}</a></li>
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

                                    <li class="nav-item active" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabMain" aria-controls="tabMain" role="tab" aria-selected="true">
                                            Потребитель
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabIO" aria-controls="tabIO" role="tab" aria-selected="false">
                                            Общие технические данные IO
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabMain" role="tabpanel">

                                        <div class="row">
                                            <div class="col">

                                                {{-- выбор из справочника vue-компонент --}}
                                                <options-sprav-component get-sprav='Acline' get-title='ЛЭП' get-field='_io_acline_id' get-current-id='{{ ($content->acline) ? $content->acline->id : '' }}'>
                                                </options-sprav-component>

                                            </div>

                                            <div class="col">
                                            </div>
                                        </div>

                                    </div>

                                    {{-- вкладка Общие технические данные IO --}}
                                    <div class="tab-pane" id="tabIO" role="tabpanel">

                                        {{-- редактирование данных IO в дочернем обьекте --}}
                                        @include('backend.blocks_edit.io', [
                                        'myMapObject' => 'Customer'
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



