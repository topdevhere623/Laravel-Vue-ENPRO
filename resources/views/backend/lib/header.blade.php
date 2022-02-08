{{-- верхний хеадер - админка --}}
<div class="header">
    <div class="left">
        <a cl href="{{  route('admin') }}">
            <img class="logo" src="/public/uploads/new-images/logo-white.png" title="">
        </a>

        <div class="nav">
            @if (isset($allmap))
                <a href="{{ route('mainMap') }}" class="nav-link">Редактор</a>
                <a href="{{  route('admin') }}" class="nav-link active" >Общая схема сети</a>
            @else
                <a href="{{ route('mainMap') }}" class="nav-link active">Редактор</a>
                <a href="{{  route('admin') }}" class="nav-link" >Общая схема сети</a>
            @endif
        </div>
    </div>
    <div class="right">
        <a href="{{ route('admin_user_role.index') }}" class="link">
            <span class="icon icon-user"></span>
            {{ Auth::user()->username ?? ''}} ({{ Auth::user()->role[0]->name }})
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <a href="{{ route('logout') }}" class="link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="icon icon-exit"></span>
                Выйти
            </a>
        </form>
    </div>
</div>
