{{-- левое меню - админка --}}

<div class="site-menubar">
    <div class="site-menubar-body">

        {{-- если убрать <ul> то контентная часть сдвинется --}}
        <ul class="site-menu" data-plugin="menu">

            {{-- редактор ЛЭП--}}
            @if (
            Auth::user()->isVendor() or
            Auth::user()->isAdmin() or
            Auth::user()->isManager() or
            Auth::user()->isDispatcher() or
            Auth::user()->isOperator() or
            Auth::user()->isMaster() or
            Auth::user()->isWorking()
            )
                <div class="group js-nav-group">
                    <div class="group-caption js-nav-trigger">
                        Редактор ЛЭП
                        <div class="collapse-button icon icon-chevron"></div>
                    </div>
                    <div class="group-items js-nav-content">

                        @php $myText = '<span class="icon icon-sidebar-1"></span><span>Создать новую</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isOperator()
                        )
                            <a class="group-item" href="{{ route('acline.map.edit') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-2"></span><span>Открыть существующую</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('acline.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-3"></span><span>CIM модель обьекта</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="#">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-4"></span><span>Статусы линий</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('aclineStatus.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-2"></span><span>Отчет-3</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('acline.report', ['report_id'=>3]) }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-2"></span><span>Целостность данных</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isOperator()
                        )
                            <a class="group-item" href="{{ route('acline.repair') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-1"></span><span>SVG-карта</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('acline.map.edit', ['id'=>0, 'regim'=>'svg']) }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                    </div>
                </div>
            @endif

            {{-- редактор ТП/РП--}}
            @if (
            Auth::user()->isVendor() or
            Auth::user()->isAdmin() or
            Auth::user()->isManager() or
            Auth::user()->isDispatcher() or
            Auth::user()->isOperator() or
            Auth::user()->isMaster() or
            Auth::user()->isWorking()
            )
                <div class="group js-nav-group">
                    <div class="group-caption js-nav-trigger">
                        Редактор ТП/РП
                        <div class="collapse-button icon icon-chevron"></div>
                    </div>
                    <div class="group-items js-nav-content">

                        @php $myText = '<span class="icon icon-sidebar-6"></span><span>ТП/РП</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('substation.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @if (false)

                            @php $myText = '<span class="icon icon-sidebar-4"></span><span>КТП</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="#">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-5"></span><span>Связи</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="#">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                        @endif

                    </div>
                </div>
            @endif

            {{-- редактор опоры --}}
            @if (
            Auth::user()->isVendor() or
            Auth::user()->isAdmin() or
            Auth::user()->isManager() or
            Auth::user()->isDispatcher() or
            Auth::user()->isOperator() or
            Auth::user()->isMaster() or
            Auth::user()->isWorking()
            )
                <div class="group js-nav-group">
                    <div class="group-caption js-nav-trigger">
                        Редактор опор
                        <div class="collapse-button icon icon-chevron"></div>
                    </div>
                    <div class="group-items js-nav-content">

                        @php $myText = '<span class="icon icon-sidebar-4"></span><span>Компоненты</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('towerconstructionmaster.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-4"></span><span>Сборные агрегаты</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('towerconstructionaggregate.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-4"></span><span>Марки опор</span>'; @endphp
                        @if (
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('towerinfo.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-4"></span><span>Материалы опор</span>'; @endphp
                        @if (
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('towermaterial.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-4"></span><span>Назначение опор</span>'; @endphp
                        @if (
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('towerkind.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-4"></span><span>Конструкции опор</span>'; @endphp
                        @if (
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('towerconstructionkind.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- модуль ТОиР --}}
            @if (false)

                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <div class="group js-nav-group">
                        <div class="group-caption js-nav-trigger">
                            Модуль ТОиР
                            <div class="collapse-button icon icon-chevron"></div>
                        </div>
                        <div class="group-items js-nav-content">

                            @php $myText = '<span class="icon icon-sidebar-9"></span><span>План текущего года</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isOperator()
                            )
                                <a class="group-item" href="#">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-7"></span><span>Исполнение программы 2020</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isOperator()
                            )
                                <a class="group-item" href="#">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-8"></span><span>Проект плана 2021</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isOperator()
                            )
                                <a class="group-item" href="#">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-12"></span><span>Технологическое присоединение</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isOperator()
                            )
                                <a class="group-item" href="#">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                        </div>
                    </div>
                @endif

            @endif

            {{-- абоненты --}}
            @if (
            Auth::user()->isVendor() or
            Auth::user()->isAdmin() or
            Auth::user()->isManager() or
            Auth::user()->isDispatcher() or
            Auth::user()->isOperator() or
            Auth::user()->isMaster() or
            Auth::user()->isWorking()
            )
                <div class="group js-nav-group">
                    <div class="group-caption js-nav-trigger">
                        Абоненты
                        <div class="collapse-button icon icon-chevron"></div>
                    </div>
                    <div class="group-items js-nav-content">

                        @php $myText = '<span class="icon icon-sidebar-11"></span><span>Абоненты</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('customer.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @if (false)

                            @php $myText = '<span class="icon icon-sidebar-10"></span><span>Сотрудники компании</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="#">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                        @endif

                    </div>
                </div>
            @endif

            {{-- реализация услуг --}}
            @if (false)

                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <div class="group js-nav-group">
                        <div class="group-caption js-nav-trigger">
                            Реализация услуг
                            <div class="collapse-button icon icon-chevron"></div>
                        </div>
                        <div class="group-items js-nav-content">

                        </div>
                    </div>
                @endif

            @endif

            {{-- настройки системы безопасности --}}
            @if (
            Auth::user()->isVendor() or
            Auth::user()->isAdmin()
            )
                <div class="group js-nav-group">
                    <div class="group-caption js-nav-trigger">
                        Настройки системы безопасности
                        <div class="collapse-button icon icon-chevron"></div>
                    </div>
                    <div class="group-items js-nav-content">

                        @php $myText = '<span class="icon icon-sidebar-11"></span><span>Пользователи</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin()
                        )
                            <a class="group-item" href="{{ route('user.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-10"></span><span>Роли</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('admin_user_role.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-10"></span><span>Журнал операций</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin()
                        )
                            <a class="group-item" href="{{ route('admin_log.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                    </div>
                </div>
            @endif



            {{-- справочники --}}
            @if (
            Auth::user()->isVendor() or
            Auth::user()->isAdmin() or
            Auth::user()->isManager() or
            Auth::user()->isDispatcher() or
            Auth::user()->isOperator() or
            Auth::user()->isMaster() or
            Auth::user()->isWorking()

            )
               

                <div class="group js-nav-group">
                    <div class="group-caption js-nav-trigger">
                        Справочники
                        <div class="collapse-button icon icon-chevron"></div>
                    </div>
                    <div class="group-items js-nav-content">

                        <div class="group app-nav-group-dropdown-level-2 js-nav-group">
                            <div class="group-caption js-nav-trigger">
                                Коммутационные аппараты
                                <div class="collapse-button icon icon-chevron"></div>
                            </div>
                            <div class="group-items js-nav-content">
                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Предохранители 3 кВ и выше</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('fuse_info.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                @php echo $myText; @endphp
                                </span>
                                @endif

                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Реклоузеры 6-35 кВ</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('recloser_info.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                @php echo $myText; @endphp
                                </span>
                                @endif

                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Выключатели нагрузки 3-10 кВ</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('load_break_switch_info.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                @php echo $myText; @endphp
                                </span>
                                @endif

                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Выключатели 3-750 кВ</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('breaker_info.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                @php echo $myText; @endphp
                                </span>
                                @endif

                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Разъединители выше 1 кВ</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('disconnector_info.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                @php echo $myText; @endphp
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="group app-nav-group-dropdown-level-2 js-nav-group">
                            <div class="group-caption js-nav-trigger">
                                Провода и кабели
                                <div class="collapse-button icon icon-chevron"></div>
                            </div>
                            <div class="group-items js-nav-content">
                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Марки проводов</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('aclinesegmentinfo.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                        @php echo $myText; @endphp
                                    </span>
                                @endif
                            <!-- марки проводов новый -->
                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Марки проводов новый</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('overhead_wire_info.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                        @php echo $myText; @endphp
                                    </span>
                                @endif

                            <!-- марки кабелей новый -->
                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Марки кабелей новый</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('cable_info.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                        @php echo $myText; @endphp
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="group app-nav-group-dropdown-level-2 js-nav-group">
                            <div class="group-caption js-nav-trigger">
                               Справочник дефектов
                                <div class="collapse-button icon icon-chevron"></div>
                            </div>
                            <div class="group-items js-nav-content">
                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Группы оборудования</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('enproclassdefect.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                        @php echo $myText; @endphp
                                    </span>
                                @endif
                            <!-- марки проводов новый -->
                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Группы измерений</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('enprogroupdefect.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                        @php echo $myText; @endphp
                                    </span>
                                @endif

                            <!-- марки кабелей новый -->
                                @php $myText = '<span class="icon icon-sidebar-12"></span><span>Дефекты</span>'; @endphp
                                @if (
                                Auth::user()->isVendor() or
                                Auth::user()->isAdmin() or
                                Auth::user()->isManager() or
                                Auth::user()->isDispatcher() or
                                Auth::user()->isOperator() or
                                Auth::user()->isMaster() or
                                Auth::user()->isWorking()
                                )
                                    <a class="group-item" href="{{ route('enprodefect.index') }}">
                                        @php echo $myText; @endphp
                                    </a>
                                @else
                                    <span class="disabled">
                                        @php echo $myText; @endphp
                                    </span>
                                @endif
                       
                            </div>
                        </div>
                        @if (false)

                            @php $myText = '<span class="icon icon-sidebar-6"></span><span>Разъединители</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="{{ route('disconnector.index') }}">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-6"></span><span>Разрядники</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="{{ route('discharger.index') }}">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-9"></span><span>Потребители</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="{{ route('customer.index') }}">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-10"></span><span>Конечные точки</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="{{ route('endpoint.index') }}">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                    @php echo $myText; @endphp
                                </span>
                            @endif

                            @php $myText = '<span class="icon icon-sidebar-11"></span><span>Фидеры</span>'; @endphp
                            @if (
                            Auth::user()->isVendor() or
                            Auth::user()->isAdmin() or
                            Auth::user()->isManager() or
                            Auth::user()->isDispatcher() or
                            Auth::user()->isOperator() or
                            Auth::user()->isMaster() or
                            Auth::user()->isWorking()
                            )
                                <a class="group-item" href="{{ route('connector.index') }}">
                                    @php echo $myText; @endphp
                                </a>
                            @else
                                <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                            @endif

                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Виды и свойства</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('all_kind.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                                @php echo $myText; @endphp
                            </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Общие тех.данные (IO)</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('identifiedobject.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Профессий</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('aclinesegmentinfo.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                        @php echo $myText; @endphp
                        </span>
                        @endif


                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Базовые напряжения</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('basevoltage.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Марки разъединителей</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('disconnectorinfo.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Марки разрядников</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('dischargerinfo.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Условия прокладки</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('layingconditionkind.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-5"></span><span>Пересечение местности</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('crossing.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Типы пересеченой местности</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('crossingtype.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Материалы</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('materialkind.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif

                    <!-- марки проводов новый -->
                        @php $myText = '<span class="icon icon-sidebar-12"></span><span>Трансформаторы силовые</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('old_transformer_tank_info.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif


                         <!-- марки проводов новый -->
                         @php $myText = '<span class="icon icon-sidebar-12"></span><span>Загрузка excel файла</span>'; @endphp
                        @if (
                        Auth::user()->isVendor() or
                        Auth::user()->isAdmin() or
                        Auth::user()->isManager() or
                        Auth::user()->isDispatcher() or
                        Auth::user()->isOperator() or
                        Auth::user()->isMaster() or
                        Auth::user()->isWorking()
                        )
                            <a class="group-item" href="{{ route('loadExcel.index') }}">
                                @php echo $myText; @endphp
                            </a>
                        @else
                            <span class="disabled">
                            @php echo $myText; @endphp
                        </span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- разное в данный момент не используемое --}}

            @if (false)

                {{-- паспортизация ЛЭП --}}
                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <div class="group js-nav-group">
                        <div class="group-caption js-nav-trigger">
                            Паспортизация ЛЭП
                            <div class="collapse-button icon icon-chevron"></div>
                        </div>
                        <div class="group-items js-nav-content">
                            <a class="group-item" href="{{ route('task.index') }}">
                                <span class="icon icon-user"></span>
                                <span>Задачи</span>
                            </a>
                            <a class="group-item" href="{{ route('task.jsonFiles') }}">
                                <span class="icon icon-user"></span>
                                <span>Файлы к задачам с мобильного приложения</span>
                            </a>
                            <a class="group-item" href="{{ route('device.index') }}">
                                <span class="icon icon-user"></span>
                                <span>Устройства (планшеты)</span>
                            </a>
                            <a class="group-item" href="{{ route('file.index') }}">
                                <span class="icon icon-user"></span>
                                <span>Файлы</span>
                            </a>
                            <a class="group-item" href="{{ route('tasktype.index') }}">
                                <span class="icon icon-user"></span>
                                <span>Типы задач</span>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- задачи todo--}}
                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <li class="site-menu-category">Задачи Todo</li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('todo.index') }}">
                            <i class="site-menu-icon md-assignment-o" aria-hidden="true"></i>
                            <span class="site-menu-title">Задачи</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('todostatus.index') }}">
                            <i class="site-menu-icon md-alarm" aria-hidden="true"></i>
                            <span class="site-menu-title">Статусы</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('todostage.index') }}">
                            <i class="site-menu-icon md-time-countdown" aria-hidden="true"></i>
                            <span class="site-menu-title">Этапы</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('company.index') }}">
                            <i class="site-menu-icon md-store" aria-hidden="true"></i>
                            <span class="site-menu-title">Организации</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('fio.index') }}">
                            <i class="site-menu-icon md-male-female" aria-hidden="true"></i>
                            <span class="site-menu-title">ФИО</span>
                        </a>
                    </li>
                @endif

                {{-- справочники --}}
                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <li class="site-menu-category">Для прототипа таблицы</li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('asset.index') }}">
                            <i class="site-menu-icon md-case" aria-hidden="true"></i>
                            <span class="site-menu-title">Asset (общие данные)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('classname.index') }}">
                            <i class="site-menu-icon md-copy" aria-hidden="true"></i>
                            <span class="site-menu-title">Classname (имена классов)</span>
                        </a>
                    </li>

                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('subclass.index') }}">
                            <i class="site-menu-icon md-copy" aria-hidden="true"></i>
                            <span class="site-menu-title">Subclass (подклассы)</span>
                        </a>
                    </li>

                    {{-- разделитель --}}
                    <li class="site-menu-category">Другие таблицы</li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('address.index') }}">
                            <i class="site-menu-icon md-pin-drop" aria-hidden="true"></i>
                            <span class="site-menu-title">Address (адреса)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('alarmdevice.index') }}">
                            <i class="site-menu-icon md-timer" aria-hidden="true"></i>
                            <span class="site-menu-title">Alarmdevice (сигнализирующии устройства)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('bbsecinsulatorinfo.index') }}">
                            <i class="site-menu-icon md-panorama-horizontal" aria-hidden="true"></i>
                            <span class="site-menu-title">Bbsecinsulatorinfo (марки изоляторов)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('building.index') }}">
                            <i class="site-menu-icon md-city" aria-hidden="true"></i>
                            <span class="site-menu-title">Building (сооружения)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('material.index') }}">
                            <i class="site-menu-icon md-apps" aria-hidden="true"></i>
                            <span class="site-menu-title">Material (материалы)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('picturekind.index') }}">
                            <i class="site-menu-icon md-image-o" aria-hidden="true"></i>
                            <span class="site-menu-title">Picturekind (виды изображений)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('picturetype.index') }}">
                            <i class="site-menu-icon md-image-o" aria-hidden="true"></i>
                            <span class="site-menu-title">Picturetype (тип изображений)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('substationinfo.index') }}">
                            <i class="site-menu-icon md-balance" aria-hidden="true"></i>
                            <span class="site-menu-title">Substationinfo (инфо по подстанциям)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('substationtpinfo.index') }}">
                            <i class="site-menu-icon md-balance" aria-hidden="true"></i>
                            <span class="site-menu-title">Substationtpinfo (инфо о ТП подстанций)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('substationfunction.index') }}">
                            <i class="site-menu-icon md-balance" aria-hidden="true"></i>
                            <span class="site-menu-title">Substationfunction (функции подстанции)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('substationfunctionkind.index') }}">
                            <i class="site-menu-icon md-balance" aria-hidden="true"></i>
                            <span class="site-menu-title">Substationfunctionkind (виды функций подстанций)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('cableboxkind.index') }}">
                            <i class="site-menu-icon md-panorama-vertical" aria-hidden="true"></i>
                            <span class="site-menu-title">Cableboxkind (муфты)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('primingkind.index') }}">
                            <i class="site-menu-icon md-layers" aria-hidden="true"></i>
                            <span class="site-menu-title">Primingkind (грунт заземлений)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('ptendinsulatorkind.index') }}">
                            <i class="site-menu-icon md-unfold-less" aria-hidden="true"></i>
                            <span class="site-menu-title">Ptendinsulatorkind (типы изоляторов)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('groundingkind.index') }}">
                            <i class="site-menu-icon md-leak" aria-hidden="true"></i>
                            <span class="site-menu-title">Groundingkind (назначение заземлений)</span>
                        </a>
                    </li>

                    {{--<a class="group-item" href="{{ route('towerpreservkind.index') }}">--}}
                    {{--<span class="icon icon-sidebar-4"></span>--}}
                    {{--<span>Towerpreservkind (защитные покрытия опор)</span>--}}
                    {{--</a>--}}
                    <li class="site-menu-item">
                        <a class="group-item" href="{{ route('towerinsulatormountingkind.index') }}">
                            <span class="icon icon-sidebar-5"></span>
                            <span>Towerinsulatormountingkind (способы крепления изоляторов на опорах)</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="group-item" href="{{ route('groundingmaterialkind.index') }}">
                            <span class="icon icon-sidebar-5"></span>
                            <span>Groundingmaterialkind (материалы заземлений на опорах)</span>
                        </a>
                    </li>

                @endif

                {{-- настройка --}}
                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <li class="site-menu-category">Настройка</li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('admin_setting.index') }}">
                            <i class="site-menu-icon md-settings" aria-hidden="true"></i>
                            <span class="site-menu-title">Параметры</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('phpinfo') }}">
                            <i class="site-menu-icon md-pin-help" aria-hidden="true"></i>
                            <span class="site-menu-title">Версия PHP (phpinfo)</span>
                        </a>
                    </li>
                @endif

                {{-- отладка API --}}
                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <li class="site-menu-category">Отладка API</li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('apiInstruction') }}">
                            <i class="site-menu-icon md-help" aria-hidden="true"></i>
                            <span class="site-menu-title">Инструкция</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('apiQueries') }}">
                            <i class="site-menu-icon md-swap" aria-hidden="true"></i>
                            <span class="site-menu-title">Запросы</span>
                        </a>
                    </li>
                @endif

                {{-- импорт --}}
                @if (
                Auth::user()->isVendor() or
                Auth::user()->isAdmin() or
                Auth::user()->isManager() or
                Auth::user()->isDispatcher() or
                Auth::user()->isOperator() or
                Auth::user()->isMaster() or
                Auth::user()->isWorking()
                )
                    <li class="site-menu-category">Импорт</li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('tables.firebird') }}">
                            <i class="site-menu-icon md-view-web" aria-hidden="true"></i>
                            <span class="site-menu-title">Таблицы Firebird (646 шт.)</span>{{-- константа!!! --}}
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('tables.mysql') }}">
                            <i class="site-menu-icon md-grid" aria-hidden="true"></i>
                            <span class="site-menu-title">Таблицы MySQL (120 шт.)</span>{{-- константа!!! --}}
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a class="animsition-link" href="{{ route('tables.for-imports') }}">
                            <i class="site-menu-icon md-code" aria-hidden="true"></i>
                            <span class="site-menu-title">Для импорта (62 шт.)</span>{{-- константа!!! --}}
                        </a>
                    </li>
                @endif
            @endif

        </ul>
    </div>
</div>

<style>
    .app-nav-group-dropdown-level-2 .group-caption,
    .app-nav-group-dropdown-level-2 .group-items .group-item {
        padding-left: 45px !important;
    }

    .app-nav-group-dropdown-level-2:not(.opened) .group-caption .collapse-button {
        transform: rotate(360deg);
    }

    .app-nav-group-dropdown-level-2.opened .group-caption .collapse-button {
        transform: rotate(180deg);
    }
</style>
