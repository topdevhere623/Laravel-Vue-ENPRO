{{-- выбор изображения из таблицы file и сохранение в pivot --}}
{{-- плагин dropify --}}

{{-- выывести весь имеющихся файлов --}}
<div class="row">
    @foreach ($files as $key => $item)
        <div class="form-group col-md-1 text-center">
            <a href="{{ $item->getImage('hd','src') }}" target="_blank" title="Открыть в отдельном окне">
                <img src="/public/{{ $item->getImage('thumb','src') }}" class="img-vibor">
            </a>

            <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" id="img_check" name="pivot_img_{{ $item->id }}" value="{{ $item->id }}"
                       @if (in_array($item->id, $content->file->pluck('id')->toArray()))
                       checked
                        @endif
                >
                <label for="img_check">{{ $item->title }}</label>
            </div>

        </div>
    @endforeach
</div>

