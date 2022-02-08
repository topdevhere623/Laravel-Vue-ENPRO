{{-- редактирование manufacturer (имя класса) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Производители (manufacturer)</h4>
        <select class="form-control" name="manufacturer_id">

            {{-- текущее значение --}}
            @if ($content->manufacturer_id)
                <option value="{{ $content->manufacturer->id }}" selected disabled="disabled">
                    {{ $content->manufacturer->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($manufacturers) > 0)
                {{-- справочник не пустой --}}

                @foreach($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}">
                        {{ $manufacturer->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>