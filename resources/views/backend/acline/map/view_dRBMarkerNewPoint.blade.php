{{-- создание нового обьекта --}}

<div id="dRBMarkerNewPoint" class="dRB">

    {{-- заголовок --}}
    <h3>
        Добавить объект
        <i class="md-close" style="float: right;" onclick="funRBview('acline')"></i>
    </h3>

    {{-- кнопка добавить --}}
    <div class="form-field">
        <input type="button" class="button" value="Добавить" onclick="funNewObjectAdd(); funHistoreSave();">
    </div>

    {{-- тип --}}
    <div class="form-field">
        <select class="form-control selectric" id="sRBNewPlacemarkType">
            <option value="tower" selected>Опора</option>
            <option value="substation">ТП</option>
            <option value="customer">Потребитель</option>
        </select>
        <div class="form-input-label">Тип</div>
    </div>

    <div class="form-field">
        <select class="form-control selectric" id="sRBNewPolylineType">
            <option value="701" selected>Воздушной</option>
            <option value="702">Кабельной</option>
        </select>
        <div class="form-input-label">Соединить линией</div>
    </div>

    <div class="form-field coords">
        <div>
            Широта: <span id="sRBMarkerNewPointLat"> </span>
        </div>
        <div>
            Долгота: <span id="sRBMarkerNewPointLong"> </span>
        </div>
    </div>
</div>
