Тестовая вьюшка Сергей Титов
{{ dd($content) }}
<br><br>
<div class="m-50">
    @if($content)
        @foreach ($content as $item)
            {{ $item }}
            <br>
        @endforeach
    @endif
</div>