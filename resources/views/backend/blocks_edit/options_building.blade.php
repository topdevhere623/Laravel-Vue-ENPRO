{{-- редактирование building (сооружения) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Сооружение (building)</h4>
        <select class="form-control" name="building_id">

            {{-- текущее значение --}}
            @if ($content->building_id)
                <option value="{{ $content->building->id }}" selected disabled="disabled">
                    {{ $content->building->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($buildings) > 0)
                {{-- справочник не пустой --}}

                @foreach($buildings as $building)
                    <option value="{{ $building->id }}">
                        {{ $building->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>