<script type="text/javascript">

    // значек загрузки Ajax

    // ---------------------------------------------------------------
    // показывать значек loading во время работы ajax
    $(document).ready(function () {

        // в начале работы ajax
        $(document).ajaxStart(function () {
            // индикатор ожидания включить
            funAjaxLoadingImg(true);
        });

        // при завершении работы ajax
        $(document).ajaxComplete(function () {
            // индикатор ожидания ajax выключить
            funAjaxLoadingImg(false);
        });

    });

    // ---------------------------------------------------------------
    // показывать/скрыть значек loading
    function funAjaxLoadingImg(getRegim) {

        if (getRegim) {
            $("#loading").css("display", "block");
        }
        else {
            $("#loading").css("display", "none");
        }
    }

</script>