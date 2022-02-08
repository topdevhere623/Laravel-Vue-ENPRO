{{-- редактирование --}}

<form class="form-horizontal" name="form_connector" id="form_connector" @if (!isset($thisModal))
    action="{{ route('connector.update', ['id' => $content->id]) }}"
    @endif
    method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Connector::title1 }} -
            @if (isset($content->id))
                {{ $content->id }}
            @else
                Новый
            @endif
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('connector.index') }}">{{ App\Models\Connector::title2 }}</a>
            </li>
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
                                        <a class="nav-link active" data-toggle="tab" href="#tabConnectorIO"
                                            aria-controls="tabConnectorIO" role="tab" aria-selected="false">
                                            Общие технические данные IO
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabConnectorMain"
                                            aria-controls="tabConnectorMain" role="tab" aria-selected="true">
                                            Фидер
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabConnectorRelated"
                                            aria-controls="tabConnectorRelated" role="tab" aria-selected="false">
                                            Конечные точки
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-toggle="tab" href="#tabConnectorImages"
                                            aria-controls="tabConnectorImages" role="tab" aria-selected="false">
                                            Схема
                                        </a>
                                    </li>

                                </ul>
                                <div class="tab-content pt-20">

                                    {{-- вкладка Общие технические данные IO
                                    --}}
                                    <div class="tab-pane active" id="tabConnectorIO" role="tabpanel">

                                        {{-- редактирование данных IO в дочернем обьекте
                                        --}}
                                        @include('backend.blocks_edit.io', [
                                        'myMapObject' => 'Connector'
                                        ])

                                    </div>

                                    {{-- вкладка Основное --}}
                                    <div class="tab-pane" id="tabConnectorMain" role="tabpanel">

                                        <div class="row">
                                            <div class="col">

                                                {{-- редактирование названия
                                                --}}
                                                @include('backend.blocks_edit.name',[
                                                'myFieldTitle' => 'Keylink',
                                                'myFieldName' => 'keylink',
                                                ])

                                            </div>

                                            <div class="col">

                                                {{-- редактирование asset (общие данные)
                                                --}}
                                                @include('backend.blocks_edit.options_asset')

                                            </div>
                                        </div>

                                        {{-- редактирование цифрового значения
                                        --}}
                                        @include('backend.blocks_edit.number',[
                                        'myFieldName' => 'connected',
                                        'myFieldTitle' => 'Соединено (connected)',
                                        ])

                                    </div>

                                    {{-- вкладка Связанные данные
                                    --}}
                                    <div class="tab-pane" id="tabConnectorRelated" role="tabpanel">

                                        <p>
                                            <i>Примечание: Наименование обьектов и справочников - активные ссылки, по
                                                которым Вы можете перейти в отдельное окно для редактирования.</i>
                                        </p>

                                        {{-- связанные данные - конечные точки
                                        --}}
                                        @include('backend.blocks_edit.related_endpoint',[
                                        'myRelatedEndpoint' => $content->endpoint,
                                        ])

                                    </div>

                                    {{-- вкладка Изображения и прикрепленные файлы
                                    --}}
                                    <div class="tab-pane" id="tabConnectorImages" role="tabpanel">

                                        {{-- редактирование поля изображение (из таблицы
                                        file) --}}
                                        @include('backend.blocks_edit.img',[
                                        'myFieldTitle' => 'Изображение',
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
