{{-- пагинация в списке данных и сообщение Пользователю, если данных нет --}}

@if(count($content) > 0)

    {{-- пагинация --}}
    @if( method_exists($content,'links') )
        <div class="table-bottom-bar">
            <div class="right">
                {{ $content->links() ?? '' }}
            </div>
        </div>
    @endif

@else

    {{-- сообщение Пользователю --}}
    <div class="card card-inverse bg-primary">
        <div class="card-block">
            <h4 class="card-title">Внимание!</h4>
            <p class="card-text">
                {{ __('edit.no_data_in_razdel') }}
            </p>
        </div>
    </div>

@endif