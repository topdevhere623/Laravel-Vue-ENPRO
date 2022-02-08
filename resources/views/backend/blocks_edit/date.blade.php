{{-- редактирование поля дата --}}

{{--
$myFieldTitle - заголовок поля;
$myFieldName - имя поля, обязательно передавать из вьюшки!
$myFieldValue - значение поля;
$myRequired - обязательно ли это поле для заполнения;
--}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">{{ $myFieldTitle }}</h4>

        <div class="input-group w-300">
              <span class="input-group-addon">
                <i class="icon wb-calendar" aria-hidden="true"></i>
              </span>
            <input type="text" class="form-control" data-plugin="datepicker"
                   name="{{ $myFieldName }}" id="{{ $myFieldName }}"
                   value="{{ old($myFieldName, (isset($myFieldValue) ? $myFieldValue : $content->$myFieldName)) }}"
                   placeholder="дата"
                   @if (isset($myRequired))
                   required
                    @endif
            >
        </div>

    </div>
</div>