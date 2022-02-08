{{-- данные выбранного пролета --}}

<div id="dRBPolyline" class="dRB">

    {{-- заголовок --}}
    <h3>
        <span id="h3RBPolylineType"> </span>
        <i class="md-close" style="float: right;" onclick="funRBview('acline')"></i>
    </h3>

    {{-- скрытое поле --}}
    <input type="hidden" id="hRBPolylineMapID">

    {{-- кнопка применить --}}
    @if ($userHasEditRights === 1)
        <div class="form-field">
            <div class="row">
                <div class="col col-6">
                    <div class="form-field">
                        <input type="button" class="button w-200" id="bApplyPolyline" value="Применить" onclick="funRBapplyPolyline(); funHistoreSave();">
                    </div>
                </div>
                <div class="col col-6">
                    <div class="form-field">
                        <input type="button" class="button bordered w-200" value="Удалить" onclick="funPolylineDelete(); funHistoreSave();">
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- длина пролета/участка --}}
    <div class="form-field" id="trRBPolylineSpanLength">
        <span id="tRBPolylineSpanLength"> </span>: <span id="sRBPolylineSpanLength"> </span> м.
    </div>

    {{-- тип --}}
    <div class="form-field" id="trRBPolylineWireMark">
        <select class="form-control selectric" id="sRBPolylineType" onchange="funChangeWireMark('polyline', $(this).val())">
            <option value="701" selected>Воздушная</option>
            <option value="702">Кабельная</option>
            <option value="0">нет</option>
        </select>
        <div class="form-input-label">тип</div>
    </div>

    {{-- марка провода --}}
    <div class="form-field">
        <select class="form-control selectric" id="sRBPolylineWireMark" onchange="funChangeWireMarkS('polyline')">
            <option value="no" selected>не указано</option>
            @if(count($aclinesegmentinfos) > 0)
                @foreach ($aclinesegmentinfos as $item)
                    <option value="{{ $item->id }}">{{ $item->assetinfokey }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label"><span id="tRBPolylineWireMark"> </span></div>
    </div>

    {{-- сечение --}}
    <div class="form-field" id="trRBPolylineWireS">
        <input type="text" class="text-field" id="iRBPolylineWireS" value="" placeholder="">
        <div class="form-input-label">сечение</div>
    </div>

    {{-- обрудование коммутационное --}}
    <div class="form-field input-group" id="trRBPolylineEqDisconnector">
        <div class="multiselect">
            <div class="selectBox js-show-checkboxes1">
                <select class="form-control selectric">
                    <option>Оборудование коммутационное</option>
                </select>
                <div class="overSelect"></div>
            </div>

            <div id="checkboxes1">

                {{-------------------------------------------------------}}
                разъединитель:
                <div class="row">
                    <div class="col-5">

                        <label class="checkbox" style="margin-top: 22px;">
                            <input type="checkbox" id="chRBPolylineEqDisconnectorStart" style="margin-right:5px;"/>
                            <span class="box"></span>
                            <span id="lRBPolylineEqDisconnectorStart"> </span>
                        </label>

                    </div>
                    <div class="col-7">

                        {{-- справочник --}}
                        <select class="form-control selectric" id="sRBPolylineEqDisconnectorStartInfo">
                            <option value="no" selected>не указано</option>
                            @if(count($disconnectorinfos) > 0)
                                @foreach ($disconnectorinfos as $item)
                                    <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                <div class="row">
                    <div class="col-5">

                        <label class="checkbox" style="margin-top: 22px;">
                            <input type="checkbox" id="chRBPolylineEqDisconnectorEnd" style="margin-right:5px;"/>
                            <span class="box"></span>
                            <span id="lRBPolylineEqDisconnectorEnd"> </span>
                        </label>

                    </div>
                    <div class="col-7">

                        {{-- справочник --}}
                        <select class="form-control selectric" id="sRBPolylineEqDisconnectorEndInfo">
                            <option value="no" selected>не указано</option>
                            @if(count($disconnectorinfos) > 0)
                                @foreach ($disconnectorinfos as $item)
                                    <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                {{-------------------------------------------------------}}
                реклоузер:
                <div class="row">
                    <div class="col-5">

                        <label class="checkbox" style="margin-top: 22px;">
                            <input type="checkbox" id="chRBPolylineEqReklouzerStart" style="margin-right:5px;"/>
                            <span class="box"></span>
                            <span id="lRBPolylineEqReklouzerStart"> </span>
                        </label>

                    </div>
                    <div class="col-7">

                        {{-- справочник --}}
                        <select class="form-control selectric" id="sRBPolylineEqReklouzerStartInfo">
                            <option value="no" selected>не указано</option>
                            @if(count($disconnectorinfos) > 0)
                                @foreach ($disconnectorinfos as $item)
                                    <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                <div class="row">
                    <div class="col-5">

                        <label class="checkbox" style="margin-top: 22px;">
                            <input type="checkbox" id="chRBPolylineEqReklouzerEnd" style="margin-right:5px;"/>
                            <span class="box"></span>
                            <span id="lRBPolylineEqReklouzerEnd"> </span>
                        </label>

                    </div>
                    <div class="col-7">

                        {{-- справочник --}}
                        <select class="form-control selectric" id="sRBPolylineEqReklouzerEndInfo">
                            <option value="no" selected>не указано</option>
                            @if(count($disconnectorinfos) > 0)
                                @foreach ($disconnectorinfos as $item)
                                    <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                {{-------------------------------------------------------}}
                выключатель нагрузки:
                <div class="row">
                    <div class="col-5">

                        <label class="checkbox" style="margin-top: 22px;">
                            <input type="checkbox" id="chRBPolylineEqVNaStart" style="margin-right:5px;"/>
                            <span class="box"></span>
                            <span id="lRBPolylineEqVNaStart"> </span>
                        </label>

                    </div>
                    <div class="col-7">

                        {{-- справочник --}}
                        <select class="form-control selectric" id="sRBPolylineEqVNaStartInfo">
                            <option value="no" selected>не указано</option>
                            @if(count($disconnectorinfos) > 0)
                                @foreach ($disconnectorinfos as $item)
                                    <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                <div class="row">
                    <div class="col-5">

                        <label class="checkbox" style="margin-top: 22px;">
                            <input type="checkbox" id="chRBPolylineEqVNaEnd" style="margin-right:5px;"/>
                            <span class="box"></span>
                            <span id="lRBPolylineEqVNaEnd"> </span>
                        </label>

                    </div>
                    <div class="col-7">

                        {{-- справочник --}}
                        <select class="form-control selectric" id="sRBPolylineEqVNaEndInfo">
                            <option value="no" selected>не указано</option>
                            @if(count($disconnectorinfos) > 0)
                                @foreach ($disconnectorinfos as $item)
                                    <option value="{{ $item->id }}">{{ $item->ASSETINFOKEY }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- габарит --}}
    <div class="form-field" id="trRBPolylineGabarit">
        <input type="text" class="text-field" id="iRBPolylineGabarit" value="" placeholder="">
        <div class="form-input-label">габарит</div>
    </div>

    {{-- число проводов --}}
    <div class="form-field" id="trRBPolylineWireN">
        <input type="text" class="text-field" id="iRBPolylineWireN" value="" placeholder="">
        <div class="form-input-label">число проводов</div>
    </div>

    {{-- длина провода --}}
    <div class="form-field" id="trRBPolylineWireLength">
        <input type="text" class="text-field" id="iRBPolylineWireLength" value="" placeholder="">
        <div class="form-input-label">длина провода</div>
    </div>

    {{-- проводов в фазе --}}
    <div class="form-field" id="trRBPolylineWirePhaseN">
        <input type="text" class="text-field" id="iRBPolylineWirePhaseN" value="" placeholder="">
        <div class="form-input-label">проводов в фазе</div>
    </div>

    {{-- условие прокладки --}}
    <div class="form-field" id="trRBPolylineLayingCondition">
        <select class="form-control selectric" id="sRBPolylineLayingCondition">
            <option value="no" selected>не указано</option>
            @if(count($layingconditionkinds) > 0)
                @foreach ($layingconditionkinds as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">условие прокладки</div>
    </div>

    {{-- кабелей в траншее --}}
    <div class="form-field" id="trRBPolylineCabelsN">
        <input type="text" class="text-field" id="iRBPolylineCabelsN" value="" placeholder="">
        <div class="form-input-label">кабелей в траншее</div>
    </div>

    {{-- характерные точки --}}
    <div class="form-field" id="trRBPolylineCoord">
        <textarea class="text-field" id="tRBPolylineCoord" style="width: 100%; height:150px; padding-top:30px;" placeholder=""></textarea>
        <div class="form-input-label">характерные точки</div>
    </div>

</div>
