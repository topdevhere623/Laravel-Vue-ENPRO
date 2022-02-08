{{-- редактирование classname (имя класса) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Имя класса (classname)</h4>
        <select class="form-control" name="classname_id">

            {{-- текущее значение --}}
            @if ($content->classname_id)
                <option value="{{ $content->classname->id }}" selected disabled="disabled">
                    {{ $content->classname->classname }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($classnames) > 0)
                {{-- справочник не пустой --}}

                @foreach($classnames as $classname)
                    <option value="{{ $classname->id }}">
                        {{ $classname->classname }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>