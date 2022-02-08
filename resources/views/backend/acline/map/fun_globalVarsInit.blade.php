<script type="text/javascript">

    // иницилизация глобальных переменных

    // ---------------------------------------------------------------
    // иницилизация глобальных переменных, которые нельзя обнулять
    function funGlobalVarsCannotResetInit() {

        mmHistoreStep = -1; // массив истории и ее текущий шаг
        mmHistoreArr = [];
        mmChMapViewNearObjects = 0; // показывать ли другие обьекты рядом в видимой области карты
        mmChMapViewLamp = 1; // показывать ли линии освещения
        mmChMapViewDoubleAcline = 1; // показывать ли линии совместного подвеса
        mmChMapViewFix = 1; // карту зафиксировать или нет
        mmChMapViewPolylineEdit = 0; // редактировать кабельные линии можно или нет
        mmSVGWidthMax = 1280; // для отрисовки SVG поверх карты - размер холста // 1920
        mmSVGHeightMax = 1024; // для отрисовки SVG поверх карты - размер холста  // 1080
        mmPathTowerImages = '/public/uploads/models/map/photos/'; // путь к прикрепляемым изображениям опор
        mmStateOld = new Map([ // состояние - первоначальные обьекты при загрузке или сохранении
            ['mmObjects', null],
            ['mmAclineName', null],
            ['mmAclineBaseVoltage', null],
            ['mmAclineStatus', null],
        ]);

        mmUserHasEditRights = {{ $userHasEditRights }};
        console.log('mmUserHasEditRights = ' + mmUserHasEditRights);

        // свойства обьектов
        mmObjectsProperties = {
            'tower': {
                'name': 'Опора',
                'color': '#FFFFFF', // #373435 - был черный цвет
                'colorActive': '#ed3237',
            },
            'substation': {
                'name': 'ТП',
                'color': '#ffffff', // #373435 - был черный цвет
                'colorActive': '#ed3237',
            },
            'customer': {
                'name': 'Потребитель',
                'color': '#FF8C00',
                'colorActive': '#ed3237',
            },
            701: {
                'name': 'Пролет',
                'strokeColor': '#EBFF03', // '#1e98ff' - был синий цвет
                'strokeColorActive': '#ed4543',
                'strokeWidth': 3,
                'strokeStyle-style': 'solid', // сплошная
                'strokeStyle-offset': 10,
            },
            702: {
                'name': 'Кабельный пролет',
                'strokeColor': '#EBFF03', // '#1e98ff' - был синий цвет
                'strokeColorActive': '#ed4543',
                'strokeWidth': 3,
                'strokeStyle-style': 'shortdash', // короткие тире
                'strokeStyle-offset': 10,
            },
            0: {
                'name': 'нет линии',
                'strokeColor': '#fffba3',
                'strokeColorActive': '#ed4543',
                'strokeWidth': 2,
                'strokeStyle-style': 'shortdot', // точки через двойной интервал
                'strokeStyle-offset': 10,
            },
            1: {
                'name': 'Ввод к Потребителю',
                'strokeColor': '#FF8C00',
                'strokeColorActive': '#ed4543',
                'strokeWidth': 2,
                'strokeStyle-style': 'solid', // сплошная
                'strokeStyle-offset': 10,
            },
        };

        // по-умолчанию материал опоры
        mmDefaultPlacemarkTowerMaterialID = null;
        if (typeof (mmSpravs['towermaterial']) !== 'undefined') {
            let myTowerMaterial = mmSpravs['towermaterial'].find(item => item.name === 'железобетон');
            if (typeof (myTowerMaterial) !== 'undefined') {
                mmDefaultPlacemarkTowerMaterialID = Number(myTowerMaterial['id']);
            }
        }

        // по-умолчанию назначение опоры
        mmDefaultPlacemarkTowerKindID = null;
        if (typeof (mmSpravs['towerkind']) !== 'undefined') {
            let myTowerKind = mmSpravs['towerkind'].find(item => item.name === 'промежуточная');
            if (typeof (myTowerKind) !== 'undefined') {
                mmDefaultPlacemarkTowerKindID = Number(myTowerKind['id']);
            }
        }
    }

    // ---------------------------------------------------------------
    // иницилизация глобальных переменных, которые можно обнулять
    function funGlobalVarsCanResetInit() {

        // очистить глобальные переменные
        mmObjects = []; // массив точек и линий на карте
        mmObjectsOther = []; // массив других обьектов в видимой области карты
        mmSegments = []; // массив сегментов на карте
        mmCurrentCoords = []; // текущие координаты метки
        mmCurrentPlacemarkMapID = null; // ID текущей точки
        mmLastPlacemarkMapID = null; // ID старой точки
        mmCurrentPolylineMapID = null; // ID текущей линии
        mmLastPolylineMapID = null; // ID старой линии
        mmCurrentSegmentMapID = null; // ID текущего сегмента
        mmLastSegmentMapID = null; // ID старого сегмента
        mmMaxSegmentN = 0; // порядковый номер сегментов
        mmDefaulPlacemarkType = 'tower'; // по-умолчанию тип точки
        mmDefaulPolylineType = 701; // по-умолчанию тип линии (701 - воздушная, 702 - кабельная)
        mmDefaultBaseVoltage = null; // по-умолчанию класс напряжение (задает вся ЛЭП)
        mmClipboard = []; // множественный выбор обьектов
        mmIsGroupOperation = false; // признак групповой операции

        // очистить поле выбора импортируемого файла
        $("#importUpload").val('');
    }

</script>
