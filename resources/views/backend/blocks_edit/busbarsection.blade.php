{{-- редактирование данных IO в дочернем обьекте --}}

<div class="row">
    <div class="col-lg-12">
        @foreach ($content->busbarsections as $key => $busbarsection)
            <div class="row">
                <div class="col">

                    {{-- редактирование названия --}}
                    @include('backend.blocks_edit.name', [
                    'myFieldTitle' => 'Диспетчерское имя',
                    'myFieldName' => '_busbarsection_name_['.$busbarsection->identifiedobject->id.']',
                    'myFieldValue' => $busbarsection->identifiedobject->name,
                    'myRequired' => 1,
                    ])

                </div>

                <div class="col">

                    {{-- редактирование названия --}}
                    @include('backend.blocks_edit.name', [
                    'myFieldTitle' => 'Keylink',
                    'myFieldName' => '_busbarsection_keylink_['.$busbarsection->identifiedobject->id.']',
                    'myFieldValue' => $busbarsection->identifiedobject->keylink,
                    ])

                </div>
            </div>
        @endforeach
    </div>
</div>
