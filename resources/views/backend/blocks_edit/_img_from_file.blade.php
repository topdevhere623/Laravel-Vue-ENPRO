{{-- редактирование поля изображение (из таблицы file) --}}
{{-- плагин dropify --}}

<div class="row">

    {{-- сохраненные изображения --}}
    @foreach($files as $key => $item)
        <div class="form-group col-md-12">
            <h4 class="example-title">Изображение {{ $key + 1 }}</h4>
            {{-- скрытое поле с текущим значеним изображения --}}
            <input type="hidden" name="img-current_{{ $key }}" id="img-current_{{ $key }}" value="{{ $item->src }}">
            <div class="example">
                <input type="file" id="input-file-max-fs" name="img_{{ $key }}" data-plugin="dropify" data-max-file-size="2M" data-default-file="{{ $item->getImage('thumb') }}" accept=".jpg, .jpeg, .png"/>
                <input type="text" class="form-control" name="img_file_type_{{ $key}}" value="{{ $item->type }}" placeholder="{{ 'тип изображения' }}">
                <input type="text" class="form-control" name="img_file_title_{{ $key}}" value="{{ $item->title }}" placeholder="{{ 'название изображения' }}">
                <input type="text" class="form-control" name="img_file_description_{{ $key}}" value="{{ $item->description }}" placeholder="{{ 'описание изображения' }}">
            </div>

            <span class="badge badge-round badge-warning">Рекомендованный размер</span>
            <small>
                <2 Mb
            </small>
        </div>
    @endforeach

    {{-- новое изображение --}}
    <div class="form-group col-md-12">
        <h4 class="example-title">Изображение новое</h4>
        {{-- скрытое поле с текущим значеним изображения --}}
        <input type="hidden" name="img-current_1" id="img-current1" value="">
        <div class="example">
            <input type="file" id="input-file-max-fs" name="img_1" data-plugin="dropify" data-max-file-size="2M" data-default-file="/public/uploads/default/default_thumb.png" accept=".jpg, .jpeg, .png"/>
            <input type="text" class="form-control" name="img_file_type_1" value="" placeholder="{{ 'тип изображения' }}">
            <input type="text" class="form-control" name="img_file_title_1" value="" placeholder="{{ 'название изображения' }}">
            <input type="text" class="form-control" name="img_file_description_1" value="" placeholder="{{ 'описание изображения' }}">
        </div>

        <span class="badge badge-round badge-warning">Рекомендованный размер</span>
        <small>
            <2 Mb
        </small>
    </div>
</div>


