{{-- кнопка сохранить --}}

@if (isset($thisModal))
    {{-- было открыто в модальном окне--}}
    <button type="button" class="button" onclick="myFunAjaxSaveInModal('{{ $thisModal }}')">
        Сохранить
    </button>
@else
    {{-- было открыто на странице--}}
    <button type="submit" class="button">
        Сохранить
    </button>
@endif