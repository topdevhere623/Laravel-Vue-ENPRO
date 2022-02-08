// фильтр обьектов карты

// ------------------------------------------------------------------
// фильтр по типу из всех обьектов карты (по полю type: substation, tower, text и прочее)
export const serviceFilterObjectsOnType = (getMapObjects, getType) => {

    let myReturn = null;

    if (typeof (getMapObjects) !== 'undefined' && getMapObjects !== null && (getMapObjects).length > 0) {

        myReturn = getMapObjects.filter(
            item => (item.type === getType)
        );
    }

    // возвращаемый параметр
    return myReturn;
}

// ------------------------------------------------------------------
// фильтр по типу карты из всех обьектов карты (по полю mapType: placemark, polyline, text)
export const serviceFilterObjectsOnMapType = (getMapObjects, getMapType) => {

    let myReturn = null;

    if (typeof (getMapObjects) !== 'undefined' && getMapObjects !== null && (getMapObjects).length > 0) {

        myReturn = getMapObjects.filter(
            item => (item.mapType === getMapType)
        );
    }

    // возвращаемый параметр
    return myReturn;
}

// ------------------------------------------------------------------
// фильтр текущий или нетекущий из всех обьектов карты (по полю current)
export const serviceFilterObjectsOnCurrent = (getMapObjects) => {

    let myReturn = null;

    if (typeof (getMapObjects) !== 'undefined' && getMapObjects !== null && (getMapObjects).length > 0) {

        myReturn = getMapObjects.filter(
            item => (item.current)
        );
    }

    // возвращаемый параметр
    return myReturn;
}
