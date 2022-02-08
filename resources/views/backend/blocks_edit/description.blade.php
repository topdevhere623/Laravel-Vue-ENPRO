{{-- редактирование описания --}}

{{--
$myFieldTitle - заголовок поля;
$myFieldName - имя поля, обязательно передавать из вьюшки!
$myFieldValue - значение поля;
$myRequired - обязательно ли это поле для заполнения;
--}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">{{ $myFieldTitle ?? 'Описание' }}</h4>
        <textarea class="form-control my-5"
                  name="{{ $myFieldName }}" id="{{ $myFieldName }}"
                  rows="7"
                  @if (isset($myRequired))
                  required
                @endif
        >
                {{ old($myFieldName, (isset($myFieldValue) ? $myFieldValue : $content->$myFieldName)) }}
        </textarea>
    </div>
</div>