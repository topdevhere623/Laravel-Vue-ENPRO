{{-- редактирование user (пользователя) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Пользователь</h4>
        <select class="form-control" name="user_id">

            {{-- текущее значение --}}
            @if ($content->user_id)
                <option value="{{ $content->user->id }}" selected disabled="disabled">
                    {{ $content->user->username }}
                </option>
            @endif

            {{-- данные из справочника --}}
            @if (count($users) > 0)
                {{-- справочник не пустой --}}

                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->username }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>
    </div>
</div>