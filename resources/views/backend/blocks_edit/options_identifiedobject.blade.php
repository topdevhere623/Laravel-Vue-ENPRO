{{-- редактирование IO (общие технические данные) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">IO (id)</h4>
        <select class="form-control" name="identifiedobject_id">

            {{-- текущее значение --}}
            @if ($content->identifiedobject)
                <option value="{{ $content->identifiedobject->id }}" selected disabled="disabled">
                    {{ $content->identifiedobject->id }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($identifiedobjects) > 0)
                {{-- справочник не пустой --}}

                @foreach($identifiedobjects as $identifiedobject)
                    <option value="{{ $identifiedobject->id }}">
                        {{ $identifiedobject->id }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>