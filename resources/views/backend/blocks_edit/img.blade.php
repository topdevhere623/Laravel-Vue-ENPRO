{{-- редактирование поля изображение --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Изображение</h4>

        {{-- скрытое поле с текущим значеним изображения --}}
        <input type="hidden" name="{{ $myFieldName }}" value="{{ $content->$myFieldName }}">

        {{-- плагин dropify --}}
        <div class="example">
            <input type="file" id="input-file-max-fs" name="img" data-plugin="dropify" data-max-file-size="2M"
                data-default-file="/public/{{ $content->getImage('thumb', $myFieldName) }}"
                accept=".jpg, .jpeg, .png .svg .SVG" />
        </div>

        <span class="badge badge-round badge-warning">Рекомендуемый размер</span>
        <small>
            <3 Mb </small>

    </div>
</div>
