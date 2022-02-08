<script type="text/javascript">

    // выпадающие списки доп.оборудования (мультиселект)

    // ---------------------------------------------------------------
    // мультиселект
    let expanded = {
        "1": false,
        "2": false,
        "3": false,
    };
    let $body = $('body');

    $body.on('click', '.js-show-checkboxes1', function () {
        funJSshowChecboxes("1");
    });
    $body.on('click', '.js-show-checkboxes2', function () {
        funJSshowChecboxes("2");
    });
    $body.on('click', '.js-show-checkboxes3', function () {
        funJSshowChecboxes("3");
    });

    // ---------------------------------------------------------------
    function funJSshowChecboxes(getN) {
        let checkboxes = document.getElementById("checkboxes" + getN);
        if (!expanded[getN]) {
            checkboxes.style.display = "block";
            $(this).addClass('open');
            expanded[getN] = true;
        } else {
            checkboxes.style.display = "none";
            $(this).removeClass('open');
            expanded[getN] = false;
        }
    }

    // ---------------------------------------------------------------
    $body.on('click', function (e) {
        if (!e.target.closest('.multiselect') && (expanded[1] || expanded[2] || expanded[3])) {

            $("#checkboxes1").css("display", "none");
            $("#checkboxes2").css("display", "none");
            $("#checkboxes3").css("display", "none");

            $('.js-show-checkboxes1').removeClass('open');
            $('.js-show-checkboxes2').removeClass('open');
            $('.js-show-checkboxes3').removeClass('open');

            expanded[1] = false;
            expanded[2] = false;
            expanded[3] = false;
        }
    });

</script>

<style>
    /* --------------------------------------------------------------- */
    /* стили ещ есть в public/assets/backend/css/main.css */

    /* мультиселект */
    .multiselect {
        width: 100%;
    }

    .selectBox {
        position: relative;
    }

    .overSelect {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }

    #checkboxes1, #checkboxes2, #checkboxes3 {
        display: none;
        border: 1px #dadada solid;
        padding: 10px 5px 0 10px;
    }

    #checkboxes1 label {
        display: block;
    }

    #checkboxes2 label {
        display: block;
    }

    #checkboxes3 label {
        display: block;
    }

    #checkboxes1 label:hover {
        background-color: #1e90ff;
    }

    #checkboxes2 label:hover {
        background-color: #1e90ff;
    }

    #checkboxes3 label:hover {
        background-color: #1e90ff;
    }
</style>
