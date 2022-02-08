{{-- редактирование connector (фидер) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Фидер</h4>
        <select class="form-control" name="connector_id" id="connector_id">

            {{-- данные из справочника --}}
            @if (count($connectors) > 0)
                {{-- справочник не пустой --}}

                @foreach($connectors as $connector)
                    <option value="{{ $connector->id }}"
                            {{-- текущее значение --}}
                            @if ($connector->id == $content->connector->id)
                            selected
                            @endif
                    >
                        {{ $connector->identifiedobject->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>