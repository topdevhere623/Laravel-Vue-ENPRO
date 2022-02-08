{{-- редактирование picturetype (типа изображения) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Тип изображения (picturetype)</h4>
        <select class="form-control" name="picturetype_id">

            {{-- текущее значение --}}
            @if ($content->picturetype_id)
                <option value="{{ $content->picturetype->id }}" selected disabled="disabled">
                    {{ $content->picturetype->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($pictureTypes) > 0)
                {{-- справочник не пустой --}}

                @foreach($pictureTypes as $pictureType)
                    <option value="{{ $pictureType->id }}">
                        {{ $pictureType->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>