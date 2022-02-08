{{-- редактирование substationfunction (функции подстанции) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Функции ТП (substationfunction)</h4>
        <select class="form-control" name="substationfunction_id">

            {{-- текущее значение --}}
            @if ($content->substationfunction_id)
                <option value="{{ $content->substationfunction->id }}" selected disabled="disabled">
                    {{ $content->substationfunction->function }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($substationfunctions) > 0)
                {{-- справочник не пустой --}}

                @foreach($substationfunctions as $substationfunction)
                    <option value="{{ $substationfunction->id }}">
                        {{ $substationfunction->function }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>