{{-- редактирование bay (имя класса) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Bay</h4>
        <select class="form-control" name="bay_id">

            {{-- текущее значение --}}
            @if ($content->bay_id)
                <option value="{{ $content->bay->id }}" selected disabled="disabled">
                    {{ $content->bay->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($bays) > 0)
                {{-- справочник не пустой --}}

                @foreach($bays as $bay)
                    <option value="{{ $bay->id }}">
                        {{ $bay->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>