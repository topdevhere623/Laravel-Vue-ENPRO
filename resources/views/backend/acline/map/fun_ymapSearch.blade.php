<script type="text/javascript">

    // ---------------------------------------------------------------
    // провайдер данных для элемента управления ymaps.control.SearchControl
    // осуществляет поиск геообъектов в по массиву points
    // реализует интерфейс IGeocodeProvider
    function MapSearchProvider(points) {
        this.points = mmObjects;
    }

    // ---------------------------------------------------------------
    // провайдер ищет по имени стандартным методом String.ptototype.indexOf
    MapSearchProvider.prototype.geocode = function (request, options) {

        var deferred = new ymaps.vow.defer(),
            geoObjects = new ymaps.GeoObjectCollection(),
            // сколько результатов нужно пропустить.
            offset = options.skip || 0,
            // количество возвращаемых результатов.
            limit = options.results || 20;

        var points = [];
        // поиск в имени точки
        for (var i = 0, l = this.points.length; i < l; i++) {
            //console.log(this.points[i]);
            //console.log(this.points[i].viewName);
            //console.log(this.points[i].deleted);
            if (this.points[i].mapType === 'placemark' && this.points[i].deleted === false) {
                var point = this.points[i];
                if (point.viewName.toLowerCase().indexOf(request.toLowerCase()) != -1) {
                    points.push(point);
                }
            }
        }
        // при формировании ответа можно учитывать offset и limit.
        points = points.splice(offset, limit);
        // добавляем точки в результирующую коллекцию.
        for (var i = 0, l = points.length; i < l; i++) {

            var point = points[i],
                coords = mmCollection.get(point.mapID).geometry.getCoordinates(),
                text = point.viewName;

            geoObjects.add(new ymaps.Placemark(coords, {
                name: text, // + ' name',
                //description: text + ' description',
                balloonContentBody: '<p>' + text + '</p>',
                boundedBy: [coords, coords]
            }));
        }

        deferred.resolve({
            // геообъекты поисковой выдачи
            geoObjects: geoObjects,
            // метаинформация ответа
            metaData: {
                geocoder: {
                    // строка обработанного запроса
                    request: request,
                    // кол-во найденных результатов.
                    found: geoObjects.getLength(),
                    // кол-во возвращенных результатов
                    results: limit,
                    // кол-во пропущенных результатов
                    skip: offset
                }
            }
        });

        // возвращаем объекты
        return deferred.promise();
    };

</script>