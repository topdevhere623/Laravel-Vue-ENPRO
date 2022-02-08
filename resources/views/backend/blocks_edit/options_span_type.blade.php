{{-- редактирование типа линии 701, 702 --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Тип</h4>
        <select class="form-control" name="spantype">

            <option value="701" @if ($content->spantype == 701) selected @endif>воздушный</option>
            <option value="702" @if ($content->spantype == 702) selected @endif>кабельный</option>
            <option value="" @if ($content->spantype == null) selected @endif>не определено</option>

        </select>
    </div>
</div>