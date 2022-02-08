{{-- редактирование названия --}}

{{--
$myFieldTitle - заголовок поля;
$myFieldName - имя поля, обязательно передавать из вьюшки!
$myFieldValue - значение поля;
$myPlaceHolder - подсказка в пустом поле;
$myRequired - обязательно ли это поле для заполнения;
--}}

<div class="form-field">
    <input type="text" class="text-field"
           name='{{ $myFieldName }}' id='{{ $myFieldName }}'
           value="{{ old($myFieldName, (isset($myFieldValue) ? $myFieldValue : $content->$myFieldName)) }}"
           placeholder="{{ $myPlaceHolder ?? Str::lower($myFieldTitle) }}"
           @if (isset($myRequired))
           required
            @endif
    >
    <div class="form-input-label">{{ $myFieldTitle ?? 'Наименование' }}</div>
</div>