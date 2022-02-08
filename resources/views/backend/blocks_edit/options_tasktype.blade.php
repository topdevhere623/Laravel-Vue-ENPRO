{{-- редактирование tasktype (типа задачи) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Тип задачи</h4>
        <select class="form-control" name="tasktype_id">

            {{-- текущее значение --}}
            @if ($content->tasktype_id)
                <option value="{{ $content->tasktype->id }}" selected disabled="disabled">
                    {{ $content->tasktype->title }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($tasktypes) > 0)
                {{-- справочник не пустой --}}

                @foreach($tasktypes as $tasktype)
                    <option value="{{ $tasktype->id }}">
                        {{ $tasktype->title }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>