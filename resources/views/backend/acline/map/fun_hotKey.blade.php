<script type="text/javascript">

    // горячие клавиши

    // ---------------------------------------------------------------
    // закрытие вкладки в браузере
    window.onbeforeunload = function () {
        if (funStateChange()) {
            return 'У Вас есть несохраненные данные! Вы уверены, что хотите закрыть страницу?';
        }
    };

    // ---------------------------------------------------------------
    // отслеживать, что нажата Ctrl
    let CtrlDown = false;
    let setCtrlDown = function (event) {
        if (event.keyCode === 17 || event.charCode === 17) {
            window.CtrlDown = true;
        }
    };

    let setCtrlUp = function (event) {
        if (event.keyCode === 17 || event.charCode === 17) {
            window.CtrlDown = false;
        }
    };

    window.addEventListener ? document.addEventListener('keydown', setCtrlDown) : document.attachEvent('keydown', setCtrlDown);
    window.addEventListener ? document.addEventListener('keyup', setCtrlUp) : document.attachEvent('keyup', setCtrlUp);

    // ---------------------------------------------------------------
    // отслеживать, что нажата Shift
    let ShiftDown = false;
    let setShiftDown = function (event) {
        if (event.keyCode === 16 || event.charCode === 16) {
            window.ShiftDown = true;
        }
    };

    let setShiftUp = function (event) {
        if (event.keyCode === 16 || event.charCode === 16) {
            window.ShiftDown = false;
        }
    };

    window.addEventListener ? document.addEventListener('keydown', setShiftDown) : document.attachEvent('keydown', setShiftDown);
    window.addEventListener ? document.addEventListener('keyup', setShiftUp) : document.attachEvent('keyup', setShiftUp);

    // ---------------------------------------------------------------
    // отслеживать, что нажата Alt
    let AltDown = false;
    let setAltDown = function (event) {
        if (event.keyCode === 18 || event.charCode === 18) {
            window.setAltDown = true;
        }
    };

    let setAltUp = function (event) {
        if (event.keyCode === 18 || event.charCode === 18) {
            window.setAltDown = false;
        }
    };

    window.addEventListener ? document.addEventListener('keydown', setAltDown) : document.attachEvent('keydown', setAltDown);
    window.addEventListener ? document.addEventListener('keyup', setAltUp) : document.attachEvent('keyup', setAltUp);

</script>
