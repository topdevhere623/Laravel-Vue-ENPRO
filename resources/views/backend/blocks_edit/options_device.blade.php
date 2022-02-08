{{-- редактирование device (устройства планшеты) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Устройства планшеты (device)</h4>
        <select class="form-control" name="device_id">

            {{-- текущее значение --}}
            @if ($content->device_id)
                <option value="{{ $content->device->id }}" selected disabled="disabled">
                    {{ $content->device->name }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($devices) > 0)
                {{-- справочник не пустой --}}

                @foreach($devices as $device)
                    <option value="{{ $device->id }}">
                        {{ $device->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>