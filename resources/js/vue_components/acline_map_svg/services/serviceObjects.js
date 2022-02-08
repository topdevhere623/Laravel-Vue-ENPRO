// обьекты карты

// ---------------------------------------------------------------
// видимое имя для точки от типа
export const serviceGetPlacemarkViewName = (getObject) => {

    let myViewName = 'не определено';

    if (typeof (getObject) !== 'undefined' && getObject !== null) {
        // тип обьекта
        switch (getObject.type) {
            case "substation":
                myViewName = getObject.name; // имя
                break;
            case "tower":
                myViewName = getObject.localName; // диспетчерский номер
                break;
            case "customer":
                myViewName = getObject.address; // адрес
                break;
            default:
                myViewName = getObject.name; // имя
        }
    }

    // возвращаемый параметр
    return myViewName;
}

// ---------------------------------------------------------------
// получить данные вершин (начало / конец)
export const serviceGetPolylineStartEnd = (getObject, getObjects) => {

    let myReturn, myStartObject, myEndObject;

    if (typeof (getObject) !== 'undefined' && getObject !== null && (getObject.type === '701' || getObject.type === '702')) {
        // это линия

        // начало
        let myStartObject = getObjects.filter(function (item) {
            return (item.mapID === getObject.startMapID);
        });
        myStartObject = (myStartObject.length) > 0 ? myStartObject[0] : null;

        // конец
        let myEndObject = getObjects.filter(function (item) {
            return (item.mapID === getObject.endMapID);
        });
        myEndObject = (myEndObject.length) > 0 ? myEndObject[0] : null;

        // возвращаемый параметр
        myReturn = new Map([
            ['startMapID', myStartObject.mapID],
            ['startX', myStartObject.x],
            ['startY', myStartObject.y],
            ['startName', serviceGetPlacemarkViewName(myStartObject)],
            ['endMapID', myEndObject.mapID],
            ['endX', myEndObject.x],
            ['endY', myEndObject.y],
            ['endName', serviceGetPlacemarkViewName(myEndObject)],
        ]);
    }

    // возвращаемый параметр
    return myReturn;
}
