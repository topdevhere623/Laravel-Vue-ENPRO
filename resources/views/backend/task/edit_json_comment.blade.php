{{-- json текстовый комментарий с фото --}}

@if (!empty($content->json_file))
    <div class="row">
        <div class="col-lg-4">

            <p>
                Данные с полученного файла: [{{ $content->json_file }}]
            </p>
            {!!  $content->json_parse_text !!}

        </div>

        <div class="col-lg-8">

            {{-- изображения из файла json --}}
            @if (!is_null($arrJsonImg))

                <p>
                    Переданные изображения:
                    <br>
                    <i>
                        <small>(для увеличения нажмите на фото)</small>
                    </i>
                </p>

                @foreach($arrJsonImg['thumb'] as $key => $item)
                    <a href='{{ $arrJsonImg['dir'].(str_replace('_thumb.', '_hd.', $item)) }}' target="_blank">
                        <img src='{{ $arrJsonImg['dir'].$item }}' class="m-5">
                    </a>
                @endforeach

            @endif

        </div>
    </div>
@else
    <p>
        {{ __('edit.no_json_file') }}
    </p>
@endif

