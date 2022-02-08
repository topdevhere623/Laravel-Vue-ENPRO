{{-- данные выбранной точки - Опоры, ТП или Потребителя --}}

<div id="dRBPlacemark" class="dRB">

    {{-- заголовок --}}
    <h3>
        <span id="h3RBPlacemarkType"> </span>
        <i class="md-close" style="float: right;" onclick="funRBview('acline')"></i>
    </h3>

    {{-- скрытое поле --}}
    <input type="hidden" id="hRBPlacemarkMapID">

    {{-- кнопка применить --}}
    @if ($userHasEditRights === 1)
        <div class="form-field">
            <div class="row">
                <div class="col col-6">
                    <div class="form-field">
                        <input class="button" type="button" id="bApplyPlacemark" value="Применить" onclick="funRBapplyPlacemark(); funHistoreSave();">
                    </div>
                </div>
                <div class="col col-6">
                    <div class="form-field">
                        <input class="button bordered" type="button" value="Удалить" onclick="funPlacemarkDelete(); funHistoreSave();">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="form-field" id="trRBPlacemarkName">
        <input type="text" class="text-field" id="iRBPlacemarkName" value="" placeholder="">
        <div class="form-input-label">Наименование</div>
    </div>

    <div class="form-field" id="trRBPlacemarkLocalName">
        <input type="text" class="text-field" id="iRBPlacemarkLocalName" value="" placeholder="">
        <div class="form-input-label">Диспетчерский номер</div>
    </div>

    <div class="form-field" id="trRBPlacemarkAddress">
        <div class="input-group-icon input-group">
            <input type="text" class="text-field" id="iRBPlacemarkAddress" value="" placeholder="">
            <span class="input-group-addon">
                    <span class="icon wb-map" aria-hidden="true" onclick="funGetAddress(mmCurrentPlacemarkMapID,'iRBPlacemarkAddress')"></span>
                </span>
            <div class="form-input-label">Адрес</div>
        </div>
    </div>

    <div class="form-field" id="trRBPlacemarkType">
        <select class="form-control selectric" id="sRBPlacemarkType">
            <option value="tower" selected>Опора</option>
            <option value="customer">Потребитель</option>
            <option value="substation">ТП</option>
        </select>
        <div class="form-input-label">Тип</div>
    </div>

    {{-------------------------------------------------------------------}}
    {{-- связка --}}
    {{-- ТП --}}
    <div class="form-field" id="trRBPlacemarkSubstation">
        <select class="form-control selectric" id="sRBPlacemarkSubstation" onchange="funChangeSubstation();">
            <option value="no" selected>не указано</option>
            @if(count($substations) > 0)
                @foreach ($substations as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">ТП (реальное близ находящееся)</div>
    </div>

    {{-- секция шин --}}
    <div class="form-field" id="trRBPlacemarkBusbarsection">
        <select class="form-control selectric" id="sRBPlacemarkBusbarsection" onchange="funChangeBusbarsection()">
            <option value="no" selected>не указано</option>
            @if(count($busbarsection) > 0)
                @foreach ($busbarsection as $item)
                    <option value="{{ $item->id }}">{{ $item->identifiedobject->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Секция шин</div>
    </div>

    {{-- терминал --}}
    <div class="form-field" id="trRBPlacemarkTerminal">
        <select class="form-control selectric" id="sRBPlacemarkTerminal">
            <option value="no" selected>не указано</option>
            @if(count($terminals) > 0)
                @foreach ($terminals as $item)
                    <option value="{{ $item->id }}">{{ $item->identifiedobject->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Терминал</div>
    </div>

    {{-------------------------------------------------------------------}}
    {{-- марка опоры --}}
    <div class="form-field" id="trRBPlacemarkTowerInfo">
        <select class="form-control selectric" id="sRBPlacemarkTowerInfo" onchange="funChangeTowerInfo()">
            <option value="no" selected>не указано</option>
            @if(count($towerinfos) > 0)
                @foreach ($towerinfos as $item)
                    <option value="{{ $item->id }}">{{ $item->mark }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Марка</div>
    </div>

    {{-- материал опоры --}}
    <div class="form-field" id="trRBPlacemarkTowerMaterial">
        <select class="form-control selectric" id="sRBPlacemarkTowerMaterial">
            <option value="no" selected>не указано</option>
            @if(count($towermaterials) > 0)
                @foreach ($towermaterials as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Материал</div>
    </div>

    {{-- назначение опоры --}}
    <div class="form-field" id="trRBPlacemarkTowerKind">
        <select class="form-control selectric" id="sRBPlacemarkTowerKind">
            <option value="no" selected>не указано</option>
            @if(count($towerkinds) > 0)
                @foreach ($towerkinds as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Назначение</div>
    </div>

    {{-- конструкция опоры --}}
    <div class="form-field" id="trRBPlacemarkTowerConstruction">
        <select class="form-control selectric" id="sRBPlacemarkTowerConstruction">
            <option value="no" selected>не указано</option>
            @if(count($towerconstructionkinds) > 0)
                @foreach ($towerconstructionkinds as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Конструкция</div>
    </div>

    {{-- кол-во стоек --}}
    <div class="form-field" id="trRBPlacemarkPropN">
        <select class="form-control selectric" id="sRBPlacemarkPropN">
            <option value="no" selected>не указано</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        <div class="form-input-label">Кол-во стоек</div>
    </div>

    {{-- оттяжка --}}
    <div class="form-field" id="trRBPlacemarkGuy">
        <select class="form-control selectric" id="sRBPlacemarkGuy">
            <option value="no" selected>нет</option>
            <option value="left">слева</option>
            <option value="right">справа</option>
        </select>
        <div class="form-input-label">Оттяжка</div>
    </div>

    {{-- подкос --}}
    <div class="form-field" id="trRBPlacemarkStrut">
        <div class="row">
            <div class="col col-5">
                <div class="form-field">
                    <select class="form-control selectric" id="sRBPlacemarkStrut">
                        <option value="no" selected>нет</option>
                        <option value="concrete">железобетон</option>
                        <option value="wood">дерево</option>
                        <option value="metal">металл</option>
                    </select>
                    <div class="form-input-label">Подкос</div>
                </div>
            </div>
            <div class="col col-7">
                <div class="form-field">
                    <select class="form-control selectric" id="sRBPlacemarkStrutN">
                        <option value="no" selected>не указано</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                    <div class="form-input-label">Количество</div>
                </div>
            </div>
        </div>
    </div>

    {{-- приставка --}}
    <div class="form-field" id="trRBPlacemarkAnnex">
        <select class="form-control selectric" id="sRBPlacemarkAnnex">
            <option value="no" selected>нет</option>
            <option value="metal">металл</option>
            <option value="concrete">железобетон</option>
        </select>
        <div class="form-input-label">Приставка</div>
    </div>

    {{--обрудование грозозащиты--}}
    <div class="form-field input-group" id="trRBPlacemarkEqDischarge">
        <div class="multiselect">
            <div class="selectBox js-show-checkboxes2">
                <select class="form-control selectric">
                    <option>Оборудование грозозащиты</option>
                </select>
                <div class="overSelect"></div>
            </div>
            <div id="checkboxes2">

                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqDischarger" style="margin-right:5px;"/>
                    <span class="box"></span>
                    разрядник

                    <select class="form-control selectric" id="sRBPlacemarkEqDischargerInfo">
                        <option value="no" selected>не указано</option>
                        @if(count($dischargerinfos) > 0)
                            @foreach ($dischargerinfos as $item)
                                <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                            @endforeach
                        @endif
                    </select>

                </label>
                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqOPN" style="margin-right:5px;"/>
                    <span class="box"></span>
                    ОПН

                    <select class="form-control selectric" id="sRBPlacemarkEqOPNInfo">
                        <option value="no" selected>не указано</option>
                        @if(count($dischargerinfos) > 0)
                            @foreach ($dischargerinfos as $item)
                                <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                            @endforeach
                        @endif
                    </select>

                </label>
                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqGrounding" style="margin-right:5px;"/>
                    <span class="box"></span>
                    заземление
                </label>

            </div>
        </div>
    </div>

    {{--обрудование прочее--}}
    <div class="form-field input-group" id="trRBPlacemarkEqOther">
        <div class="multiselect">
            <div class="selectBox js-show-checkboxes3">
                <select class="form-control selectric">
                    <option>Оборудование прочее</option>
                </select>
                <div class="overSelect"></div>
            </div>
            <div id="checkboxes3">

                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqOtherLine" style="margin-right:5px;"/>
                    <span class="box"></span>
                    линии разных классов напряжения
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqCommLine" style="margin-right:5px;"/>
                    <span class="box"></span>
                    линии связи
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqLamp" style="margin-right:5px;"/>
                    <span class="box"></span>
                    фонарь
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqAdapter" style="margin-right:5px;"/>
                    <span class="box"></span>
                    адаптер
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqAccident" style="margin-right:5px;"/>
                    <span class="box"></span>
                    аварийная
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="chRBPlacemarkEqNoUp" style="margin-right:5px;"/>
                    <span class="box"></span>
                    подьем запрещен
                </label>

            </div>
        </div>
    </div>

    <div class="form-field" id="trRBPlacemarkLat">
        <input type="text" class="text-field" id="iRBPlacemarkLat" value="" placeholder="">
        <div class="form-input-label">Широта</div>
    </div>

    <div class="form-field" id="trRBPlacemarkLong">
        <input type="text" class="text-field" id="iRBPlacemarkLong" value="" placeholder="">
        <div class="form-input-label">Долгота</div>
    </div>

    <hr>

    <div class="form-field">
        {{-- место для вывода текущих фото точек --}}
        <div id="dPlacemarkPhotos"></div>
        {{-- фото обьектов карты vue-компонент --}}
        <map-file-upload-component>
        </map-file-upload-component>
    </div>

</div>
