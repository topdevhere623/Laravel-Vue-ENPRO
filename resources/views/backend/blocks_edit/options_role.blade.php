{{-- редактирование role (роли) --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Роль</h4>
        <select class="form-control" name="role_id">

            {{-- данные из справочника --}}
            @if (count($roles) > 0)
                {{-- справочник не пустой --}}

                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                            {{-- текущее значение (disabled ставить нельзя, иначе в request не приходит)--}}
                            @if (isset($content->role[0]->id) and $role->id == $content->role[0]->id)
                            selected
                            @endif
                    >
                        {{ $role->name }}
                    </option>
                @endforeach

            @else
                {{-- справочник пустой --}}
                <option disabled>- в справочнике нет данных -</option>
            @endif

        </select>

        <small>
            <a href="{{ route("admin_user_role.index") }}" target="_blank">О ролях подробнее здесь...</a>
        </small>
    </div>
</div>