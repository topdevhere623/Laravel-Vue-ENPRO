{{-- главная страница на фронте --}}

{{-- содержимое --}}
<div class="page-content">
    <div class="panel">
        <div class="row">
            <div class="col-lg-12">


                <div id="level">
                    <div id="content">
                        <div id="gears">
                            <div id="gears-static"></div>
                            <div id="gear-system-1">
                                <div id="gear15"></div>
                                <div id="gear14"></div>
                                <div id="gear13"></div>
                            </div>
                            <div id="gear-system-2">
                                <div id="gear10"></div>
                                <div id="gear3"></div>
                            </div>
                            <div id="gear-system-3">
                                <div id="gear9"></div>
                                <div id="gear7"></div>
                            </div>
                            <div id="gear-system-4">
                                <div id="gear6"></div>
                                <div id="gear4"></div>
                            </div>
                            <div id="gear-system-5">
                                <div id="gear12"></div>
                                <div id="gear11"></div>
                                <div id="gear8"></div>
                            </div>
                            <div id="gear1"></div>
                            <div id="gear-system-6">
                                <div id="gear5"></div>
                                <div id="gear2"></div>
                            </div>
                            <div id="chain-circle"></div>
                            <div id="chain"></div>
                            <div id="weight"></div>
                        </div>
                        <div id="title">
                            <h1>Добро пожаловать в <br>"{{ $setting_title }}"</h1>
                            <p>Переход в Админ-панель <a href="{{ route('admin') }}">здесь...</a></p>
                        </div>
                    </div>
                </div>

                @if (false)
                    <div style="margin-left: auto; margin-right: auto; width: 400px; margin-top: 200px;">

                        <h3 class="panel-title">Здраствуйте, это фронтенд! </h3>

                        <p>
                            Переход в Админ-панель <a href="{{ route('admin') }}">здесь...</a>
                        </p>

                        @if (false)
                            <p>
                                Информация о текущей версии PHP:

                                <br><br>
                                {!! phpinfo() !!}
                            </p>
                        @endif

                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
