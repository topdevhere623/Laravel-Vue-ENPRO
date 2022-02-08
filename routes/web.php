<?php

use Illuminate\Support\Facades\Route;

// роутинг

// очистка кеша на момент разработки
if (env('APP_DEBUG') == true) {
    // когда включена отладка ошибок - кеш очищать
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
}

// очистка кеша Laravel - через кнопку
Route::post('ajaxClearCache', 'backend\AjaxController@ajaxClearCache')->name('ajaxClearCache');

// очистка кеша Laravel - через url
Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Кэш очищен.";
});

// стандартная авторизация
Auth::routes();
Route::get('/swagger', function() {
    return File::get(public_path() . '/swagger/index.html');
});
// главная страница Админки
Route::group(
    [
        'prefix' => 'admin',
        'namespace' => 'backend',
        'middleware' => 'auth',
        'middleware' => 'status', // кто имеет доступ к Админке
        //'middleware' => 'saveLog:2', // запись в log (1 - API , 2 - web)
    ],
    function () {

        // тестовый запрос (для проб с API и AJAX и прочим. url: rezh.loc/admin/test) ----------------------------------------
        Route::get('test', 'TestTitovController@index');

        // страница приветсвия ----------------------------------------
        //Route::get('/', 'HomeController@index')->name('admin'); // первый вариант (Сергей)
        Route::get('/', 'HomeController@mainMap')->name('admin'); // с картой ТП (Михаил)

        // задачи ----------------------------------------indexView
        Route::get('todo', 'TodoController@index')->name('todo.index');
        Route::get('todo/edit/{id?}', 'TodoController@edit')->name('todo.edit');
        Route::post('todo/update/{id?}', 'TodoController@update')->name('todo.update');
        Route::delete('todo/{id}', 'TodoController@destroy')->name('todo.destroy');

        // статусы задач
        Route::get('todostatus', 'TodoStatusController@index')->name('todostatus.index');
        Route::get('todostatus/edit/{id?}', 'TodoStatusController@edit')->name('todostatus.edit');
        Route::post('todostatus/update/{id?}', 'TodoStatusController@update')->name('todostatus.update');
        Route::delete('todostatus/{id}', 'TodoStatusController@destroy')->name('todostatus.destroy');

        // этапы задач
        Route::get('todostage', 'TodoStageController@index')->name('todostage.index');
        Route::get('todostage/edit/{id?}', 'TodoStageController@edit')->name('todostage.edit');
        Route::post('todostage/update/{id?}', 'TodoStageController@update')->name('todostage.update');
        Route::delete('todostage/{id}', 'TodoStageController@destroy')->name('todostage.destroy');

        // компании
        Route::get('company', 'CompanyController@index')->name('company.index');
        Route::get('company/edit/{id?}', 'CompanyController@edit')->name('company.edit');
        Route::post('company/update/{id?}', 'CompanyController@update')->name('company.update');
        Route::delete('company/{id}', 'CompanyController@destroy')->name('company.destroy');

        // фио
        Route::get('fio', 'FioController@index')->name('fio.index');
        Route::get('fio/edit/{id?}', 'FioController@edit')->name('fio.edit');
        Route::post('fio/update/{id?}', 'FioController@update')->name('fio.update');
        Route::delete('fio/{id}', 'FioController@destroy')->name('fio.destroy');

        // паспортизация ЛЭП ----------------------------------------
        Route::get('task', 'TaskController@index')->name('task.index');
        Route::get('task/edit/{id?}', 'TaskController@edit')->name('task.edit');
        Route::post('task/update/{id?}', 'TaskController@update')->name('task.update');
        Route::delete('task/{id}', 'TaskController@destroy')->name('task.destroy');
        Route::get('task/jsonFiles/{id?}', 'TaskController@jsonFiles')->name('task.jsonFiles'); // список всех json-ов
        Route::post('task/jsonParse', 'TaskController@jsonParse')->name('task.jsonParse'); // парсинг json-а
        Route::post('task/jsonDelete', 'TaskController@jsonDelete')->name('task.jsonDelete'); // удаление json-а

        // модели ----------------------------------------
        // alarmdevice (сигнализирующии устройства)
        Route::get('alarmdevice', 'AlarmdeviceController@index')->name('alarmdevice.index');
        Route::get('alarmdevice/edit/{id?}', 'AlarmdeviceController@edit')->name('alarmdevice.edit');
        Route::post('alarmdevice/update/{id?}', 'AlarmdeviceController@update')->name('alarmdevice.update');
        Route::delete('alarmdevice/{id}', 'AlarmdeviceController@destroy')->name('alarmdevice.destroy');

        // acline (ЛЭП)
        Route::get('acline', 'Acline\AclineController@index')->name('acline.index');
        Route::get('acline/edit/{id?}', 'Acline\AclineController@edit')->name('acline.edit');
        // карта
        Route::get('acline/map/edit/{id?}/{regim?}', 'Acline\AclineMapController@mapEdit')->name('acline.map.edit');
        // отчеты
        Route::get('acline/report/{report_id}/{acline_id?}', 'Acline\AclineReportController@report')->name('acline.report');
        Route::get('line/cim/{profile_id}/{acline_id?}', 'LineController@cim')->name('line.cim');
        // целостность данных
        Route::get('acline/repair', 'Acline\AclineRepairController@repair')->name('acline.repair'); // целостность данных
        Route::get('acline/repair/newPoints/{regim?}', 'Acline\AclineRepairController@newPoints')->name('acline.repair.newPoints'); // дописать всем характерным точкам start-end - это разовый скрипт, сам с url запускаю
        Route::get('acline/repair/trancateAll', 'Acline\AclineRepairController@trancateAll')->name('acline.repair.trancateAll'); // очистить все линии и все что с ними связано (для отладки) !!! потом убрать - это разовый скрипт, сам с url запускаю

        // aclinestatus (статусы линий)
        Route::get('aclineStatus', 'AclineStatusController@index')->name('aclineStatus.index');

        // aclinesegmentinfo (марки проводов)
        Route::get('aclinesegmentinfo', 'AclinesegmentinfoController@index')->name('aclinesegmentinfo.index');
        Route::get('aclinesegmentinfo/edit/{id?}', 'AclinesegmentinfoController@edit')->name('aclinesegmentinfo.edit');
        Route::post('aclinesegmentinfo/update/{id?}', 'AclinesegmentinfoController@update')->name('aclinesegmentinfo.update');
        Route::delete('aclinesegmentinfo/{id}', 'AclinesegmentinfoController@destroy')->name('aclinesegmentinfo.destroy');

        // address (адреса)
        Route::get('address', 'AddressController@index')->name('address.index');
        Route::get('address/edit/{id?}', 'AddressController@edit')->name('address.edit');
        Route::post('address/update/{id?}', 'AddressController@update')->name('address.update');
        Route::delete('address/{id}', 'AddressController@destroy')->name('address.destroy');

        // asset (общие данные)
        Route::get('asset', 'AssetController@index')->name('asset.index');
        Route::get('asset/edit/{id?}', 'AssetController@edit')->name('asset.edit');
        Route::post('asset/update/{id?}', 'AssetController@update')->name('asset.update');
        Route::delete('asset/{id}', 'AssetController@destroy')->name('asset.destroy');

        // basevoltage (базовое напряжение)
        Route::get('basevoltage', 'BaseVoltageController@index')->name('basevoltage.index');
        Route::get('basevoltage/edit/{id?}', 'BaseVoltageController@edit')->name('basevoltage.edit');
        Route::post('basevoltage/update/{id?}', 'BaseVoltageController@update')->name('basevoltage.update');
        Route::delete('basevoltage/{id}', 'BaseVoltageController@destroy')->name('basevoltage.destroy');

        // bbsecinsulatorinfo (марка изоляторов)
        Route::get('bbsecinsulatorinfo', 'BbsecinsulatorinfoController@index')->name('bbsecinsulatorinfo.index');
        Route::get('bbsecinsulatorinfo/edit/{id?}', 'BbsecinsulatorinfoController@edit')->name('bbsecinsulatorinfo.edit');
        Route::post('bbsecinsulatorinfo/update/{id?}', 'BbsecinsulatorinfoController@update')->name('bbsecinsulatorinfo.update');
        Route::delete('bbsecinsulatorinfo/{id}', 'BbsecinsulatorinfoController@destroy')->name('bbsecinsulatorinfo.destroy');

        // building (сооружения)
        Route::get('building', 'BuildingController@index')->name('building.index');
        Route::get('building/edit/{id?}', 'BuildingController@edit')->name('building.edit');
        Route::post('building/update/{id?}', 'BuildingController@update')->name('building.update');
        Route::delete('building/{id}', 'BuildingController@destroy')->name('building.destroy');

        // cableboxkind (муфты)
        Route::get('cableboxkind', 'CableboxkindController@index')->name('cableboxkind.index');
        Route::get('cableboxkind/edit/{id?}', 'CableboxkindController@edit')->name('cableboxkind.edit');
        Route::post('cableboxkind/update/{id?}', 'CableboxkindController@update')->name('cableboxkind.update');
        Route::delete('cableboxkind/{id}', 'CableboxkindController@destroy')->name('cableboxkind.destroy');

        // classname (имена классов)
        Route::get('classname', 'ClassnameController@index')->name('classname.index');
        Route::get('classname/edit/{id?}', 'ClassnameController@edit')->name('classname.edit');
        Route::post('classname/update/{id?}', 'ClassnameController@update')->name('classname.update');
        Route::delete('classname/{id}', 'ClassnameController@destroy')->name('classname.destroy');

        // connector (фидеры)
        Route::get('connector', 'ConnectorController@index')->name('connector.index');
        Route::get('connector/edit/{id?}', 'ConnectorController@edit')->name('connector.edit');
        Route::post('connector/update/{id?}', 'ConnectorController@update')->name('connector.update');
        Route::delete('connector/{id}', 'ConnectorController@destroy')->name('connector.destroy');

        // crossing (пересечения местности)
        Route::get('crossing', 'CrossingController@index')->name('crossing.index');
        Route::get('crossing/edit/{id?}', 'CrossingController@edit')->name('crossing.edit');
        Route::post('crossing/update/{id?}', 'CrossingController@update')->name('crossing.update');
        Route::delete('crossing/{id}', 'CrossingController@destroy')->name('crossing.destroy');

        // crossingtype (типы пересеченой местности)
        Route::get('crossingtype', 'CrossingtypeController@index')->name('crossingtype.index');
        Route::get('crossingtype/edit/{id?}', 'CrossingtypeController@edit')->name('crossingtype.edit');
        Route::post('crossingtype/update/{id?}', 'CrossingtypeController@update')->name('crossingtype.update');
        Route::delete('crossingtype/{id}', 'CrossingtypeController@destroy')->name('crossingtype.destroy');

        // customer (потребители)
        Route::get('customer', 'CustomerController@index')->name('customer.index');
        Route::get('customer/edit/{id?}', 'CustomerController@edit')->name('customer.edit');
        Route::post('customer/update/{id?}', 'CustomerController@update')->name('customer.update');
        Route::delete('customer/{id}', 'CustomerController@destroy')->name('customer.destroy');

        // device (устройства планшеты)
        Route::get('device', 'DeviceController@index')->name('device.index');
        Route::get('device/edit/{id?}', 'DeviceController@edit')->name('device.edit');
        Route::post('device/update/{id?}', 'DeviceController@update')->name('device.update');
        Route::delete('device/{id}', 'DeviceController@destroy')->name('device.destroy');

        // disconnector (разъединители на опорах)
        Route::get('disconnector', 'DisconnectorController@index')->name('disconnector.index');
        Route::get('disconnector/edit/{id?}', 'DisconnectorController@edit')->name('disconnector.edit');
        Route::post('disconnector/update/{id?}', 'DisconnectorController@update')->name('disconnector.update');
        Route::delete('disconnector/{id}', 'DisconnectorController@destroy')->name('disconnector.destroy');

        // disconnectorinfo (разъединители справочник)
        Route::get('disconnectorinfo', 'DisconnectorinfoController@index')->name('disconnectorinfo.index');
        Route::get('disconnectorinfo/edit/{id?}', 'DisconnectorinfoController@edit')->name('disconnectorinfo.edit');
        Route::post('disconnectorinfo/update/{id?}', 'DisconnectorinfoController@update')->name('disconnectorinfo.update');
        Route::delete('disconnectorinfo/{id}', 'DisconnectorinfoController@destroy')->name('disconnectorinfo.destroy');

        // discharger (разрядники на опорах)
        Route::get('discharger', 'DischargerController@index')->name('discharger.index');
        Route::get('discharger/edit/{id?}', 'DischargerController@edit')->name('discharger.edit');
        Route::post('discharger/update/{id?}', 'DischargerController@update')->name('discharger.update');
        Route::delete('discharger/{id}', 'DischargerController@destroy')->name('discharger.destroy');

        // dischargerinfo (разрядники справочник)
        Route::get('dischargerinfo', 'DischargerinfoController@index')->name('dischargerinfo.index');
        Route::get('dischargerinfo/edit/{id?}', 'DischargerinfoController@edit')->name('dischargerinfo.edit');
        Route::post('dischargerinfo/update/{id?}', 'DischargerinfoController@update')->name('dischargerinfo.update');
        Route::delete('dischargerinfo/{id}', 'DischargerinfoController@destroy')->name('dischargerinfo.destroy');

        // endpoint (конечные точки)
        Route::get('endpoint', 'EndpointController@index')->name('endpoint.index');
        Route::get('endpoint/edit/{id?}', 'EndpointController@edit')->name('endpoint.edit');
        Route::post('endpoint/update/{id?}', 'EndpointController@update')->name('endpoint.update');
        Route::delete('endpoint/{id}', 'EndpointController@destroy')->name('endpoint.destroy');

        // file (файлы)
        Route::get('file', 'FileController@index')->name('file.index');
        Route::get('file/edit/{id?}', 'FileController@edit')->name('file.edit');
        Route::post('file/update/{id?}', 'FileController@update')->name('file.update');
        Route::delete('file/{id}', 'FileController@destroy')->name('file.destroy');

        // identifiedobject (общие технические данные IO)
        Route::get('identifiedobject', 'IdentifiedobjectController@index')->name('identifiedobject.index');
        Route::get('identifiedobject/edit/{id?}', 'IdentifiedobjectController@edit')->name('identifiedobject.edit');
        Route::post('identifiedobject/update/{id?}', 'IdentifiedobjectController@update')->name('identifiedobject.update');
        Route::delete('identifiedobject/{id}', 'IdentifiedobjectController@destroy')->name('identifiedobject.destroy');

        // groundingkind (назначение заземлений)
        Route::get('groundingkind', 'GroundingkindController@index')->name('groundingkind.index');
        Route::get('groundingkind/edit/{id?}', 'GroundingkindController@edit')->name('groundingkind.edit');
        Route::post('groundingkind/update/{id?}', 'GroundingkindController@update')->name('groundingkind.update');
        Route::delete('groundingkind/{id}', 'GroundingkindController@destroy')->name('groundingkind.destroy');

        // groundingmaterialkind (материалы заземлений на опорах)
        Route::get('groundingmaterialkind', 'GroundingmaterialkindController@index')->name('groundingmaterialkind.index');
        Route::get('groundingmaterialkind/edit/{id?}', 'GroundingmaterialkindController@edit')->name('groundingmaterialkind.edit');
        Route::post('groundingmaterialkind/update/{id?}', 'GroundingmaterialkindController@update')->name('groundingmaterialkind.update');
        Route::delete('groundingmaterialkind/{id}', 'GroundingmaterialkindController@destroy')->name('groundingmaterialkind.destroy');

        // layingconditionkind (условия прокладки)
        Route::get('layingconditionkind', 'LayingconditionkindController@index')->name('layingconditionkind.index');
        Route::get('layingconditionkind/edit/{id?}', 'LayingconditionkindController@edit')->name('layingconditionkind.edit');
        Route::post('layingconditionkind/update/{id?}', 'LayingconditionkindController@update')->name('layingconditionkind.update');
        Route::delete('layingconditionkind/{id}', 'LayingconditionkindController@destroy')->name('layingconditionkind.destroy');

        // materialkind (материалы)
        Route::get('materialkind', 'MaterialkindController@index')->name('materialkind.index');
        Route::get('materialkind/edit/{id?}', 'MaterialkindController@edit')->name('materialkind.edit');
        Route::post('materialkind/update/{id?}', 'MaterialkindController@update')->name('materialkind.update');
        Route::delete('materialkind/{id}', 'MaterialkindController@destroy')->name('materialkind.destroy');

        // material (материалы)
        Route::get('material', 'MaterialController@index')->name('material.index');
        Route::get('material/edit/{id?}', 'MaterialController@edit')->name('material.edit');
        Route::post('material/update/{id?}', 'MaterialController@update')->name('material.update');
        Route::delete('material/{id}', 'MaterialController@destroy')->name('material.destroy');

        // picturekind (выды изображений)
        Route::get('picturekind', 'PicturekindController@index')->name('picturekind.index');
        Route::get('picturekind/edit/{id?}', 'PicturekindController@edit')->name('picturekind.edit');
        Route::post('picturekind/update/{id?}', 'PicturekindController@update')->name('picturekind.update');
        Route::delete('picturekind/{id}', 'PicturekindController@destroy')->name('picturekind.destroy');

        // primingkind (грунт заземлений)
        Route::get('primingkind', 'PrimingkindController@index')->name('primingkind.index');
        Route::get('primingkind/edit/{id?}', 'PrimingkindController@edit')->name('primingkind.edit');
        Route::post('primingkind/update/{id?}', 'PrimingkindController@update')->name('primingkind.update');
        Route::delete('primingkind/{id}', 'PrimingkindController@destroy')->name('primingkind.destroy');

        // ptendinsulatorkind (типы изоляторов)
        Route::get('ptendinsulatorkind', 'PtendinsulatorkindController@index')->name('ptendinsulatorkind.index');
        Route::get('ptendinsulatorkind/edit/{id?}', 'PtendinsulatorkindController@edit')->name('ptendinsulatorkind.edit');
        Route::post('ptendinsulatorkind/update/{id?}', 'PtendinsulatorkindController@update')->name('ptendinsulatorkind.update');
        Route::delete('ptendinsulatorkind/{id}', 'PtendinsulatorkindController@destroy')->name('ptendinsulatorkind.destroy');

        // picturetype (типы изображений)
        Route::get('picturetype', 'PicturetypeController@index')->name('picturetype.index');
        Route::get('picturetype/edit/{id?}', 'PicturetypeController@edit')->name('picturetype.edit');
        Route::post('picturetype/update/{id?}', 'PicturetypeController@update')->name('picturetype.update');
        Route::delete('picturetype/{id}', 'PicturetypeController@destroy')->name('picturetype.destroy');

        // subclass (подклассы)
        Route::get('subclass', 'SubclassController@index')->name('subclass.index');
        Route::get('subclass/edit/{id?}', 'SubclassController@edit')->name('subclass.edit');
        Route::post('subclass/update/{id?}', 'SubclassController@update')->name('subclass.update');
        Route::delete('subclass/{id}', 'SubclassController@destroy')->name('subclass.destroy');

        // substation (ТП)
        Route::get('substation', 'SubstationController@index')->name('substation.index');
        Route::get('substation/edit/{id?}', 'SubstationController@edit')->name('substation.edit');
        Route::get('substation/show/{id?}', 'SubstationController@show')->name('substation.show');
        Route::get('substation/icon/{id?}', 'SubstationController@icon')->name('substation.icon');
        Route::post('substation/update/{id?}', 'SubstationController@update')->name('substation.update');
        Route::delete('substation/{id}', 'SubstationController@destroy')->name('substation.destroy');
        Route::get('substation/parse/{id?}', 'SubstationController@parse')->name('substation.parse');
        Route::get('substation/clear/{id?}', 'SubstationController@clear')->name('substation.clear');
        Route::post('substation/parse/{id?}', 'SubstationController@parseScheme')->name('substation.parseScheme');
        Route::get('substation/parsexsde/{id?}', 'SubstationController@parseScheme')->name('substation.parseXsde');

        // substationinfo (информация о ТП)
        Route::get('substationinfo', 'SubstationinfoController@index')->name('substationinfo.index');
        Route::get('substationinfo/edit/{id?}', 'SubstationinfoController@edit')->name('substationinfo.edit');
        Route::post('substationinfo/update/{id?}', 'SubstationinfoController@update')->name('substationinfo.update');
        Route::delete('substationinfo/{id}', 'SubstationinfoController@destroy')->name('substationinfo.destroy');

        // substationtpinfo (информация о ТП)
        Route::get('substationtpinfo', 'SubstationtpinfoController@index')->name('substationtpinfo.index');
        Route::get('substationtpinfo/edit/{id?}', 'SubstationtpinfoController@edit')->name('substationtpinfo.edit');
        Route::post('substationtpinfo/update/{id?}', 'SubstationtpinfoController@update')->name('substationtpinfo.update');
        Route::delete('substationtpinfo/{id}', 'SubstationtpinfoController@destroy')->name('substationtpinfo.destroy');

        // substationfunction (функции ТП)
        Route::get('substationfunction', 'SubstationfunctionController@index')->name('substationfunction.index');
        Route::get('substationfunction/edit/{id?}', 'SubstationfunctionController@edit')->name('substationfunction.edit');
        Route::post('substationfunction/update/{id?}', 'SubstationfunctionController@update')->name('substationfunction.update');
        Route::delete('substationfunction/{id}', 'SubstationfunctionController@destroy')->name('substationfunction.destroy');

        // substationfunctionkind (виды функций ТП)
        Route::get('substationfunctionkind', 'SubstationfunctionkindController@index')->name('substationfunctionkind.index');
        Route::get('substationfunctionkind/edit/{id?}', 'SubstationfunctionkindController@edit')->name('substationfunctionkind.edit');
        Route::post('substationfunctionkind/update/{id?}', 'SubstationfunctionkindController@update')->name('substationfunctionkind.update');
        Route::delete('substationfunctionkind/{id}', 'SubstationfunctionkindController@destroy')->name('substationfunctionkind.destroy');

        // tasktype (типы задач)
        Route::get('tasktype', 'TasktypeController@index')->name('tasktype.index');
        Route::get('tasktype/edit/{id?}', 'TasktypeController@edit')->name('tasktype.edit');
        Route::post('tasktype/update/{id?}', 'TasktypeController@update')->name('tasktype.update');
        Route::delete('tasktype/{id}', 'TasktypeController@destroy')->name('tasktype.destroy');

        // tower (опоры)
        Route::get('tower', 'TowerController@index')->name('tower.index');
        Route::get('tower/edit/{id?}', 'TowerController@edit')->name('tower.edit');
        Route::post('tower/update/{id?}', 'TowerController@update')->name('tower.update');
        Route::delete('tower/{id}', 'TowerController@destroy')->name('tower.destroy');

        // компоненты
        Route::get('towerconstructionmaster', 'TowerConstructionMasterController@index')->name('towerconstructionmaster.index');

        // сборные агрегаты
        Route::get('towerconstructionaggregate', 'TowerconstructionaggregateController@index')->name('towerconstructionaggregate.index');
        Route::get('towerconstructionaggregate/edit/{id?}', 'TowerconstructionaggregateController@edit')->name('towerconstructionaggregate.edit');

        // towerinfo (марки опор)
        Route::get('towerinfo', 'TowerinfoController@index')->name('towerinfo.index');
        Route::get('towerinfo/edit/{id?}', 'TowerinfoController@edit')->name('towerinfo.edit');

        // towerinsulatormountingkind (способы крепления изоляторов на опорах)
        Route::get('towerinsulatormountingkind', 'TowerinsulatormountingkindController@index')->name('towerinsulatormountingkind.index');
        Route::get('towerinsulatormountingkind/edit/{id?}', 'TowerinsulatormountingkindController@edit')->name('towerinsulatormountingkind.edit');
        Route::post('towerinsulatormountingkind/update/{id?}', 'TowerinsulatormountingkindController@update')->name('towerinsulatormountingkind.update');
        Route::delete('towerinsulatormountingkind/{id}', 'TowerinsulatormountingkindController@destroy')->name('towerinsulatormountingkind.destroy');

        // towerkind (назначение опоры)
        Route::get('towerkind', 'TowerkindController@index')->name('towerkind.index');

        // towerconstructionkind (ккомпоненты)
        Route::get('towerconstructionkind', 'TowerconstructionkindController@index')->name('towerconstructionkind.index');

        // towermaterial (материал опоры)
        Route::get('towermaterial', 'TowermaterialController@index')->name('towermaterial.index');

        // towerpreservkind (защитные покрытия опор)
        Route::get('towerpreservkind', 'TowerpreservkindController@index')->name('towerpreservkind.index');
        Route::get('towerpreservkind/edit/{id?}', 'TowerpreservkindController@edit')->name('towerpreservkind.edit');
        Route::post('towerpreservkind/update/{id?}', 'TowerpreservkindController@update')->name('towerpreservkind.update');
        Route::delete('towerpreservkind/{id}', 'TowerpreservkindController@destroy')->name('towerpreservkind.destroy');

        // user (пользователи)
        Route::get('user', 'UserController@index')->name('user.index');
        Route::get('user/edit/{id?}', 'UserController@edit')->name('user.edit');
        Route::post('user/update/{id?}', 'UserController@update')->name('user.update');

        // таблицы ----------------------------------------
        // просмотр всех таблиц Firebird
        Route::get('tables/firebird', 'TableController@viewTablesFirebird')->name('tables.firebird');
        // просмотр одной таблицы Firebird
        Route::get('table/firebird/{table}', 'TableController@viewTableFirebird')->name('table.firebird');
        // просмотр всех таблиц MySQL
        Route::get('tables/mysql', 'TableController@viewTablesMySQL')->name('tables.mysql');
        // просмотр одной таблицы MySQL
        Route::get('table/mysql/{model}', 'TableController@viewTableMySQL')->name('table.mysql');
        // вывод таблиц с одинаковым названием для импорта
        Route::get('tables/for-imports', 'TableController@forImports')->name('tables.for-imports');

        // карта яндекс-карты ----------------------------------------
        Route::get('yandex_map/{model}', "YandexMapController@index")->name('yandex_map.index');

        // мое ----------------------------------------
        // настройки
        Route::get('admin_setting', "AdminSettingController@index")->name('admin_setting.index');
        Route::get('admin_setting/edit/{id?}', "AdminSettingController@edit")->name('admin_setting.edit');
        Route::post('admin_setting/update/{id?}', "AdminSettingController@update")->name('admin_setting.update');

        // роли пользователей
        Route::get('admin_user_role', 'AdminUserRoleController@index')->name('admin_user_role.index');

        // журнал операций Пользователя
        Route::get('admin_log', 'AdminLogController@index')->name('admin_log.index');

        // php-info
        Route::get('phpinfo', 'HomeController@phpinfo')->name('phpinfo');
        Route::get('mainmap', 'HomeController@mainMap')->name('mainMap');

        // API
        // инструкция
        Route::get('apiInstruction', "HomeController@apiInstruction")->name('apiInstruction');
        // отладка запросов
        Route::get('apiQueries', "HomeController@apiQueries")->name('apiQueries');

        // запросы AJAX
        // смена статуса и пр.
        Route::post('ajaxChangeField', 'AjaxController@ajaxChangeField')->name('ajaxChangeField');
        // импорт таблицы
        Route::post('tables/ajaxImportTable', 'AjaxController@ajaxImportTable')->name('ajaxImportTable');
        // очистка таблицы модели
        Route::post('ajaxClearTable', 'AjaxController@ajaxClearTable')->name('ajaxClearTable');
        // отладка API запросов
        Route::post('apiQueries/ajaxApiTest', 'AjaxController@ajaxApiTest')->name('ajaxApiTest');

        // обновление координат точек в json-файле задачи
        Route::post('ajaxUpdateTaskJsonMap', 'AjaxController@ajaxUpdateTaskJsonMap')->name('ajaxUpdateTaskJsonMap');

        // -------------------------------------------
        // получение контента через Ajax
        Route::post('ajaxNewSubstation', 'SubstationController@edit')->name('ajaxNewSubstation');
        Route::post('ajaxNewSubstationSave', 'SubstationController@update')->name('ajaxNewSubstationSave');

        Route::post('ajaxNewConnector', 'ConnectorController@edit')->name('ajaxNewConnector');
        Route::post('ajaxNewConnectorSave', 'ConnectorController@update')->name('ajaxNewConnectorSave');

        Route::post('ajaxNewEndpoint', 'EndpointController@edit')->name('ajaxNewEndpoint');
        Route::post('ajaxNewEndpointSave', 'EndpointController@update')->name('ajaxNewEndpointSave');

        // -------------------------------------------
        // загрузка связанных данных - конечные точки фидера, через Ajax
        Route::post('ajaxLoadRelatedEndpoint', 'EndpointController@ajaxLoadRelatedEndpoint')->name('ajaxLoadRelatedEndpoint');

        //Дефекты
        //Route::apiResource('enpro_class_defect', 'EnproClassDefectController');
        //Route::apiResource('enpro_group_defect', 'EnproGroupDefectController');
        //Route::apiResource('enpro_defect', 'EnproDefectController');
        Route::get('/enpro_class_defect', 'EnproClassDefectController@indexView')->name('enproclassdefect.index');
        Route::get('/enpro_class_defect/edit', 'EnproClassDefectController@edit')->name('enproclassdefect.edit');
        Route::get('/enpro_group_defect', 'EnproGroupDefectController@indexView')->name('enprogroupdefect.index');
        Route::get('/enpro_group_defect/edit', 'EnproGroupDefectController@edit')->name('enprogroupdefect.edit');
        Route::get('/enpro_defect', 'EnproDefectController@indexView')->name('enprodefect.index');
        Route::get('/enpro_defect/edit', 'EnproDefectController@edit')->name('enprodefect.edit');


        Route::get('/all_kind', 'AllKindController@indexView')->name('all_kind.index');
        Route::get('/all_kind/{modelName}/edit/{id?}/enum/{enum?}', 'AllKindController@editView')->name('all_kind.edit');

        Route::get('/all_enum_kind/{modelName}', 'AllEnumKindController@indexView')->name('all_enum_kind.index');
        Route::get('/all_enum_kind/{modelName}/edit/{id?}', 'AllEnumKindController@editView')->name('all_enum_kind.edit');

        Route::get('/wire_info', 'WireInfoController@indexView')->name('wire_info.index');
        Route::get('/wire_info/edit/{id?}', 'WireInfoController@editView')->name('wire_info.edit');

        Route::get('/overhead_wire_info', 'WireAssemblyInfoController@indexViewOverheadWireInfo')->name('overhead_wire_info.index');
        Route::get('/overhead_wire_info/edit/{id?}', 'WireAssemblyInfoController@editViewOverheadWireInfo')->name('overhead_wire_info.edit');

        Route::get('/cable_info', 'WireAssemblyInfoController@indexViewCableInfo')->name('cable_info.index');
        Route::get('/cable_info/edit/{id?}', 'WireAssemblyInfoController@editViewCableInfo')->name('cable_info.edit');

        Route::get('/old_transformer_tank_info', 'OldTransformerTankInfoController@indexView')->name('old_transformer_tank_info.index');
        Route::get('/old_transformer_tank_info/edit/{id?}', 'OldTransformerTankInfoController@editView')->name('old_transformer_tank_info.edit');

        Route::get('/procedure', 'ProcedureController@indexView')->name('procedure.index');
        Route::get('/procedure/edit', 'ProcedureController@edit')->name('procedure.edit');

        Route::get('/fuse_info', 'SwitchInfoController@indexViewFuseInfo')->name('fuse_info.index');
        Route::get('/fuse_info/edit/{id?}', 'SwitchInfoController@editViewFuseInfo')->name('fuse_info.edit');

        Route::get('/recloser_info', 'SwitchInfoController@indexViewRecloserInfo')->name('recloser_info.index');
        Route::get('/recloser_info/edit/{id?}', 'SwitchInfoController@editViewRecloserInfo')->name('recloser_info.edit');

        Route::get('/breaker_info', 'SwitchInfoController@indexViewBreakerInfo')->name('breaker_info.index');
        Route::get('/breaker_info/edit/{id?}', 'SwitchInfoController@editViewBreakerInfo')->name('breaker_info.edit');

        Route::get('/disconnector_info', 'SwitchInfoController@indexViewDisconnectorInfo')->name('disconnector_info.index');
        Route::get('/disconnector_info/edit/{id?}', 'SwitchInfoController@editViewDisconnectorInfo')->name('disconnector_info.edit');

        Route::get('/load_break_switch_info', 'SwitchInfoController@indexViewLoadBreakSwitchInfo')->name('load_break_switch_info.index');
        Route::get('/load_break_switch_info/edit/{id?}', 'SwitchInfoController@editViewLoadBreakSwitchInfo')->name('load_break_switch_info.edit');

        Route::get('/loadExcel', 'ExcelLoadController@indexView')->name('loadExcel.index');
        Route::post('/loadExcel/model/{modelName}', 'ExcelLoadController@importExcel');
    });

// для фронта
Route::group(
    [
        'namespace' => 'frontend',
    ],
    function () {
        // главная страница
        Route::get('/', "HomeController@index")->name('home');
    });
