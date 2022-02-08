{{-- редактирование basevoltage (базовые напряжения) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Базовое напряжение</h4>
        <select class="form-control" name="{{ $myFieldName ??  'basevoltage_id' }}">

            {{-- данные из справочника --}}
            @if (isset($basevoltages) and count($basevoltages) > 0)
                {{-- справочник не пустой --}}

                @foreach($basevoltages as $basevoltage)
                    <option value="{{ $basevoltage->id }}"
                            @if ($basevoltage->id == $myFieldValue)
                            selected
                            @endif
                    >
                        {{ $basevoltage->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>