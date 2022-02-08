{{-- phpinfo - админка --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">Информация о версии PHP на хостинге</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
    </ol>
</div>

{{-- содержимое --}}
<div class="page-content">
    <div class="panel">
        <div class="row">
            <div class="col-lg-12">

                <div class="panel-heading">
                    <h3 class="panel-title">Информация о версии PHP на хостинге</h3>
                </div>

                <div class="panel-body">
                    {{ phpinfo() }}
                </div>

            </div>
        </div>
    </div>
</div>
