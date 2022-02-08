{{-- панель навигации --}}

<div class="header-page">
    <h3>
        <span id="tAclineName">
            @if ($acline->identifiedobject->name)
                {{ $acline->identifiedobject->name }}
            @else
                {{ $myAclineNameDefault }}
            @endif
        </span>
    </h3>
    <div class="header-page-bottom">
        <div class="left">
            {{-- хлебные крошки --}}
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('acline.index') }}">{{ App\Models\Acline::title2 }}</a>
                @if (isset($acline->id) and $acline->id > 0)
                    <li class="breadcrumb-item"><a href="{{ route('acline.edit', ['id'=>$acline->id]) }}">Карточка</a>
                @endif
                <li class="breadcrumb-item active">На карте</li>
            </ol>

        </div>
        <div class="right">

            @if ($userHasEditRights === 1)

                {{-- сохранить --}}
                <div class="checkbox">
                    <button class="link-icon" onclick="funSave()">
                        <span class="icon icon-save"></span>
                        Сохранить
                    </button>
                </div>

                {{-- модальное окно с настройками карты --}}
                <div class="checkbox">
                    <button class="link-icon" onclick="funModalMapSettings()">
                        <span class="icon md-settings"></span>
                        Настройка
                    </button>
                </div>

            @endif
        </div>
    </div>
</div>
