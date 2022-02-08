{{-- редактирование subclass (имя класса) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Подксласс (subclass)</h4>
        <select class="form-control" name="subclass_id">

            {{-- текущее значение --}}
            @if ($content->subclass_id)
                <option value="{{ $content->subclass->id }}" selected disabled="disabled">
                    {{ $content->subclass->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($subclasses) > 0)
                {{-- справочник не пустой --}}

                @foreach($subclasses as $subclass)
                    <option value="{{ $subclass->id }}">
                        {{ $subclass->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>