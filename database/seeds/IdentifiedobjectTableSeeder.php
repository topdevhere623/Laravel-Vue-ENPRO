<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

// мои сервисы
use App\Http\Services\backend\ModelService;

// модели
use App\Models\Identifiedobject;
use App\Models\Classname;

class IdentifiedobjectTableSeeder extends Seeder
{
    // подключение сервисов
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function run()
    {
        // для substation
        // ***********************************************************************************
        // сперва получить список id
        $classname_id = \App\Models\Classname::where('classname', 'Substation')->get();

        // данные от Анатолия
        for ($i = 1; $i <= 3; $i++) {
            switch ($i) {
                case 1:
                    $myName = 'ТП-51Б';
                    $myDescription = 'Описание ТП-51Б';
                    $myAddress = 'ул.Калинина 38\1';
                    $myLat = 57.340297;
                    $myLong = 61.335189;
                    break;
                case 2:
                    $myName = 'КТПН-59';
                    $myDescription = 'Описание КТПН-59';
                    $myAddress = 'ул. Садовая 1а-11,2-10';
                    $myLat = 57.343167;
                    $myLong = 61.363198;
                    break;
                case 3:
                    $myName = 'КТПН-59';
                    $myDescription = 'Описание КТПН-59';
                    $myAddress = 'ул. Южная, Березовая роща';
                    $myLat = 57.339894;
                    $myLong = 61.361995;
                    break;
            }

            $myId = $i;
            $myLocalName = $myId;
            $myClassnameId = $classname_id[0]->id;

            $data = factory(\App\Models\Identifiedobject::class, 1)->make()->each(function ($content) use ($myId, $myLocalName, $myName, $myDescription, $myAddress, $myLat, $myLong, $myClassnameId) {
                $content->id = $myId;
                $content->localname = $myLocalName;
                $content->name = $myName;
                $content->description = $myDescription;
                $content->address = $myAddress;
                $content->lat = $myLat;
                $content->long = $myLong;
                $content->classname_id = $myClassnameId;
            })->toArray();
            \App\Models\Identifiedobject::insert($data);
        }



        // для connector
        // ***********************************************************************************
        // сперва получить список id
        $classname_id = \App\Models\Classname::where('classname', 'Connector')->get();

        // данные от Анатолия
        for ($i = 1; $i <= 3; $i++) {
            switch ($i) {
                case 1:
                    $myName = 'Фидер 3 яч. 1 0.4 кВ';
                    $myDescription = 'Описание Фидер 3';
                    $myAddress = 'ул.Калинина 38\1';
                    $myLocalName = 'ул.Калинина 38\1';
                    $myLat = 57.340297;
                    $myLong = 61.335189;
                    break;
                case 2:
                    $myName = 'Фидер 2 яч. 5 0.4 кВ';
                    $myDescription = 'Описание Фидер 2';
                    $myAddress = 'ул. Садовая 1а-11,2-10';
                    $myLocalName = 'ул. Садовая 1а-11,2-10';
                    $myLat = 57.343167;
                    $myLong = 61.363198;
                    break;
                case 3:
                    $myName = 'Фидер 5 яч. 3 0.4 кВ';
                    $myDescription = 'Описание Фидер 5';
                    $myAddress = 'ул. Южная, Березовая роща';
                    $myLocalName = 'ул. Южная, Березовая роща';
                    $myLat = 57.339894;
                    $myLong = 61.361995;
                    break;
            }

            $myId = ($i + 3);
            $myClassnameId = $classname_id[0]->id;

            $data = factory(\App\Models\Identifiedobject::class, 1)->make()->each(function ($content) use ($myId, $myLocalName, $myName, $myDescription, $myAddress, $myLat, $myLong, $myClassnameId) {
                $content->id = $myId;
                $content->localname = $myLocalName;
                $content->name = $myName;
                $content->description = $myDescription;
                $content->address = $myAddress;
                $content->lat = $myLat;
                $content->long = $myLong;
                $content->classname_id = $myClassnameId;
            })->toArray();
            \App\Models\Identifiedobject::insert($data);
        }


        // для enpoint
        // ***********************************************************************************
        // сперва получить список id
        $classname_id = \App\Models\Classname::where('classname', 'Endpoint')->get();

        // данные от Анатолия
        for ($i = 1; $i <= 5; $i++) {
            switch ($i) {
                case 1:
                    $myName = 'Конечная точка - Калинина, 12';
                    $myDescription = 'Описание конечной точки на Калинина, 12';
                    $myAddress = 'Калинина, 12';
                    $myLat = 57.340297;
                    $myLong = 61.335189;
                    break;
                case 2:
                    $myName = 'Конечная точка - Садовая, 11';
                    $myDescription = 'Описание конечной точки на Садовой, 11';
                    $myAddress = 'Садовая, 11';
                    $myLat = 57.343167;
                    $myLong = 61.363198;
                    break;
                case 3:
                    $myName = 'Конечная точка - Садовая, 10';
                    $myDescription = 'Описание конечной точки на Садовой, 10';
                    $myAddress = 'Садовая, 10';
                    $myLat = 57.343206;
                    $myLong = 61.363755;
                    break;
                case 4:
                    $myName = 'Конечная точка - Южная, 15';
                    $myDescription = 'Описание конечной точки на Южной, 15';
                    $myAddress = 'Южная, 15';
                    $myLat = 57.339894;
                    $myLong = 61.361995;
                    break;
                case 5:
                    $myName = 'Конечная точка - Березовая роща, 9';
                    $myDescription = 'Описание конечной точки на Березовой роще, 9';
                    $myAddress = 'Березовая роща, 9';
                    $myLat = 57.340724;
                    $myLong = 61.361546;
                    break;
            }

            $myId = ($i + 6);
            $myLocalName = $myId;
            $myClassnameId = $classname_id[0]->id;

            $data = factory(\App\Models\Identifiedobject::class, 1)->make()->each(function ($content) use ($myId, $myLocalName, $myName, $myDescription, $myAddress, $myLat, $myLong, $myClassnameId) {
                $content->id = $myId;
                $content->localname = $myLocalName;
                $content->name = $myName;
                $content->description = $myDescription;
                $content->address = $myAddress;
                $content->lat = $myLat;
                $content->long = $myLong;
                $content->classname_id = $myClassnameId;
            })->toArray();
            \App\Models\Identifiedobject::insert($data);
        }


        // для tower
        // ***********************************************************************************
        // сперва получить список id
        $classname_id = \App\Models\Classname::where('classname', 'Tower')->get();

        // мои данные
        for ($i = 1; $i <= 10; $i++) {
            $myName = 'Опора - '.$i;
            $myDescription = 'Описание опоры - '.$i;
            switch ($i) {
                case 1:
                    $myLat = 56.58987874959108;
                    $myLong = 58.725253906244596;
                    break;
                case 2:
                    $myLat = 57.06681661610295;
                    $myLong = 59.12526855468492;
                    break;
                case 3:
                    $myLat = 57.202325577936406;
                    $myLong = 60.04713378905991;
                    break;
                case 4:
                    $myLat = 57.39670028505315;
                    $myLong = 60.85913574218411;
                    break;
                case 5:
                    $myLat = 57.501443380626654;
                    $myLong = 61.605219726557245;
                    break;
            }

            $myId = ($i + 11);
            $myLocalName = $myId;
            $myClassnameId = $classname_id[0]->id;

            $data = factory(\App\Models\Identifiedobject::class, 1)->make()->each(function ($content) use ($myId, $myLocalName, $myName, $myDescription, $myLat, $myLong, $myClassnameId) {
                $content->id = $myId;
                $content->localname = $myLocalName;
                $content->name = $myName;
                $content->description = $myDescription;
                $content->lat = $myLat;
                $content->long = $myLong;
                $content->classname_id = $myClassnameId;
            })->toArray();
            \App\Models\Identifiedobject::insert($data);
        }

        // для acline (ЛЭП)
        // ***********************************************************************************
        // сперва получить список id
        $classname_id = \App\Models\Classname::where('classname', 'Acline')->get();

        // мои данные
        for ($i = 1; $i <= 3; $i++) {
            $myName = 'ЛЭП - '.$i;
            $myDescription = 'Описание ЛЭП - '.$i;

            $myId = ($i + 21);
            $myLocalName = $myId;
            $myClassnameId = $classname_id[0]->id;

            $data = factory(\App\Models\Identifiedobject::class, 1)->make()->each(function ($content) use ($myId, $myLocalName, $myName, $myDescription, $myClassnameId) {
                $content->id = $myId;
                $content->localname = $myLocalName;
                $content->name = $myName;
                $content->description = $myDescription;
                $content->classname_id = $myClassnameId;
            })->toArray();
            \App\Models\Identifiedobject::insert($data);
        }

        // для span (сегмента)
        // ***********************************************************************************
        // сперва получить список id
        $classname_id = \App\Models\Classname::where('classname', 'Span')->get();

        // мои данные
        for ($i = 1; $i <= 5; $i++) {
            $myName = 'Пролет - '.$i;
            $myDescription = 'Описание пролета - '.$i;

            $myId = ($i + 24);
            $myLocalName = $myId;
            $myClassnameId = $classname_id[0]->id;

            $data = factory(\App\Models\Identifiedobject::class, 1)->make()->each(function ($content) use ($myId, $myLocalName, $myName, $myDescription, $myClassnameId) {
                $content->id = $myId;
                $content->localname = $myLocalName;
                $content->name = $myName;
                $content->description = $myDescription;
                $content->classname_id = $myClassnameId;
            })->toArray();
            \App\Models\Identifiedobject::insert($data);
        }

        // для customer (потребители)
        // ***********************************************************************************
        // сперва получить список id
        $classname_id = \App\Models\Classname::where('classname', 'Customer')->get();

        // мои данные
        for ($i = 1; $i <= 5; $i++) {
            $myName = 'Потребитель - '.$i;
            $myDescription = 'Описание потребителя - '.$i;

            $myId = ($i + 29);
            $myLocalName = $myId;
            $myClassnameId = $classname_id[0]->id;

            $data = factory(\App\Models\Identifiedobject::class, 1)->make()->each(function ($content) use ($myId, $myLocalName, $myName, $myDescription, $myClassnameId) {
                $content->id = $myId;
                $content->localname = $myLocalName;
                $content->name = $myName;
                $content->description = $myDescription;
                $content->classname_id = $myClassnameId;
            })->toArray();
            \App\Models\Identifiedobject::insert($data);
        }

    }
}
