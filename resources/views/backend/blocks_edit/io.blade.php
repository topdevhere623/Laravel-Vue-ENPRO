{{-- редактирование данных IO в дочернем обьекте --}}

<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col">

                {{-- редактирование названия  --}}
                @include('backend.blocks_edit.name', [
                'myFieldTitle' => 'Диспетчерское имя',
                'myFieldName' => '_io_name',
                'myFieldValue' => $content->identifiedobject->name,
                'myRequired' => 1,
                ])

            </div>

            <div class="col">

                {{-- редактирование названия  --}}
                @include('backend.blocks_edit.name', [
                'myFieldTitle' => 'Диспетчерский номер',
                'myFieldName' => '_io_localname',
                'myFieldValue' => $content->identifiedobject->localname,
                ])

            </div>
        </div>

        {{-- редактирование координат: широта, долгота --}}
        @include('backend.blocks_edit.lat_long',[
        'myMapObject' => $myMapObject,
        'myFieldName1' => '_io_address',
        'myFieldName2' => '_io_lat',
        'myFieldName3' => '_io_long',
        'myFieldValue1' => $content->identifiedobject->address,
        'myFieldValue2' => $content->identifiedobject->lat,
        'myFieldValue3' => $content->identifiedobject->long,
        ])

        <div class="row">

            <div class="col-md-4">

                {{-- редактирование названия  --}}
                @include('backend.blocks_edit.name', [
                'myFieldTitle' => 'Keylink',
                'myFieldName' => '_io_keylink',
                'myFieldValue' => $content->identifiedobject->keylink,
                ])

                {{-- редактирование basevoltage (базовые напряжения) --}}
                @if ($myMapObject === 'Substation')

                @else
                    {{-- выбор из справочника vue-компонент --}}
                    <options-sprav-component get-sprav='BaseVoltage' get-title='Базовое напряжение' get-field='_io_voltage_id' get-current-id='{{ $content->identifiedobject->voltage_id }}'>
                    </options-sprav-component>
                @endif

                @if (false)
                    {{-- редактирование названия  --}}
                    @include('backend.blocks_edit.name', [
                    'myFieldTitle' => 'Кол-во фаз (phaseno)',
                    'myFieldName' => '_io_phaseno',
                    'myFieldValue' => $content->identifiedobject->phaseno,
                    ])
                @endif

            </div>

            <div class="col-md-8">

                {{-- редактирование описания  --}}
                @include('backend.blocks_edit.description', [
                'myFieldTitle' => 'Описание',
                'myFieldName' => '_io_description',
                'myFieldValue' => $content->identifiedobject->description,
                ])

            </div>
        </div>

    </div>
</div>

