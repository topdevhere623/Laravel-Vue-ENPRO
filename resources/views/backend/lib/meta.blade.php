{{-- мета-данные и стили css --}}

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="author" content="www.programmist.uz">
<meta name="robots" content="noindex, nofollow"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

{{-- мета-данные --}}
<title>@yield('title') | Админ-панель</title>

{{-- токен --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- фавикон --}}
<link rel="shortcut icon" type="image/png" href="/public/uploads/favicon/favicon.ico"/>

{{-- подгружаемые стили общие --}}
{{-- Stylesheets --}}
<link rel="stylesheet" href="/public/assets/backend/global/css/bootstrap.min.css">
<link rel="stylesheet" href="/public/assets/backend/global/css/bootstrap-extend.min.css">
<link rel="stylesheet" href="/public/assets/backend/css/site.min.css">

{{-- Plugins --}}
<link rel="stylesheet" href="/public/assets/backend/global/vendor/animsition/animsition.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/asscrollable/asScrollable.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/switchery/switchery.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/intro-js/introjs.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/slidepanel/slidePanel.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/waves/waves.css">
<link rel="stylesheet" href="/public/assets/backend/examples/css/tables/basic.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="/public/assets/backend/global/vendor/bootstrap-markdown/bootstrap-markdown.js"></script>
<script src="/public/assets/backend/global/vendor/marked/marked.js"></script>
<script src="/public/assets/backend/global/vendor/to-markdown/to-markdown.js"></script>
<link rel="stylesheet" href="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/dropify/dropify.css">
<link rel="stylesheet" href="/public/assets/backend/libs/summernote/summernote-lite.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/timepicker/jquery-timepicker.css">
<link rel="stylesheet" href="/public/assets/backend/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css">

{{-- Fonts --}}
<link rel="stylesheet" href="/public/assets/backend/global/fonts/material-design/material-design.min.css">
<link rel="stylesheet" href="/public/assets/backend/global/fonts/font-awesome/font-awesome.min.css">
<link rel="stylesheet" href="/public/assets/backend/global/fonts/themify/themify.min.css">
<link rel="stylesheet" href="/public/assets/backend/global/fonts/brand-icons/brand-icons.min.css">
<link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
<link rel="stylesheet" href="/public/assets/backend/global/fonts/web-icons/web-icons.min.css">

<!--[if lt IE 9]>
<script src="/public/assets/backend/global/vendor/html5shiv/html5shiv.min.js"></script>
<![endif]-->

<!--[if lt IE 10]>
<script src="/public/assets/backend/global/vendor/media-match/media.match.min.js"></script>
<script src="/public/assets/backend/global/vendor/respond/respond.min.js"></script>
<![endif]-->

{{-- Scripts --}}
<script src="/public/assets/backend/global/vendor/breakpoints/breakpoints.js"></script>
<script>
    Breakpoints();
</script>

{{-- подгружаемые стили мои --}}
<link rel="stylesheet" href="/public/assets/backend/css/selectric.css">
<link rel="stylesheet" href="/public/assets/backend/css/mystyle.css?<?php echo time(); ?>">
<link rel="stylesheet" href="/public/assets/backend/css/main.css?<?php echo time(); ?>">
<link rel="stylesheet" href="/public/assets/common/libs/toastr/toastr.min.css">

{{-- vue --}}
<script src="{{ asset('js/app.js') }}" defer></script>
<link ref="stylesheet" href="{{ asset('css/app.css') }}">
