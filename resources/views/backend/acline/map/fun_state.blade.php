<script type="text/javascript">

    // состояние - первоначальные обьекты при загрузке или сохранении

    // ---------------------------------------------------------------
    // функция сохранения состояния
    function funStateSave() {

        // массив точек и то,что можно поменять без кнопки Применить
        mmStateOld = new Map([
            ['mmObjects', JSON.parse(JSON.stringify(mmObjects))],
            ['mmAclineName', String($('#iRBAclineName').val())],
            ['mmAclineBaseVoltage', Number($('#sRBAclineBaseVoltage').val())],
            ['mmAclineStatus', Number($('#sRBAclineStatus').val())],
        ]);
    }

    // ---------------------------------------------------------------
    // функция проверка состояния - изменилось ли что с момента последней загрузки или сохранения
    function funStateChange() {
        let myChange = false;

        if (!funCompareArr(mmStateOld.get('mmObjects'), mmObjects)) {
            myChange = true;
            console.log("Есть изменения в обьектах карты");
        }
        if (String($('#iRBAclineName').val()) !== mmStateOld.get('mmAclineName')) {
            myChange = true;
            console.log("Есть изменения в имени карты");
        }
        if (Number($('#sRBAclineBaseVoltage').val()) !== mmStateOld.get('mmAclineBaseVoltage')) {
            myChange = true;
            console.log("Есть изменения в классе напряжения карты");
        }
        if (Number($('#sRBAclineStatus').val()) !== mmStateOld.get('mmAclineStatus')) {
            myChange = true;
            console.log("Есть изменения в статусе карты");
        }

        // возвращаемый параметр - false, если все идентично, изменений не было. true - изменения были
        return myChange;
    }

    // ---------------------------------------------------------------
    // сравнение двух массивов
    funCompareArr = function (a1, a2) {
        return a1.length === a2.length && a1.length > 0 && a1.every((v, i) => funCompareObject(v, a2[i]));
    }

    // ---------------------------------------------------------------
    // сравнение двух обьектов с их полями
    funCompareObject = function (a1, a2) {

        let myHaveChange = false;
        let myArrException = ['isActive', 'aclinesObject', 'hint'];
        for (key in a1) {

            // поля, которые не нужно сравнивать. Например "isActive"
            if (myArrException.includes(key)) {
                // новая иттерация цикла
                continue;
            }

            if (Array.isArray(a1[key]) || Array.isArray(a2[key])) {
                // сравнение двух массивов - рекурсия
                myHaveChange = funCompareArr(a1, a2);
            } else {

                if (a1[key] !== a2[key]) {
                    myHaveChange = true;
                    console.log("Есть разница:");
                    console.log(key);
                    console.log(a1[key]);
                    console.log(a2[key]);
                    break;
                } else {
                    //console.log("OK:");
                    //console.log(key);
                }
            }
        }
        return !myHaveChange;
    }

    // ---------------------------------------------------------------
    // разница в двух массивах
    diff = function (a1, a2) {
        return a1.filter(i => a2.indexOf(i) < 0)
            .concat(a2.filter(i => a1.indexOf(i) < 0));
    }
</script>
