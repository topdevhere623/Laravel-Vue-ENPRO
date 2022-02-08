{{-- редактирование substation (подстанции) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">ТП</h4>
        <select class="form-control" name="substation_id" id="substation_id">

            {{-- текущее значение --}}
            @if ($content->substation_id)
                <option value="{{ $content->substation->id }}" selected disabled="disabled">
                    {{ $content->substation->identifiedobject->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($substations) > 0)
                {{-- справочник не пустой --}}

                @foreach($substations as $substation)
                    <option value="{{ $substation->id }}">
                        {{ $substation->identifiedobject->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>