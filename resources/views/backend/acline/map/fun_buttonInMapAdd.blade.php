<script type="text/javascript">

    // ---------------------------------------------------------------
    // кнопки на карте
    function funButtonInMapAdd() {

        // имеет ли права
        if (!mmUserHasEditRights) return;

        // ---------------------------------------------------------------
        // кнопка очистить карту
        let myButtonMapClear =
            new ymaps.control.Button(
                {
                    data: {
                        content: "<i class='icon ti-file'></i>",
                        title: "Очистить карту",
                    },
                    options: {
                        maxWidth: 50,
                        selectOnClick: false, // чтоб не залипала, как чекбокс
                    }
                }
            );
        myButtonMapClear.events
            .add(
                'press',
                function () {
                    // вопрос Пользователю
                    if (!confirm('Вы точно хотите очистить всю карту?')) return;
                    // очистить карту и глобальные переменные, которые можно обнулять
                    funMapClear();
                    // записать шаг в историю
                    funHistoreSave();
                }
            );
        myMap.controls.add(myButtonMapClear, {
            float: "left"
        });

        // ---------------------------------------------------------------
        // кнопка автомасштаб карты
        let myButtonAutoScale =
            new ymaps.control.Button(
                {
                    data: {
                        content: "<i class='icon ti-fullscreen'></i>",
                        title: "Автомасштаб карты",
                    },
                    options: {
                        maxWidth: 50,
                        selectOnClick: false, // чтоб не залипала, как чекбокс
                    }
                }
            );
        myButtonAutoScale.events
            .add(
                'press',
                function () {
                    // автомасштаб карты
                    funAutoScale();
                }
            );
        myMap.controls.add(myButtonAutoScale, {
            float: "left"
        });

        // ---------------------------------------------------------------
        // кнопка предыдущая точка
        let myButtonBack =
            new ymaps.control.Button(
                {
                    data: {
                        content: "<i class='icon md-long-arrow-right'></i>",
                        title: "Предыдущая точка",
                    },
                    options: {
                        maxWidth: 50,
                        selectOnClick: false, // чтоб не залипала, как чекбокс
                    }
                }
            );
        myButtonBack.events
            .add(
                'press',
                function () {
                    // получить предыдущий/следующий обьект
                    funBackForwardObject('forward');
                }
            );
        myMap.controls.add(myButtonBack, {
            float: "left"
        });

        // ---------------------------------------------------------------
        // кнопка переход к активному обьекту
        let myButtonGoActiveObject =
            new ymaps.control.Button(
                {
                    data: {
                        content: "<i class='icon ti-target'></i>",
                        title: "Перейти к активной точке",
                    },
                    options: {
                        maxWidth: 50,
                        selectOnClick: false, // чтоб не залипала, как чекбокс
                    }
                }
            );
        myButtonGoActiveObject.events
            .add(
                'press',
                function () {
                    // "перелет" по карте
                    funFlyTo('mapID', mmCurrentPlacemarkMapID, false);
                }
            );
        myMap.controls.add(myButtonGoActiveObject, {
            float: "left"
        });

        // ---------------------------------------------------------------
        // кнопка следующая точка
        let myButtonForward =
            new ymaps.control.Button(
                {
                    data: {
                        content: "<i class='icon md-long-arrow-left'></i>",
                        title: "Следующая точка",
                    },
                    options: {
                        maxWidth: 50,
                        selectOnClick: false, // чтоб не залипала, как чекбокс
                    }
                }
            );
        myButtonForward.events
            .add(
                'press',
                function () {
                    // получить предыдущий/следующий обьект
                    funBackForwardObject('back');
                }
            );
        myMap.controls.add(myButtonForward, {
            float: "left"
        });

        // ---------------------------------------------------------------
        // кнопка шаг вперед
        let myButtonRedo =
            new ymaps.control.Button(
                {
                    data: {
                        content: "<i class='icon md-redo'></i>",
                        title: "Шаг вперед",
                    },
                    options: {
                        maxWidth: 50,
                        selectOnClick: false, // чтоб не залипала, как чекбокс
                    }
                }
            );
        myButtonRedo.events
            .add(
                'press',
                function () {
                    // получить предыдущий/следующий обьект
                    funHistoreUndoRedo('redo');
                }
            );
        myMap.controls.add(myButtonRedo, {
            float: "left"
        });

        // ---------------------------------------------------------------
        // кнопка шаг назад
        let myButtonUndo =
            new ymaps.control.Button(
                {
                    data: {
                        content: "<i class='icon md-undo'></i>",
                        title: "Шаг назад",
                    },
                    options: {
                        maxWidth: 50,
                        selectOnClick: false, // чтоб не залипала, как чекбокс
                    }
                }
            );
        myButtonUndo.events
            .add(
                'press',
                function () {
                    // получить предыдущий/следующий обьект
                    funHistoreUndoRedo('undo');
                }
            );
        myMap.controls.add(myButtonUndo, {
            float: "left"
        });
    }

</script>
