<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// мои сервисы
use App\Http\Services\backend\ModelService;

class DatabaseSeeder extends Seeder
{
    // подключение сервисов
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function run()
    {
        echo "\n" . "НАЧАТО ЗАПОЛНЕНИЕ ТАБЛИЦ:" . "\n\n";


        // для Админки
        echo "Заполнение моих настроек Админ-панели" . "\n";
        $this->call(AdminSettingTableSeeder::class);

        // новые таблицы, которых нет в схеме, и нет в Firebird
        // device, endpoint

        // новые таблицы, которые нет в схеме (но нужны), но есть в Firebird, поэтому дал такое же имя
        // acline, aclinesegmentinfo, towerconstructionbase, cableboxkind, groundingmaterialkind, groundingkind
        // layingconditionkind, materialkind, picturekind, primingkind, ptendinsulatorkind
        // span, substationtpinfo, substationfunctionkind,
        // tower, towerinsulatormountingkind, towerkind, towerpreservkind

        // импорт данных с таблиц Firebird в MySQL
        /* remove FireBird Connection from seeds */

        // заполннние других таблиц своими демо-данными (которых не получилось импортировать)
        echo "\n" . "Заполнениеи таблиц вручную:" . "\n";
        echo "(либо частичный импорт из Firebird, либо демо-данные)" . "\n\n";

        // в схеме (+), в Fb (+)
        //$this->call(AssetTableSeeder::class);

        // в схеме (+), в Fb (-), но есть похожая - CLASSNAMEMARKTYPE, импортирую из нее
        $this->call(ClassnameTableSeeder::class);

        // в схеме (+), в Fb (+), но возникают ошибки почему то при импорте (0 из 49), поэтому вручную
        $this->call(SubclassTableSeeder::class);

        // в схеме (+), в Fb (+), но поля отличаются
        // ниже basevoltage, subclass, classname
        //$this->call(IdentifiedobjectTableSeeder::class); // сперва нужно заполнить asset. И здесь много дргуих связей

        // в схеме (+), в Fb (-), но есть похожая - PTALARMDEVICES, импортирую из нее
        $this->call(AlarmdeviceTableSeeder::class);

        // в схеме (+), в Fb (-), но здесь для садреса она нужна - поэтому вставил демо-данные: здание-1, здание-2, здание-3...
        $this->call(BuildingTableSeeder::class);

        // в схеме (+), в Fb (+), но в ней только 2-е строчки - поэтому вставил демо-данные: адрес-1, адрес-2, адрес-3...
        $this->call(AddressTableSeeder::class);

        // в схеме (+), в Fb (+), в ней только 1 строка Администратор - поэтому вставил демо-данные
        $this->call(UserTableSeeder::class);

        // в схеме (-), в Fb (-), создал новую - для устройств планшетов пользователей
        // ниже пользователей должно быть
        $this->call(DeviceTableSeeder::class);

        // в схеме (+), в Fb (-), но есть похожая - MATERIALKIND с данными, импортирую из нее - НО!!! Уже создал новую таблицу Materialkind
        // seed-ер теперь не требуется
        // $this->call(MaterialTableSeeder::class);

        // в схеме (+), в Fb (+), но она пустая - поэтому вставил демо-данные: материал опоры-1, материал опоры-2...
        $this->call(TowermaterialTableSeeder::class);

        // в схеме (-), в Fb (+), но там только 2 записи не те - поэтому вставил свои данные как надо
        $this->call(TowerconstructionkindTableSeeder::class);

        // в схеме (+), в Fb (-), но она нужна для File, поэтому вставил демо-данные: тип изображения-1, тип изображения-2...
        // в Fb есть похожая PICTUREKIND с данными - уже создал ее Picturekind
        $this->call(PicturetypeTableSeeder::class);

        // в схеме (+), в Fb (-), но она нужна для Task, поэтому вставил демо-данные: тип задачи-1, тип задачи-2...
        $this->call(TasktypeTableSeeder::class);

        // в схеме (+), в Fb (+), но она пустая, поэтому вставил демо-данные: информация о подстанции-1, информация о подстанции-2...
        // в Fb есть похожая SUBSTATIONTPINFO с данными - уже создал ее Substationtpinfo
        $this->call(SubstationinfoTableSeeder::class);

        // в схеме (+), в Fb (-), но есть похожая - SUBSTATIONFUNCTIONKIND, импортирую из нее - НО!!! Уже создал новую таблицу Substationfunctionkind
        // seed-ер теперь не требуется
        $this->call(SubstationfunctionTableSeeder::class);

        // в схеме (+), в Fb (-), но она нужна для Task, поэтому вставил демо-данные: файл-1, файл-2...
        // ниже IO
        //$this->call(ConnectorTableSeeder::class);

        // в схеме (+), в Fb (+), но в ней нет данных?, поэтому вставил демо-данные
        // ниже IO, connector
        //$this->call(AclineTableSeeder::class);

        // в схеме (?), в Fb (?), для карт понадобилось, связал с линиями acline
        // ниже IO, towermaterial, towerkind, towerinfo, acline
        //$this->call(TowerTableSeeder::class);

        // в схеме (?), в Fb (?), для карт понадобилось, связал с опорами tower
        // ниже IO, tower
        //$this->call(SpanTableSeeder::class);

        // в схеме (+), в Fb (+), но в ней 1 строчка, поэтому вставил демо-данные: (имени нет, только паспорт) паспорт №.., паспорт №..
        // ниже IO
        //$this->call(SubstationTableSeeder::class);

        // в схеме (-), в Fb (+), для карт понадобилось, связал с IO
        // ниже IO
        //$this->call(CustomerTableSeeder::class);

        // в схеме (-), в Fb (-), но она нужна для Task, поэтому создал новую и вставил демо-данные: конечная точка-1, конечная точка-2...
        // ниже IO
        //$this->call(EndpointTableSeeder::class);

        // в схеме (+), в Fb (-), поэтому вставил демо-данные: задача-1, задача-2...
        //$this->call(TaskTableSeeder::class);

        // в схеме (+), в Fb (-), но она нужна для Task, поэтому вставил демо-данные: файл-1, файл-2...
        // ниже task должно быть
        //$this->call(FileTableSeeder::class);

        // в схеме (-), в Fb (-), но она нужна для Task, поэтому вставил демо-данные: файл-1, файл-2...
        // ниже task, file должно быть
        //$this->call(TaskFilePivotTableSeeder::class);

        // в схеме (-), в Fb (-), но она нужна для Connector, поэтому вставил демо-данные: файл-1, файл-2...
        // ниже connector, file должно быть
        //$this->call(ConnectorFilePivotTableSeeder::class);

        // --------------------------------------------------------
        // задачи
        $this->call(TodoStatusTableSeeder::class);
        $this->call(TodoStageTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(FioTableSeeder::class);
        $this->call(TodoTableSeeder::class);
        $this->call(TodoStageFioPivotTableSeeder::class);
        $this->call(EnproVehicleTableSeeder::class);
        $this->call(EnproToolTableSeeder::class);
        $this->call(AssetGroupKindTableSeeder::class);
        $this->call(KindTablesSeeder::class);
        $this->call(GostTableSeeder::class);
        $this->call(UnitMultiplierSeeder::class);
        $this->call(UnitSymbolSeeder::class);
        echo "\n" . "ЗАПОЛНЕНИЕ УСПЕШНО ТАБЛИЦ ЗАВЕРШЕНО!" . "\n";
    }
}
