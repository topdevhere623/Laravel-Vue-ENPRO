{{-- данные ТП --}}

<div id="substationMainForm" class="dRB">

    {{-- заголовок и кнопка применить --}}
    <h3>{{ $substation->identifiedobject->name }}</h3>
    <div class="form-field">

    </div>
    <div class="form-field">
        <div id="substaion_quipments">

        </div>
    </div>
    {{-- прокручивая область --}}

        <div class="form-field">
            <input type="text" class="text-field" id="substationName"
                   @if ($substation->identifiedobject->name)
                   value="{{ $substation->identifiedobject->name }}"
                   @else
                   value="Новая ТП"
                   @endif
                   placeholder="наименование" onChange="$('#tAclineName').text((this.value).slice(0,30));">
            <div class="form-input-label">Дисп. наименование</div>
        </div>


</div>
