<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="author" content="www.programmist.uz">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- мета-данные --}}
    <title>@yield('title') | Админ-панель</title>

    {{-- Fonts --}}
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,700,700italic' rel='stylesheet'
          type='text/css'>

    {{-- CSS - theme --}}
    <link rel="stylesheet" type="text/css" href="/public/assets/login/skin/default_skin/css/theme.css">

    {{-- CSS - allcp forms --}}
    <link rel="stylesheet" type="text/css" href="/public/assets/login/allcp/forms/css/forms.css">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="/public/uploads/favicon/favicon1.png"/>

{{-- IE8 HTML5 support  --}}
<!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="utility-page sb-l-c sb-r-c">

<div id="main" class="animated fadeIn">
    <section id="content_wrapper">

        <div id="canvas-wrapper">
            <canvas id="demo-canvas"></canvas>
        </div>

        {{-- область для вывода контента --}}
        @yield('content')

    </section>
</div>

{{-- Scripts --}}
{{-- jQuery --}}
<script src="/public/assets/login/js/jquery/jquery-1.11.3.min.js"></script>
<script src="/public/assets/login/js/jquery/jquery_ui/jquery-ui.min.js"></script>
{{-- CanvasBG JS --}}
<script src="/public/assets/login/js/plugins/canvasbg/canvasbg.js"></script>
{{-- Theme Scripts --}}
<script src="/public/assets/login/js/utility/utility.js"></script>
<script src="/public/assets/login/js/demo/demo.js"></script>
<script src="/public/assets/login/js/main.js"></script>
{{-- Page JS --}}
<script type="text/javascript">
    jQuery(document).ready(function () {
        "use strict";
        // Init Theme Core
        Core.init();
        // Init Demo JS
        Demo.init();
        // Init CanvasBG
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 5,
                y: window.innerHeight / 10
            }
        });
    });
</script>

</body>
</html>
