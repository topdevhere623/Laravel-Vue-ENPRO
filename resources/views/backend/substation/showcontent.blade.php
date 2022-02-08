{{-- список --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">
        @if (isset($content->id))
        {{ $identifiedobject->name }}
        @else
        Новая
        @endif
    </h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item"><a href="{{ route('substation.index') }}">{{ App\Models\Substation::title2 }}</a></li>
        <li class="breadcrumb-item active">{{ $identifiedobject->name }}</li>
    </ol>

    {{-- действия на странице --}}
    <div class="page-header-actions">
        <a href="{{ route('admin',["model" => "substation"]) }}" class="btn btn-lg btn-icon btn-success" data-toggle="tooltip"
           data-original-title="На карте">
            <i class="icon md-google-maps" aria-hidden="true"></i>
        </a>
        <a href="{{ route('substation.edit') }}" class="btn btn-lg btn-icon btn-primary btn-round" data-toggle="tooltip"
           data-original-title="Редактировать" onclick="return false">
            <i class="icon md-plus" aria-hidden="true"></i>
        </a>
    </div>

</div>

{{-- содержимое --}}
<div class="page-content" style="background-color:#202225 ">
    <div class="panel" style="background-color:#202225 ">
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-lg-12" style="overflow: scroll">
                    {!! $svg !!}
                </div>
            </div>
        </div>
    </div>
</div>
