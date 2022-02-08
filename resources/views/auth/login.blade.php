{{-- главная страница - админка --}}

{{-- лайоут --}}
@extends("auth.layouts.login")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Вход
@endsection

{{-- секция контента --}}
@section("content")

    <section id="content">

        {{-- Login Form --}}
        <div class="allcp-form theme-primary mw320" id="login">

            {{-- логотип --}}
            <div class="text-center mb20">
                <img src="/public/assets/login/img/logo_login_form.png" class="img-responsive" alt=""/></div>
            <div class="panel mw320">

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="panel-body pn mv10">

                        {{-- email --}}
                        <div class="section">
                            <label for="email" class="field prepend-icon">

                                <input type="text" class="gui-input @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" id="email" required autocomplete="email" autofocus placeholder="Имя пользователя (или E-mail)">

                                <label for="username" class="field-icon">
                                    <i class="fa fa-user"></i>
                                </label>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </label>
                        </div>

                        {{-- password --}}
                        <div class="section">
                            <label for="password" class="field prepend-icon">

                                <input type="password" class="gui-input @error('password') is-invalid @enderror" name="password" id="password" required autocomplete="current-password" placeholder="пароль">

                                <label for="password" class="field-icon">
                                    <i class="fa fa-lock"></i>
                                </label>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </label>
                        </div>

                        {{-- remember --}}
                        <div class="section">

                            <div class="bs-component pull-left pt5">
                                <div class="radio-custom radio-primary mb5 lh25">
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember">Запомнить меня</label>
                                </div>
                            </div>

                            {{-- кнлпка Войти --}}
                            <button type="submit" class="btn btn-bordered btn-primary pull-right">Войти</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection


