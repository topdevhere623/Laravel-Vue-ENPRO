{{-- редактирование --}}

<form class="form-horizontal" name="form_tower" action="{{ route('tower.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
    {!! method_field('POST') !!}
    {{ @csrf_field() }}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">
            {{ App\Models\Tower::title1 }}
        </h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tower.index') }}">{{ App\Models\Tower::title2 }}</a></li>
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
                                            Опора
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
                                            <div class="col col-6">

                                                {{-- выбор из справочника vue-компонент --}}
                                                <options-sprav-component get-sprav='Towermaterial' get-title='Материал' get-field='towermaterial_id' get-current-id='{{ $content->towermaterial_id }}'>
                                                </options-sprav-component>

                                                {{-- выбор из справочника vue-компонент --}}
                                                <options-sprav-component get-sprav='Towerinfo' get-title='Марка' get-field='towerinfo_id' get-current-id='{{ $content->towerinfo_id }}'>
                                                </options-sprav-component>

                                                {{-- выбор из справочника vue-компонент --}}
                                                <options-sprav-component get-sprav='Towerkind' get-title='Назначение' get-field='towerkind_id' get-current-id='{{ $content->towerkind_id }}'>
                                                </options-sprav-component>

                                                {{-- выбор из справочника vue-компонент --}}
                                                <options-sprav-component get-sprav='Towerconstructionkind' get-title='Конструкция' get-field='towerconstructionkind_id' get-current-id='{{ $content->towerconstructionkind_id }}'>
                                                </options-sprav-component>

                                            </div>

                                            <div class="col-md-6">

                                                {{-- редактирование цифрового значения --}}
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4 class="example-title">Кол-во стоек</h4>
                                                        <select class="form-control" name="propn">
                                                            <option value="no" selected>не указано</option>
                                                            <option value="1" @if ($content->propn == 1) selected @endif>1</option>
                                                            <option value="2" @if ($content->propn == 2) selected @endif>2</option>
                                                            <option value="3" @if ($content->propn == 3) selected @endif>3</option>
                                                            <option value="4" @if ($content->propn == 4) selected @endif>4</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4 class="example-title">Оттяжка</h4>
                                                        <select class="form-control" name="guy">
                                                            <option value="no" @if ($content->guy == 'no') selected @endif>нет</option>
                                                            <option value="left" @if ($content->guy == 'left') selected @endif>слева</option>
                                                            <option value="right" @if ($content->guy == 'right') selected @endif>справа</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4 class="example-title">Подкос</h4>
                                                        <select class="form-control" name="strut">
                                                            <option value="no" @if ($content->strut == 'no') selected @endif>нет</option>
                                                            <option value="ironConcrete" @if ($content->strut == 'ironConcrete') selected @endif>железобетон</option>
                                                            <option value="wood" @if ($content->strut == 'wood') selected @endif>дерево</option>
                                                            <option value="metal" @if ($content->strut == 'metal') selected @endif>металл</option>
                                                        </select>

                                                        <br>
                                                        <select class="form-control" name="strutn">
                                                            <option value="no" selected>не указано</option>
                                                            <option value="1" @if ($content->strutn == 1) selected @endif>1</option>
                                                            <option value="2" @if ($content->strutn == 2) selected @endif>2</option>
                                                            <option value="3" @if ($content->strutn == 3) selected @endif>3</option>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4 class="example-title">Приставка</h4>
                                                        <select class="form-control" name="annex">
                                                            <option value="no" @if ($content->annex == 'no') selected @endif>нет</option>
                                                            <option value="metal" @if ($content->annex == 'metal') selected @endif>металл</option>
                                                            <option value="ironConcrete" @if ($content->annex == 'ironConcrete') selected @endif>железобетон</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <h4 class="example-title">Разъединитель</h4>
                                                        <select class="form-control" name="disconnector">
                                                            <option value="no" @if ($content->disconnector == 'no') selected @endif>нет</option>
                                                            <option value="on" @if ($content->disconnector == 'on') selected @endif>включен</option>
                                                            <option value="off" @if ($content->disconnector == 'off') selected @endif>выключен</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    {{-- вкладка Общие технические данные IO --}}
                                    <div class="tab-pane" id="tabIO" role="tabpanel">

                                        {{-- редактирование данных IO в дочернем обьекте --}}
                                        @include('backend.blocks_edit.io', [
                                        'myMapObject' => 'Tower'
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



