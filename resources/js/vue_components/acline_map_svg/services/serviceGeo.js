// карта Яндекс и географические координаты

// ------------------------------------------------------------------
// расчет координат верхнего левого и нижнего правого углов
export const serviceCoordsTLBR = (getSvgWidthOriginalPx, getSvgHeightOriginalPx, getSvgScale) => {

    // позиция верхнего левого угла карты
    let my_0_px = new YMaps.Point(0, 0); // x=0, y=0
    let my_0_geo = myMap.converter.mapPixelsToCoordinates(my_0_px); // __lng: 61.37724084779621, __lat: 57.38102767343352

    // позиция оверлея
    let my_overlay_px = myMap.converter
        .coordinatesToMapPixels(
            new YMaps.GeoPoint(
                myMapDefault.centerLong,
                myMapDefault.centerLat
            )
        )
        .moveBy(my_0_px);

    // TL
    let my_TL_px = new YMaps.Point(
        my_overlay_px.x - 300,
        my_overlay_px.y - 300
    );
    let my_TL_geo = myMap.converter.mapPixelsToCoordinates(my_TL_px);

    // BR
    let my_BR_px = new YMaps.Point(
        my_overlay_px.x + 300,
        my_overlay_px.y + 300
    );
    let my_BR_geo = myMap.converter.mapPixelsToCoordinates(my_BR_px);

    // пропорции для расчета координата
    let myMaxLat = my_TL_geo.__lat;
    let myMinLat = my_BR_geo.__lat;
    let myMaxLong = my_BR_geo.__lng;
    let myMinLong = my_TL_geo.__lng;

    // отразить по горизонтали (из-за того, что отсчет по Y в SVG идет вниз - иначе схема будет вверх-ногами)
    let temp = myMaxLat;
    myMaxLat = myMinLat;
    myMinLat = temp;

    let myWidthMax = getSvgWidthOriginalPx * getSvgScale;
    let myHeightMax = getSvgHeightOriginalPx * getSvgScale;

    let myPrX = (myMaxLong - myMinLong) / myWidthMax;
    let myPrY = (myMaxLat - myMinLat) / myHeightMax;
    let mySvgMinLat = myMinLat;
    let mySvgMinLong = myMinLong;

    // возвращаемый параметр
    let myReturn = new Map([
        ['prX', myPrX],
        ['prY', myPrY],
        ['svgMinLat', mySvgMinLat],
        ['svgMinLong', mySvgMinLong],
    ]);
    return myReturn;
}

// ------------------------------------------------------------------
// смещение центра карты и оверлея
export const serviceMapChangeCoordsCenter = () => {

    // изменить центр на карте
    myMap.setCenter(
        new YMaps.GeoPoint(
            myMapDefault.centerLong,
            myMapDefault.centerLat
        )
    );

    // принудительное обновление оверлея при обновлении карты
    myArea.onMapUpdate(
        new YMaps.GeoPoint(
            myMapDefault.centerLong,
            myMapDefault.centerLat
        )
    );

    // для отладки
    if (false) {
        // создание метки - новый центр
        let myPlacemark = new YMaps.Placemark(
            new YMaps.GeoPoint(
                this.mapAttributes.settings.centerGeo[1],
                this.mapAttributes.settings.centerGeo[0]
            ),
            {}
        );
        myPlacemark.name = "New Center";
        myMap.addOverlay(myPlacemark);
    }
}
