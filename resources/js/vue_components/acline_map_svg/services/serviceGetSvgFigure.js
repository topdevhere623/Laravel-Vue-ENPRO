// ------------------------------------------------------------------
// создание фигур - Create

export const serviceGetSvgFigure = (getType) => {
    let myNewObject;

    switch (getType) {
        case 'substation':
            myNewObject = getSubstation();
            break;
        case 'tower':
            myNewObject = getTower();
            break;
        case 'customer':
            myNewObject = getCustomer();
            break;
        case 'discharger':
            myNewObject = getDischarger();
            break;
        case 'opn':
            myNewObject = getOPN();
            break;
        case 'grounding':
            myNewObject = getGrounding();
            break;
        case 'lamp':
            myNewObject = getLamp();
            break;
        case 'adapter':
            myNewObject = getAdapter();
            break;
        case 'commline':
            myNewObject = getCommLine();
            break;
        case 'disconnector':
            myNewObject = getDisconnector();
            break;
        case 'reklouzer':
            myNewObject = getReklouzer();
            break;
        case 'vna':
            myNewObject = getVNa();
            break;
        case 'text':
            myNewObject = getText();
            break;
        case '701':
            myNewObject = getLine701();
            break;
        case '702':
            myNewObject = getLine702();
            break;
        case 'segment':
            myNewObject = getSegment();
            break;
    }

    // общие для всех обьектов поля
    // если здесь заранее не обьявить, то не будет реактивности
    myNewObject.current = false; // обязательно, чтоб реактивность vue была
    myNewObject.deleted = false; // обязательно, чтоб условия проходили
    myNewObject.viewName = null;
    myNewObject.parentPlacemarkMapID = null;
    myNewObject.parentPolylineMapID = null;
    myNewObject.parentSegmentMapID = null;
    myNewObject.positionText = 3; // по-умолчанию;
    myNewObject.textNameX = null; // по-умолчанию;
    myNewObject.textNameY = null; // по-умолчанию;
    myNewObject.position = 3; // по-умолчанию;
    myNewObject.angle = 0;
    myNewObject.x = 0; // чтоб в момент добавления не было ошибки - значения расчетные
    myNewObject.y = 0;
    myNewObject.lat = null;
    myNewObject.long = null;

    // возвращаемый параметр
    return myNewObject;
};

// ------------------------------------------------------------------
// фигура ТП - прямоугольник
export const getSubstation = () => {

    // возвращаемый параметр
    return {
        type: 'substation',
        mapType: 'placemark',
        width: 15,
        height: 5,
        name: 'ТП',
        hint: 'ТП',
        connectionsCount: 0,
        allowedConnectionsCount: 1,
    };
};

// ------------------------------------------------------------------
// фигура опора - круг, треугольник или квадрат, в зависимости от материала
export const getTower = () => {

    // возвращаемый параметр
    return {
        type: 'tower',
        mapType: 'placemark',
        r: 3,
        localName: 'опора',
        connectionsCount: 0,
        hint: 'опора',

        // для выделения активного по углам
        width: 3 * 2, // r * 2
        height: 3 * 2, // r * 2

        // специфичное для опоры
        towerMaterial: 1,
    };
};

// ------------------------------------------------------------------
// фигура Потребитель - круг
export const getCustomer = () => {

    // возвращаемый параметр
    return {
        type: 'customer',
        mapType: 'placemark',
        r: 2,
        address: 'Потребитель',
        connectionsCount: 0,
        hint: 'Потребитель',

        // для выделения активного по углам
        width: 2 * 2, // r * 2
        height: 2 * 2, // r * 2
    };
};

// ------------------------------------------------------------------
// фигура разрядник
export const getDischarger = () => {

    // возвращаемый параметр
    return {
        type: 'discharger',
        mapType: 'placemark',
        width: 4,
        height: 10,
        name: 'разрядник',
        connectionsCount: 0,
        hint: 'разрядник',
    };
};

// ------------------------------------------------------------------
// фигура ОПН
export const getOPN = () => {

    // возвращаемый параметр
    return {
        type: 'opn',
        mapType: 'placemark',
        width: 4,
        height: 10,
        name: 'ОПН',
        connectionsCount: 0,
        hint: 'ОПН',
    };
};

// ------------------------------------------------------------------
// фигура заземление
export const getGrounding = () => {

    // возвращаемый параметр
    return {
        type: 'grounding',
        mapType: 'placemark',
        width: 6,
        height: 12,
        name: 'заземление',
        connectionsCount: 0,
        hint: 'заземление',
    };
};

// ------------------------------------------------------------------
// фигура фонарь
export const getLamp = () => {

    // возвращаемый параметр
    return {
        type: 'lamp',
        mapType: 'placemark',
        r: 3,
        name: 'фонарь',
        connectionsCount: 0,
        hint: 'фонарь',

        // для выделения активного по углам
        width: 3 * 2, // r * 2
        height: 3 * 2, // r * 2
    };
};

// ------------------------------------------------------------------
// фигура адаптер
export const getAdapter = () => {

    // возвращаемый параметр
    return {
        type: 'adapter',
        mapType: 'placemark',
        width: 4,
        height: 10,
        name: 'адаптер',
        connectionsCount: 0,
        hint: 'адаптер',
    };
};

// ------------------------------------------------------------------
// фигура линия связи (c)
export const getCommLine = () => {

    // возвращаемый параметр
    return {
        type: 'commline',
        mapType: 'text',
        text: 'C',
        name: 'линия связи',
        connectionsCount: 0,
        hint: 'линия связи',

        // для выделения активного по углам
        width: 6 * 1, // кол-во букв на ширину среднюю
        height: 6,
    };
};

// ------------------------------------------------------------------
// фигура разьединитель
export const getDisconnector = () => {

    // возвращаемый параметр
    return {
        type: 'disconnector',
        mapType: 'placemark',
        width: 6,
        height: 12,
        name: 'разьединитель',
        connectionsCount: 0,
        hint: 'разьединитель',
    };
};

// ------------------------------------------------------------------
// фигура реклоузер
export const getReklouzer = () => {

    // возвращаемый параметр
    return {
        type: 'reklouzer',
        mapType: 'placemark',
        width: 6,
        height: 12,
        name: 'реклоузер',
        connectionsCount: 0,
        hint: 'реклоузер',
    };
};

// ------------------------------------------------------------------
// фигура выключатель нагрузки
export const getVNa = () => {

    // возвращаемый параметр
    return {
        type: 'vna',
        mapType: 'placemark',
        width: 6,
        height: 12,
        name: 'выключатель нагрузки',
        connectionsCount: 0,
        hint: 'выключатель нагрузки',
    };
};

// ------------------------------------------------------------------
// фигура текстовая надпись
export const getText = () => {

    // возвращаемый параметр
    return {
        type: 'text',
        mapType: 'text',
        text: 'Ваш текст',
        name: 'Ваш текст',
        connectionsCount: 0,
        hint: 'Ваш текст',

        // для выделения активного по углам
        width: 9 * 6, // кол-во букв на ширину среднюю
        height: 10,
    };
};

// ------------------------------------------------------------------
// фигура воздушная линия 701
export const getLine701 = () => {

    // возвращаемый параметр
    return {
        type: '701',
        mapType: 'polyline',
        name: 'воздушная линия',
        connectionsCount: 0,
        hint: 'воздушная линия',
        x1: null,
        y1: null,
        x2: null,
        y2: null,
        eqDisconnectorStart: null,
        eqReklouzerStart: null,
        eqVNaStart: null,
        eqDisconnectorEnd: null,
        eqReklouzerEnd: null,
        eqVNaEnd: null,
    };
};

// ------------------------------------------------------------------
// фигура кабельная линия 702
export const getLine702 = () => {

    // возвращаемый параметр
    return {
        type: '702',
        mapType: 'polyline',
        name: 'кабельная линия',
        connectionsCount: 0,
        hint: 'кабельная линия',
        x1: null,
        y1: null,
        x2: null,
        y2: null,
        eqDisconnectorStart: null,
        eqReklouzerStart: null,
        eqVNaStart: null,
        eqDisconnectorEnd: null,
        eqReklouzerEnd: null,
        eqVNaEnd: null,
    };
};

// ------------------------------------------------------------------
// фигура сегмент
export const getSegment = () => {

    // возвращаемый параметр
    return {
        type: 'segment',
        mapType: 'polyline',
        name: 'сегмент',
        connectionsCount: 0,
        hint: 'сегмент',
        points: [],
    };
};
