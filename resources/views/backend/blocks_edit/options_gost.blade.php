{{-- редактирование gost (имя класса) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">ГОСТ (gost)</h4>
        <select class="form-control" name="gost_id">

            {{-- текущее значение --}}
            @if ($content->gost_id)
                <option value="{{ $content->gost->id }}" selected disabled="disabled">
                    {{ $content->gost->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($gosts) > 0)
                {{-- справочник не пустой --}}

                @foreach($gosts as $gost)
                    <option value="{{ $gost->id }}">
                        {{ $gost->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>