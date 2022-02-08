{{-- редактирование substationinfo (информация о подстанции) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Информация о ТП (substationinfo)</h4>
        <select class="form-control" name="substationinfo_id">

            {{-- текущее значение --}}
            @if ($content->substationinfo_id)
                <option value="{{ $content->substationinfo->id }}" selected disabled="disabled">
                    {{ $content->substationinfo->description }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($substationinfos) > 0)
                {{-- справочник не пустой --}}

                @foreach($substationinfos as $substationinfo)
                    <option value="{{ $substationinfo->id }}">
                        {{ $substationinfo->description }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>