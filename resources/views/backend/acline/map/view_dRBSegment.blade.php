{{-- данные выбранного сегмента --}}

<div id="dRBSegment" class="dRB">

    {{-- заголовок --}}
    <h3>
        Сегмент
        <i class="md-close" style="float: right;" onclick="funRBview('acline')"></i>
    </h3>

    {{-- кнопка применить --}}
    @if ($userHasEditRights === 1)
    <div class="form-field">
        <div class="row">
            <div class="col col-6">
                <div class="form-field">
                    <input type="button" class="button" id="bApplySegment" value="Применить" onclick="funRBapplySegment(); //funHistoreSave();">
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="form-field">
        ID: <span id="sRBSegmentMapID"> </span>
    </div>

    <div class="form-field">
        <span id="tRBSegmentKolSpan"> </span>: <span id="sRBSegmentKolSpan"> </span>
    </div>

    <div class="form-field" id="trRBSegmentSpanLength">
        <span id="tRBSegmentSpanLength"> </span>: <span id="sRBSegmentSpanLength"> </span> м.
    </div>

    <div class="form-field">
        <select class="form-control selectric" id="sRBSegmentWireMark" onchange="funChangeWireMarkS('segment')">
            <option value="no" selected>не указано</option>
            @if(count($aclinesegmentinfos) > 0)
                @foreach ($aclinesegmentinfos as $item)
                    <option value="{{ $item->id }}">{{ $item->assetinfokey }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label"><span id="tRBSegmentWireMark"> </span></div>
    </div>

    <div class="form-field" id="trRBSegmentWireS">
        <input type="text" class="text-field" id="iRBSegmentWireS" value="" placeholder="">
        <div class="form-input-label">сечение</div>
    </div>

    <div class="form-field" id="trRBSegmentWireN">
        <input type="text" class="text-field" id="iRBSegmentWireN" value="" placeholder="">
        <div class="form-input-label">число проводов</div>
    </div>

    <div class="form-field" id="trRBSegmentWireLength">
        <input type="text" class="text-field" id="iRBSegmentWireLength" value="" placeholder="">
        <div class="form-input-label">длина провода</div>
    </div>

    <div class="form-field" id="trRBSegmentWirePhaseN">
        <input type="text" class="text-field" id="iRBSegmentWirePhaseN" value="" placeholder="">
        <div class="form-input-label">проводов в фазе</div>
    </div>

    <div class="form-field" id="trRBSegmentLayingCondition">
        <select class="form-control selectric" id="sRBSegmentLayingCondition">
            <option value="no" selected>не указано</option>
            @if(count($layingconditionkinds) > 0)
                @foreach ($layingconditionkinds as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">условие прокладки</div>
    </div>

    <div class="form-field" id="trRBSegmentCabelsN">
        <input type="text" class="text-field" id="iRBSegmentCabelsN" value="" placeholder="">
        <div class="form-input-label">кабелей в траншее</div>
    </div>
</div>
