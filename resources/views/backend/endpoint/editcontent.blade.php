{{-- редактирование --}}

<form class="form-horizontal" name="form_endpoint" id="form_endpoint"
      @if (!isset($thisModal))
      action="{{ route('endpoint.update', ['id'=>$content->id]) }}"
      @endif
      method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Endpoint::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новая
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('endpoint.index') }}">{{ App\Models\Endpoint::title2 }}</a></li>
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
                                        <a class="nav-link active" data-toggle="tab" href="#tabEndpointConnector" aria-controls="tabEndpointConnector" role="tab" aria-selected="true">
                                            Конечная точка
                                        </a>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabEndpointIO" aria-controls="tabEndpointIO" role="tab" aria-selected="false">
                                            Общие технические данные IO
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane active" id="tabEndpointConnector" role="tabpanel">

                                        <div class="row">
                                            <div class="col">

                                                {{-- выбор из справочника vue-компонент --}}
                                                <options-sprav-component get-sprav='Acline' get-title='ЛЭП' get-field='_io_acline_id' get-current-id='{{ ($content->acline) ? $content->acline->id : '' }}'>
                                                </options-sprav-component>

                                                {{-- редактирование connector (фидер) --}}
                                                @include('backend.blocks_edit.options_connector')

                                            </div>

                                            <div class="col">
                                            </div>
                                        </div>

                                        {{-- редактирование статуса --}}
                                        @include('backend.blocks_edit.status')

                                    </div>

                                    {{-- вкладка Общие технические данные IO --}}
                                    <div class="tab-pane" id="tabEndpointIO" role="tabpanel">

                                        {{-- редактирование данных IO в дочернем обьекте --}}
                                        @include('backend.blocks_edit.io', [
                                        'myMapObject' => 'Endpoint'
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



