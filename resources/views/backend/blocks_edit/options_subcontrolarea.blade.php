{{-- редактирование subcontrolarea (имя класса) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Subcontrolarea</h4>
        <select class="form-control" name="subcontrolarea_id">

            {{-- текущее значение --}}
            @if ($content->subcontrolarea_id)
                <option value="{{ $content->subcontrolarea->id }}" selected disabled="disabled">
                    {{ $content->subcontrolarea->id }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($subcontrolareas) > 0)
                {{-- справочник не пустой --}}

                @foreach($subcontrolareas as $subcontrolarea)
                    <option value="{{ $subcontrolarea->id }}">
                        {{ $subcontrolarea->id }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>