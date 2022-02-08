{{-- редактирование asset (общие данные) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Asset (id)</h4>
        <select class="form-control" name="asset_id">

            {{-- текущее значение --}}
            @if ($content->asset)
                <option value="{{ $content->asset->id }}" selected disabled="disabled">
                    {{ $content->asset->id }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($assets) > 0)
                {{-- справочник не пустой --}}

                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}">
                        {{ $asset->id }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>