<script type="text/javascript">

    // разбор url-строки и действия после

    // прочитать строку url и осуществить "перелет" по карте, если требуется
    function funGetUrlAndFly() {
        // прочитать строку url
        let myFlyToMapID = funGetUrlVars()["fly_id"];
        let myFlyToLat = funGetUrlVars()["fly_lat"];
        let myFlyToLong = funGetUrlVars()["fly_long"];

        // анализ переданных get-параметров
        if (typeof (myFlyToMapID) !== 'undefined') {
            // id обьекта передали

            // проверить, есть ли такое id в массиве обьектов
            if (mmObjects.length > 0) {
                for (let myObjectN = 0; myObjectN < mmObjects.length; myObjectN++) {

                    // текущий обьект
                    let myCurrentObject = mmObjects[myObjectN];

                    if (myCurrentObject.mapID === Number(myFlyToMapID) && myCurrentObject.mapType === 'placemark' && myCurrentObject.deleted === false) {
                        // обьект есть
                        // "перелет" по карте
                        funFlyTo('mapID', myFlyToMapID, true);
                        // переопределить ID текущей и прошлой точки, показать детали, подсветить активную, создать иконку svg
                        funChangeLastCurrentPlacemark(myFlyToMapID);
                    }
                }
            }
        } else {
            if (typeof (myFlyToLat) !== 'undefined' || typeof (myFlyToLong) !== 'undefined') {
                // координаты lat, long передали
                // "перелет" по карте
                funFlyTo('coords', [Number(myFlyToLat), Number(myFlyToLong)], true);
            }
        }
    }

    // ---------------------------------------------------------------
    // прочитать строку url
    function funGetUrlVars() {

        let vars = {};
        let parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });

        // возвращаемый параметр
        return vars;
    }

</script>
