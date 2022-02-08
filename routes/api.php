<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// роутинг API
Route::group(
    [
        'namespace' => 'API',
        'middleware' => 'saveLog:1', // запись в log (1 - API , 2 - web)
    ],
    function () {

        // задачи все списком
        Route::post('getTasks', 'TaskController@getTasks')->name('getTasks');

        // получить
        Route::post('getTask', 'TaskController@getTask');
        // сохранить
        Route::post('saveTask', 'TaskController@saveTask');

        // изменить дата начала или конца
        Route::post('changeTaskStart', 'TaskController@changeTaskStart');
        Route::post('changeTaskEnd', 'TaskController@changeTaskEnd');
        // изменить статус
        Route::post('changeTaskStatus', 'TaskController@changeTaskStatus');

        // получить данные таблиц (всех или конкретной по ее имени и id строки)
        Route::get('getTable', 'TablController@getTable')->name('getTable');
    });

// для локальной работы c vue
Route::group(
    [
        'namespace' => 'apiBackend',
    ],
    function () {

        // общее
        // выполнение сырого запроса
        Route::post('/runAnyQuery', 'CommonController@runAnyQuery');
        // получить все записи из указанной папки модели
        Route::post('/getModelRecords', 'CommonController@getModelRecords');

        // карта
        Route::post('/getNearObjectsOnDistance', 'CommonController@getNearObjectsOnDistance'); // получить список ближайших обьектов по дистанции

        // получить список файлов из папки модели
        Route::post('/getModelFiles', 'CommonController@getModelFiles');
        // закачать файлы к папку модели
        Route::post('/uploadModelFiles', 'CommonController@uploadModelFiles');
        // удалить файл в папке модели
        Route::post('/deleteModelFile', 'CommonController@deleteModelFile');

        // сохранить данные об опорах с карты
        //Route::post('/saveMapTowers', 'TowerController@save');
    });

// со страниц через запросы AJAX и VUE
Route::group(
    [
        'namespace' => 'backend',
    ],
    function () {

        // acline (ЛЭП)
        Route::post('/aclineVueIndex', 'Acline\AclineController@vueIndex');
        Route::post('/aclineVueDelete', 'Acline\AclineController@vueDelete');
        Route::post('/aclineVueLoad', 'Acline\AclineController@vueLoad');
        Route::post('/aclineVueSave', 'Acline\AclineController@vueSave');
        Route::post('/aclineVueSaveOther', 'Acline\AclineController@vueSaveOther');
        // карта
        Route::post('/aclineMapUpdate', 'Acline\AclineMapController@mapUpdate')->name('acline.map.update');
        Route::post('/aclineMapImport', 'Acline\AclineMapController@mapImport')->name('acline.map.import');
        Route::post('/aclineMapLoadNearObjects', 'Acline\AclineMapController@mapLoadNearObjects')->name('acline.map.loadNearObjects');
        // карта по-новому через svg
        Route::post('/aclineMapSvgLoad', 'Acline\AclineMapController@vueMapSvgLoad')->name('acline.mapSvg.load');
        // целостность данных
        Route::post('/bazaVueRepair', 'Acline\AclineRepairController@vueRepair');

        // aclinestatus (статусы линий)
        Route::post('/aclineStatusVueIndex', 'AclineStatusController@vueIndex'); // список
        Route::post('/aclineStatusVueSave', 'AclineStatusController@vueSave'); // сохранение
        Route::post('/aclineStatusVueSelectedRows', 'AclineStatusController@vueSelectedRows'); // групповая обработка строк

        // журнал операций
        Route::post('/adminlogVueIndex', 'AdminLogController@vueIndex'); // список
        Route::post('/adminlogVueDelete', 'AdminLogController@vueDelete'); // групповая обработка строк

        // выбор кода подключения
        Route::post('/getBusbarsectionsOnSubstation', 'ConnectivitycodeController@getBusbarsectionsOnSubstation');
        Route::post('/getTerminalsOnBusbarsection', 'ConnectivitycodeController@getTerminalsOnBusbarsection');
        Route::post('/getTerminalOnConnectivitycode', 'ConnectivitycodeController@getTerminalOnConnectivitycode');

        // потребители
        Route::post('/customerVueIndex', 'CustomerController@vueIndex'); // список

        // ТП
        Route::post('/substationVueIndex', 'SubstationController@vueIndex'); // список
        Route::post('/substationVueDelete', 'SubstationController@vueDelete'); // удаление

        // реальные опоры
        Route::post('/towerVueSave', 'TowerController@vueSave'); // сохранение

        // материалы опор
        Route::post('/towermaterialVueIndex', 'TowermaterialController@vueIndex'); // список
        Route::post('/towermaterialVueSave', 'TowermaterialController@vueSave'); // сохранение
        Route::post('/towermaterialVueSelectedRows', 'TowermaterialController@vueSelectedRows'); // групповая обработка строк

        // назначения опор
        Route::post('/towerkindVueIndex', 'TowerkindController@vueIndex'); // список
        Route::post('/towerkindVueSave', 'TowerkindController@vueSave'); // сохранение
        Route::post('/towerkindVueSelectedRows', 'TowerkindController@vueDelete'); // групповая обработка строк

        // конструкции опор
        Route::post('/towerconstructionkindVueIndex', 'TowerconstructionkindController@vueIndex'); // список
        Route::post('/towerconstructionkindVueSave', 'TowerconstructionkindController@vueSave'); // сохранение
        Route::post('/towerconstructionkindVueSelectedRows', 'TowerconstructionkindController@vueDelete'); // групповая обработка строк

        // марки опор
        Route::post('/towerinfoVueIndex', 'TowerinfoController@vueIndex'); // список
        Route::post('/towerinfoVueSave', 'TowerinfoController@vueSave'); // сохранение
        Route::post('/towerinfoVueSelectedRows', 'TowerinfoController@vueDelete'); // групповая обработка строк

        // сборные агрегаты
        Route::post('/towerconstructionaggregateVueIndex', 'TowerconstructionaggregateController@vueIndex'); // список
        Route::post('/towerconstructionaggregateVueSave', 'TowerconstructionaggregateController@vueSave'); // сохранение
        Route::post('/towerconstructionaggregateVueSelectedRows', 'TowerconstructionaggregateController@vueDelete'); // групповая обработка строк

        // компоненты
        Route::post('/towerConstructionMasterVueSpravLoad', 'TowerConstructionMasterController@vueSpravLoad'); // загрузка справочника
        Route::post('/towerConstructionMasterVueSpravSave', 'TowerConstructionMasterController@vueSpravSave'); // сохранение строки справочника
        Route::post('/towerConstructionMasterVueSpravDelete', 'TowerConstructionMasterController@vueSpravDelete'); // удаление строки справочника
        Route::post('/towerConstructionMasterVueSpravlGlobalSearch', 'TowerConstructionMasterController@vueSpravlGlobalSearch'); // поиск по всем справочникам
        Route::post('/towerConstructionMasterVuePivotLoad', 'TowerConstructionMasterController@vuePivotLoad'); // загрузка сводной
        Route::post('/towerConstructionMasterVuePivotSave', 'TowerConstructionMasterController@vuePivotSave'); // сохранение сводной
        Route::post('/towerConstructionMasterVueWeightItogo', 'TowerConstructionMasterController@vueWeightItogo'); // итоговый рассчетный вес всех компонентов
        Route::post('/towerConstructionMasterVueImportExport', 'TowerConstructionMasterController@vueImportExport'); // импорт/экспорт
        Route::post('/towerConstructionMasterVueCopyFromTowerinfo', 'TowerConstructionMasterController@vueCopyFromTowerinfo'); // скопировать из марки

        //справочники
        //общие тех данные
        Route::post('/identifiedobjectVueIndex', 'IdentifiedobjectController@vueIndex'); // список
        Route::post('/identifiedobjectVueSave', 'IdentifiedobjectController@vueSave'); // сохранение
        Route::post('/identifiedobjectVueDelete', 'IdentifiedobjectController@vueDelete'); // удаление
        //Базовые напряжения
        Route::post('/basevoltageVueIndex', 'BasevoltageController@vueIndex'); // список
        Route::post('/basevoltageVueSave', 'BasevoltageController@vueSave'); // сохранение
        Route::post('/basevoltageVueDelete', 'BasevoltageController@vueDelete'); // удаление
        //Марки проводов
        Route::post('/aclinesegmentinfoVueIndex', 'AclinesegmentinfoController@vueIndex'); // список
        Route::post('/aclinesegmentinfoVueSave', 'AclinesegmentinfoController@vueSave'); // сохранение
        Route::post('/aclinesegmentinfoVueDelete', 'AclinesegmentinfoController@vueDelete'); // удаление
        //Марка разъединителя
        Route::post('/disconnectorinfoVueIndex', 'DisconnectorinfoController@vueIndex'); // список
        Route::post('/disconnectorinfoVueSave', 'DisconnectorinfoController@vueSave'); // сохранение
        Route::post('/disconnectorinfoVueDelete', 'DisconnectorinfoController@vueDelete'); // удаление
        //Марки разрядников
        Route::post('/dischargerinfoVueIndex', 'DischargerinfoController@vueIndex'); // список
        Route::post('/dischargerinfoVueSave', 'DischargerinfoController@vueSave'); // сохранение
        Route::post('/dischargerinfoVueDelete', 'DischargerinfoController@vueDelete'); // удаление
        //Условия прокладки
        Route::post('/layingconditionkindVueIndex', 'LayingconditionkindController@vueIndex'); // список
        Route::post('/layingconditionkindVueSave', 'LayingconditionkindController@vueSave'); // сохранение
        Route::post('/layingconditionkindVueDelete', 'LayingconditionkindController@vueDelete'); // удаление
        //Пересечения местности
        Route::post('/crossingVueIndex', 'CrossingController@vueIndex'); // список
        Route::post('/crossingVueSave', 'CrossingController@vueSave'); // сохранение
        Route::post('/crossingVueDelete', 'CrossingController@vueDelete'); // удаление
        //Типы пересеченой местности
        Route::post('/crossingtypeVueIndex', 'CrossingtypeController@vueIndex'); // список
        Route::post('/crossingtypeVueSave', 'CrossingtypeController@vueSave'); // сохранение
        Route::post('/crossingtypeVueDelete', 'CrossingtypeController@vueDelete'); // удаление
        //Материалы
        Route::post('/materialkindVueIndex', 'MaterialkindController@vueIndex'); // список
        Route::post('/materialkindVueSave', 'MaterialkindController@vueSave'); // сохранение
        Route::post('/materialkindVueDelete', 'MaterialkindController@vueDelete'); // удаление
        // пользователи
        Route::post('/userVueIndex', 'UserController@vueIndex'); // список
        Route::post('/userVueSave', 'UserController@vueSave'); // сохранение
        Route::post('/userVueDelete', 'UserController@vueDelete'); // групповая обработка строк

        Route::resource('/asset', 'AssetController');

        //Добавление/обновление Asset к имеющейся модели
        Route::post('/assets/model/{modelName}/id/{id}', 'AssetController@setModelAsset');
        Route::get('/assets/model/{modelName}/id/{id}', 'AssetController@getModelAsset');
        Route::get('/assets/enproClassDefect/{id}', 'AssetController@getEnproClassDefectAssets');

        //Группы Asset-ов
        Route::get('/assetgroup', 'AssetGroupController@index');
        Route::get('/assetgroupkind', 'AssetGroupController@getAssetGroupKindList');
        Route::post('/assetgroup', 'AssetGroupController@store');

        //EnproVehicle
        Route::apiResource('enprovehicles', 'EnproVehicleController');

        //EnproTool
        Route::apiResource('enprotools', 'EnproToolController');

        //Defects

        //Substations
        Route::any('substation/scheme/{id}', 'SubstationController@scheme');
        Route::any('substation/schemeTemplate', 'SubstationController@schemeTemplate');


        //CableInfo and WireInfo
        Route::apiResource('unitMultiplier', 'UnitMultiplierController');
        Route::apiResource('unitSymbols', 'UnitSymbolsController');
        Route::apiResource('streetDetail', 'StreetDetailController');
        Route::apiResource('townDetail', 'TownDetailController');
        Route::apiResource('streetAddress', 'StreetAddressController');
        Route::apiResource('organisation', 'OrganisationController');
        Route::apiResource('organisationRole', 'OrganisationRoleController');
        Route::apiResource('assetModelUsageKind', 'AssetModelUsageKindController');
        Route::apiResource('manufacturer', 'ManufacturerController');
        Route::apiResource('corporateStandardKind', 'CorporateStandardKindController');
        Route::apiResource('length', 'LengthController');
        Route::apiResource('mass', 'MassController');
        Route::apiResource('productAssetModel', 'ProductAssetModelController');
        Route::apiResource('wireInsulationKind', 'WireInsulationKindController');
        Route::apiResource('wireMaterialKind', 'WireMaterialKindController');
        Route::apiResource('resistancePerLength', 'ResistancePerLengthController');
        Route::apiResource('currentFlow', 'CurrentFlowController');
        Route::apiResource('assetInfo', 'AssetInfoController');
        Route::apiResource('cableConstructionKind', 'CableConstructionKindController');
        Route::apiResource('temperature', 'TemperatureController');
        Route::apiResource('cableOuterJacketKind', 'CableOuterJacketKindController');
        Route::apiResource('cableShieldMaterialKind', 'CableShieldMaterialKindController');
        Route::apiResource('wireInfo', 'WireInfoController');
        Route::apiResource('cableInfo', 'CableInfoController');
        Route::apiResource('location', 'LocationController');
        Route::apiResource('concentricNeutralCableInfo', 'ConcentricNeutralCableInfoController');
        Route::apiResource('tapeShieldCableInfo', 'TapeShieldCableInfoController');
        Route::apiResource('enpro_defect', 'EnproDefectController');
        Route::apiResource('enpro_class_defect', 'EnproClassDefectController');
        Route::apiResource('enpro_group_defect', 'EnproGroupDefectController');
        Route::apiResource('gost', 'GostController');
        Route::apiResource('modelName.wireAssemblyInfo', 'WireAssemblyInfoController');
        Route::apiResource('oldTransformerTankInfo', 'OldTransformerTankInfoController');
        Route::apiResource('procedure', 'ProcedureController');
        Route::apiResource('modelName.switchInfo', 'SwitchInfoController');
        Route::apiResource('currenttransformerinfo', 'CurrenttransformerinfoController');
        Route::apiResource('potentialtransformerinfo', 'PotentialTransformerInfoController');
        Route::apiResource('ratio', 'RatioController');

        Route::post('modelName.switchInfo/delete', 'SwitchInfoController@massDestroy');

        //Импорт кабелей
        Route::post('wireAssemblyInfo/import_overhead', 'WireAssemblyInfoController@importOverheadWireInfo');
        Route::post('wireAssemblyInfo/import_cable', 'WireAssemblyInfoController@importCableInfo');
        Route::get('wireAssemblyInfo/clear', 'WireAssemblyInfoController@clearWireAssemblyInfo');
        Route::post('switchInfo/model/{modelName}/import', 'SwitchInfoController@import');

        //Справочники Kind
        Route::get('/all_kind/model/{modelName}', 'AllKindController@index');
        Route::get('/all_kind/model/{modelName}/id/{id}', 'AllKindController@show');
        Route::post('/all_kind/model/{modelName}', 'AllKindController@store');
        Route::post('/all_kind/model/{modelName}/id/{id}', 'AllKindController@update');
        Route::delete('/all_kind/model/{modelName}/id/{id}', 'AllKindController@destroy');
        Route::post('/all_kind/model/{modelName}/delete', 'AllKindController@massDestroy');

        //Справочники Enum Kind
        Route::get('/all_enum_kind/model/{modelName}', 'AllEnumKindController@index');
        Route::get('/all_enum_kind/model/{modelName}/id/{id}', 'AllEnumKindController@show');
        Route::post('/all_enum_kind/model/{modelName}', 'AllEnumKindController@store');
        Route::post('/all_enum_kind/model/{modelName}/id/{id}', 'AllEnumKindController@update');
        Route::delete('/all_enum_kind/model/{modelName}/id/{id}', 'AllEnumKindController@destroy');
        Route::post('/all_enum_kind/model/{modelName}/delete', 'AllEnumKindController@massDestroy');

        //Общий импорт из Excel
        Route::post('loadExcel/model/{modelName}', 'ExcelLoadController@importExcel');
    });

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
