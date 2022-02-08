{{-- редактирование address (адреса) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Адрес (address)</h4>
        <select class="form-control" name="address_id">

            {{-- текущее значение --}}
            @if ($content->address_id)
                <option value="{{ $content->address->id }}" selected disabled="disabled">
                    {{ $content->address->address }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($addresses) > 0)
                {{-- справочник не пустой --}}

                @foreach($addresses as $address)
                    <option value="{{ $address->id }}">
                        {{ $address->address }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>