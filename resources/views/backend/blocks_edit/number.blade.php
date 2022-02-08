{{-- редактирование цифрового значения --}}

{{--
$myFieldTitle - заголовок поля;
$myFieldName - имя поля, обязательно передавать из вьюшки!
$myFieldValue - значение поля;
$myPlaceHolder - подсказка в пустом поле;
$myRequired - обязательно ли это поле для заполнения;
--}}

<div class="form-field">
    <input type="number" class="text-field"
           name="{{ $myFieldName }}" id="{{ $myFieldName }}"
           value="{{ old($myFieldName, (isset($myFieldValue) ? $myFieldValue : (isset($content->$myFieldName) ? $content->$myFieldName : 0))) }}"
           placeholder="{{ $myPlaceHolder ?? Str::lower($myFieldTitle) }}"
           @if (isset($myRequired))
           required
            @endif
    >
    <div class="form-input-label">{{ $myFieldTitle ?? 'Цифровое значение' }}</div>
</div>