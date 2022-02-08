{{-- данные ЛЭП --}}

<div id="dRBAcline" class="dRB">

    {{-- заголовок и кнопка применить --}}
    <h3>ЛЭП</h3>

    <div class="form-field">
        ID: <span id="sRBAclineID">
                {{ ($acline->id) ? $acline->id : ''}}
            </span>
    </div>

    <div class="form-field">
        <input type="text" class="text-field" id="iRBAclineName"
               @if ($acline->identifiedobject->name)
               value="{{ $acline->identifiedobject->name }}"
               @else
               value="{{ $myAclineNameDefault }}"
               @endif
               placeholder="наименование" onChange="$('#tAclineName').text((this.value).slice(0,30));">
        <div class="form-input-label">Наименование</div>
    </div>

    <div class="form-field">
        <select class="form-control selectric" id="sRBAclineBaseVoltage" onchange="funCurrentBaseVoltage()">
            @if(count($basevoltages) > 0)
                @foreach ($basevoltages as $item)
                    <option value="{{ $item->id }}"
                            @if (isset($item->id) and $item->id == $acline->identifiedobject->voltage_id)
                            selected
                        @endif
                    >
                        {{ $item->name }}
                    </option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Класс напряжения</div>
    </div>

    <div class="form-field">
        <select class="form-control selectric" id="sRBAclineStatus">
            @if(count($aclinestatus) > 0)
                @foreach ($aclinestatus as $item)
                    <option value="{{ $item->id }}"
                            @if (isset($item->id) and isset($acline->status_id) and $item->id == $acline->status_id)
                            selected
                        @endif
                    >
                        {{ $item->name }}
                    </option>
                @endforeach
            @endif
        </select>
        <div class="form-input-label">Статус</div>
    </div>

</div>
