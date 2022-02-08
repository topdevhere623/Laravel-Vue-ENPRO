{{-- редактирование дататайм --}}

{{-- поле с маско datetime --}}
<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">{{ $myFieldTitle ?? 'Наименование' }}</h4>
        <input type="text" class="form-control" name="{{ $myFieldName ?? 'name' }}" id="{{ $myFieldName ?? 'name' }}" data-plugin="formatter" data-pattern="[[99]]:[[99]] [[99]]-[[99]]-[[9999]]" value="{{ $content->$myFieldName ?? '' }}">
        <small>чч:мм дд-мм-гггг</small>
    </div>
</div>

{{-- два поля дата и время - но нужно при выводе разделять, потом при сохранении обьединять --}}
@if (false)
    <div class="row">
        <div class="form-group col-md-12">
            <h4 class="example-title">{{ $myFieldTitle }}</h4>

            <div class="example-wrap">
                <div class="example datepair-wrap" data-plugin="datepair">

                    <div class="input-daterange-wrap">
                        <div class="input-daterange">
                            <div class="input-group">
                      <span class="input-group-addon">
                        <i class="icon wb-calendar" aria-hidden="true"></i>
                      </span>
                                <input type="text" class="form-control datepair-date datepair-start" data-plugin="datepicker">
                            </div>
                            <div class="input-group">
                      <span class="input-group-addon">
                        <i class="icon wb-time" aria-hidden="true"></i>
                      </span>
                                <input type="text" class="form-control datepair-time datepair-start ui-timepicker-input" data-plugin="timepicker" name="{{ $myFieldName }}" autocomplete="off">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endif