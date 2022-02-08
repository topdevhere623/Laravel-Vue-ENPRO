<script type="text/javascript">

    // история дейсвий Пользователя

    // ---------------------------------------------------------------
    // шаг назад/вперед
    function funHistoreUndoRedo(getRegim) {

        if (mmHistoreArr.length === 0) {
            toastr.warning('Историй действий, к сожалению, еще нет...');
            return;
        }

        let myHistore = null;
        if (getRegim === 'undo') {

            myHistore = mmHistoreArr.find(item => item.step === mmHistoreStep - 1);
            if (typeof(myHistore) !== 'undefined') {
                mmHistoreStep = mmHistoreStep - 1;
                toastr.success('Шаг назад...');
                //console.log("Шаг назад. Он стал: " + mmHistoreStep);
            }
            else {
                toastr.warning('Шага назад, к сожалению, нет...');
                return;
            }
        }
        else {
            myHistore = mmHistoreArr.find(item => item.step === mmHistoreStep + 1);
            if (typeof(myHistore) !== 'undefined') {
                mmHistoreStep++;
                toastr.success('Шаг вперед...');
                //console.log("Шаг вперед. Он стал: " + mmHistoreStep);
            }
            else {
                toastr.warning('Шага вперед, к сожалению, нет...');
                return;
            }
        }

        // добавить список обьектов на карту
        funAddObjectsToMapFromArray(myHistore['objects']);

        // console.log("Вывел из истории:");
        // console.log("Шаг: " + mmHistoreStep);
        // console.log("Массив истории:");
        // console.log(mmHistoreArr);
        // console.log("Выводил на карту массив:");
        // console.log(myHistore['objects']);
    }

    // ---------------------------------------------------------------
    // записать шаг в историю (ограниченное значение)
    function funHistoreSave() {

        let myMaxHistoreStep = 7;
        let myHistoreLen = mmHistoreArr.length - 1;

        // проверка, не превысило ли кол-во шагов
        if (myHistoreLen >= myMaxHistoreStep) {
            // да, кол-во шагов превысило максимальное значение
            let mmHistoreArrNew = [];
            for (let i = 1; i <= myHistoreLen - 1; i++) {
                let myObject = JSON.parse(JSON.stringify(mmHistoreArr[i]));
                mmHistoreArrNew.push({
                    'step': myObject['step'],
                    'objects': myObject['objects'],
                });
            }
            mmHistoreArr = [];
            mmHistoreArr = JSON.parse(JSON.stringify(mmHistoreArrNew));
        }

        // записать текущее состояние
        mmHistoreStep++;
        mmHistoreArr.push(
            {
                'step': mmHistoreStep,
                'objects': JSON.parse(JSON.stringify(mmObjects)),
            });

        // console.log("Сохранил в истории:");
        // console.log("Шаг: " + mmHistoreStep);
        // console.log("Массив истории:");
        // console.log(mmHistoreArr);
        // console.log("Массив для сохранения:");
        // console.log(mmObjects);
    }

</script>
