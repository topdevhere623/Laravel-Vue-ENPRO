{{-- кнопка паспорт и импортировать --}}

{{-- паспорт --}}
<div class="dRBPassport">

    <div class="form-field">
        <div class="row">
            <div class="col col-6">
                Сохранить данные карты <br>и сгенерировать паспорт линии
            </div>

            <div class="col col-6">
                <div class="button bordered" onclick="funGetPassport()">Паспорт</div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="dRBImport file-group">
    @if ($userHasEditRights === 1)
    <div class="form-field">
        <div class="row">
            <div class="col col-6">
                Импорт из файла <br>(KML, GPX, XSDE или TRACK)
            </div>
            <div class="col col-6">

                <label>
                    <div class="button bordered">Импортировать</div>
                    <input type="file" id="importUpload">
                </label>
            </div>
        </div>
    </div>
    {{-- переключатели - выбор способа импорта --}}
    <div class="form-field check-inline">
        <label class="radio">
            <input type="radio" id="importRegimTowers" name="importRegim" value="towers" checked="">
            <span class="box"></span>
            <span>Опоры</span>
        </label>
        <label class="radio">
            <input type="radio" id="importRegimPoints" name="importRegim" value="points">
            <span class="box"></span>
            <span>Характерные точки</span>
        </label>
    </div>
    @endif
</div>
