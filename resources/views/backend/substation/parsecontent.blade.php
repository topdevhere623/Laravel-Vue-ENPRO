{{-- список --}}
<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">
        @if (isset($content->id))
            {{ $content->identifiedobject->name }}
        @else
            Новая
        @endif
    </h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item"><a href="{{ route('substation.index') }}">{{ App\Models\Substation::title2 }}</a></li>
        <li class="breadcrumb-item active">{{ $content->identifiedobject->name }}</li>
    </ol>
</div>

{{-- содержимое --}}
<div class="page-content editor">
    <div class="col-lg-12 map-auto-height" id="substactionData">
    </div>
</div>
