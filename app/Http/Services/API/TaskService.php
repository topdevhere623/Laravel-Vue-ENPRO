<?php

namespace App\Http\Services\API;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

// мои сервисы

// модели
use App\Models\Task;

// сервис Task
class TaskService
{
    // обновление файла json после изменения координат на карте (в первоисточнике и в новом "легком")
    public function updateTaskJsonMap($getTaskId, $getMyArrPoints)
    {
        // два прохода: для сохранения в оригинале и в новом файле
        for ($i = 1; $i <= 2; $i++) {

            if ($i == 1) {
                $jsonType = 'original';
                $fieldLat = 'latitude';
                $fieldLong = 'longitude';
            } else {
                $jsonType = 'new';
                $fieldLat = 'lat';
                $fieldLong = 'long';
            }

            // открыть json-файл по id-задачи (исходник или новый "легкий")
            $return = self::jsonLoadTask($getTaskId, $jsonType);
            $jsonFile = $return['jsonFile'];
            $jsonNameFileForSave = ($jsonType == 'original') ? $return['fileNameJsonOriginal'] : $return['fileNameJsonNew'];

            // просканировать все точки
            foreach ($getMyArrPoints as $item) {

                $lat = $item['lat'];
                $long = $item['long'];
                $par1 = $item['json1'];
                $par2 = $item['json2'];

                // вставить данные в исходный json файл
                switch ($item['comment']) {
                    case 'начальная точка':

                        $jsonFile['startPoint']['lat'] = $lat;
                        $jsonFile['startPoint']['long'] = $long;

                        break;
                    case 'точка':

                        $jsonFile['points'][$par1]['lat'] = $lat;
                        $jsonFile['points'][$par1]['long'] = $long;
                        break;

                    case 'потребитель':
                        $jsonFile['points'][$par1]['consumerInputDTOS'][$par2][$fieldLat] = $lat;
                        $jsonFile['points'][$par1]['consumerInputDTOS'][$par2][$fieldLong] = $long;
                        break;
                }
            }

            // сохранить этот измененный json
            $jsonFile = json_encode($jsonFile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
            file_put_contents(public_path() . '/' . $jsonNameFileForSave, $jsonFile);
        }

        // возвращаемый параметр
        return
            [
                'getTaskId' => $getTaskId,
            ];
    }

    // открыть и декодировать json-файл по его имени
    public function loadJson($fileName)
    {
        // содержимое json-файла
        $jsonFile = file_get_contents(public_path() . '/' . $fileName);

        // Gzip-распаковка (декодирует строку, сжатую с помощью gzip)
        if (false) {
            try {
                $jsonFile = gzdecode($jsonFile); // gzdecode()
            } catch (\Exception $e) {
                // ошибка
            }
        }

        // base64-декодирование файла
        if (false) {
            $jsonFile = base64_decode($jsonFile);
        }

        // преобразовать в массив
        $jsonFile = json_decode($jsonFile, true);

        // !!!!!!!!!! временно Михаил массив в массиве присылает
        if (isset($jsonFile[0])) {
            $jsonFile = $jsonFile[0];
        }
        // !!!!!!!!!! временно, чтобы старые json-ы с task, substation, connectors открывались
        if (isset($jsonFile['connectors'])) {
            $jsonFile = $jsonFile['connectors'];
        }

        // получение имени директории для нового json-ом и его имени
        $return = self::jsonNames($fileName);

        // возвращаемый параметр
        return
            [
                'jsonFile' => $jsonFile,
                'fileNameJsonOriginal' => $return['fileNameJsonOriginal'],
                'fileNameJsonNew' => $return['fileNameJsonNew'],
                'dirNameJsonNew' => $return['dirNameJsonNew'],
            ];
    }

    // открыть json-файл по id-задачи (исходник или новый "легкий")
    public function jsonLoadTask($getTaskId, $jsonType = 'original')
    {
        // получить имя json-файла задачи
        $task = Task::findOrFail($getTaskId);
        $fileNameJsonOriginal = $task->folderPath() . '/' . $task->json_file;

        // получение имени директории для нового json-ом и его имени
        $return = self::jsonNames($fileNameJsonOriginal);
        $dirNameJsonNew = $return['dirNameJsonNew'];
        $fileNameJsonNew = $return['fileNameJsonNew'];

        // открыть и декодировать json-файл по его имени
        $fileName = ($jsonType == 'original') ? $fileNameJsonOriginal : $fileNameJsonNew;
        $return = self::loadJson($fileName);
        $jsonFile = $return['jsonFile'];

        // возвращаемый параметр
        return
            [
                'jsonFile' => $jsonFile,
                'fileNameJsonOriginal' => $fileNameJsonOriginal,
                'fileNameJsonNew' => $fileNameJsonNew,
                'dirNameJsonNew' => $dirNameJsonNew,
            ];
    }

    // получение имени директории для нового json-ом и его имени
    // uploads/models/task/1/20200722_025801.json
    public function jsonNames($fileName)
    {
        // директория этого файла, где лежит распарсенная информация
        // uploads/models/task/1/20200722_025801/ - без расширения и со слешем
        $dirNameJsonNew = str_replace('.' . pathinfo($fileName, PATHINFO_EXTENSION), '', $fileName) . '/';

        // имя нового легкого json-файла
        // uploads/models/task/1/20200722_025801/parsed.json - с новым именем
        $fileNameJsonNew = $dirNameJsonNew . 'parsed.json';

        // возвращаемый параметр
        return
            [
                'fileNameJsonOriginal' => $fileName,
                'fileNameJsonNew' => $fileNameJsonNew,
                'dirNameJsonNew' => $dirNameJsonNew,
            ];
    }

    // получение списка всех имеющихся json-файлов в папке конкретной модели task или всех
    public function jsonFiles($taskN = null)
    {
        // начальное значение
        $content = [];

        if (is_null($taskN)) {
            // все задачи

            // директория для сканирвоания
            $taskDir = 'uploads/models/task/';
            // директории всех задач
            $dirs = Storage::disk('public')->directories($taskDir);
            foreach ($dirs as $dir) {
                // номер текущей задачи
                $taskN = str_replace($taskDir, '', $dir);

                // получить список файлов в текущей директории
                $allFiles = Storage::disk('public')->Files($dir);
                foreach ($allFiles as $file) {
                    // расширение текущего файла
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    if ($ext == 'json') {
                        // узнать размер файла
                        $size = File::size($file);
                        // записать в итоговый массив
                        $content [] = ['taskN' => $taskN, 'file' => $file, 'size' => $size];
                    }
                }
            }
        } else {
            // указана конкретная задача

            // директория для сканирвоания
            $taskDir = 'uploads/models/task/' . $taskN . '/';
            // файлы в папке задачи
            $allFiles = Storage::disk('public')->Files($taskDir);
            foreach ($allFiles as $file) {
                // расширение текущего файла
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if ($ext == 'json') {
                    // узнать размер файла
                    $size = File::size($file);
                    // записать в итоговый массив
                    $content [] = ['taskN' => $taskN, 'file' => $file, 'size' => $size];
                }
            }
        }

        // открыть вьюшку
        return $content;
    }

    // удаление json-файла, его папки после парсинга со всеми изображениями и новым легким json-ом
    public function jsonDelete($fileName)
    {
        // получение имени директории для нового json-ом и его имени
        $return = self::jsonNames($fileName);
        $dirNameJsonNew = $return['dirNameJsonNew'];
        // удаление всех файлов из этой папки
        $files = Storage::disk('public')->allFiles($dirNameJsonNew);
        Storage::disk('public')->delete($files);
        // удаление пустой папки
        Storage::disk('public')->deleteDirectory($dirNameJsonNew);
        // удаление оригинала
        Storage::disk('public')->delete($fileName);
    }

    // получить обьект Point
    // из принятого json-а - это startPoint
    public function getPoint($startPoint)
    {
        $files = [];
        if (isset($startPoint['files']) and count($startPoint['files']) > 0) {
            foreach ($startPoint['files'] as $key => $item) {
                $files [] = $item;
            }
        }

        // обьект Point (точка)
        $Point [] =
            [
                'id' => isset($startPoint['id']) ? $startPoint['id'] : '',                      // id
                'type' => isset($startPoint['type']) ? $startPoint['type'] : '',                // тип начальной точки: 1 - ТП, 2 - опора
                'name' => isset($startPoint['name']) ? $startPoint['name'] : '',                // название (ts добавил после)
                'address' => isset($startPoint['address']) ? $startPoint['address'] : '',       // адрес
                'lat' => isset($startPoint['lat']) ? $startPoint['lat'] : '',                   // долгота
                'long' => isset($startPoint['long']) ? $startPoint['long'] : '',                // широта
                'comments' => isset($startPoint['comments']) ? $startPoint['comments'] : '',    // комментарий
                'files' => $files,                                                              // изображения
            ];

        // возвращаемый параметр
        return $Point;
    }

    // получить обьект LineSegment (линия, шаг 1 из 6)
    public function getLineSegment($request)
    {
        // воздушные линии
//        if (count($lines701) > 0) {
//
//        } else {
//
//        }
//
//        // кабельные линии
//        if (count($lines702) > 0) {
//
//        } else {
//
//        }


        // переходы и пересечения
        $transAndInterpas [] =
            [
                'type' => 1,                                // тип (1 - переход, 2 - пересечение)
                'nameOfCrossed' => 'Река Реж',              // наименование
                'files' => ['', '', ''],  // фото
            ];

        // характерные точки
        $points [] =
            [
                'no' => 1,              // тип (1 - переход, 2 - пересечение)
                'depth' => 'Река Реж',  // наименование
                'lat' => 57.77,         // долгота
                'long' => 52.33,        // широта
            ];

        // изображения
        $files [] =
            [
                '',
                '',
                ','
            ];

        $pointType = '701';
        switch ($pointType) {
            case '701':
                // это воздушная линия

                // список кабельных линий
                $cables [] =
                    [
                        [
                            'mark' => 1,                        // марка провода id
                            'markName' => 'ААБ-150(6)',         // марка провода
                            's' => 5.2,                         // сечение
                            'clearanceabov' => 100.5,           // габарит
                            'spanLength' => 200.3,              // длина пролета
                            'wireNo' => 3,                      // кол-во проводов
                            'wireLength' => 7.1,                // длина провода
                            'lat' => 53.33,                     // долгота ????
                            'long' => 77.11,                    // широта ???
                            'cableBox_1' => 0,                  // id из таблицы “Cableboxkind” (муфты) или 0 если нет ???
                            'cableBox_2' => 0,                  // id из таблицы “Cableboxkind” (муфты) или 0 если нет ???
                            'files' => ['', ''],    // изображения
                        ],
                        [
                            'mark' => 3,                        // марка провода id
                            'markName' => 'ААБ-10(6)',          // марка провода
                            's' => 5.2,                         // сечение
                            'clearanceabov' => 100.5,           // габарит
                            'spanLength' => 200.3,              // длина пролета
                            'wireNo' => 3,                      // кол-во проводов

                            'wireLength' => 7.1,                // длина провода
                            'lat' => 53.33,                     // долгота ????
                            'long' => 77.11,                    // широта ???
                            'cableBox_1' => 0,                  // id из таблицы “Cableboxkind” (муфты) или 0 если нет ???
                            'cableBox_2' => 0,                  // id из таблицы “Cableboxkind” (муфты) или 0 если нет ???
                            'files' => ['', ''],    // изображения
                        ]
                    ];

                // обьект LineSegment (линия)
                $LineSegment [] =
                    [
                        'startPoint' => 1,                          // начальная точка
                        'endPoint' => 2,                            // конечная точка
                        'type' => 701,                              // тип линии (701 - воздушная, 702 - кабельная)
                        'issubline' => true,                        // отпайка
                        'cables' => $cables,                        // список кабельных линий
                        'transAndInterpas' => $transAndInterpas,    // переходы и пересечения
                        'files' => $files,                          // изображения
                    ];

                break;
            case '702':
                // это кабельная линия

                // список кабельных линий
                $cables [] =
                    [
                        [
                            'mark' => 1,                // марка провода id
                            'markName' => 'ААБ-150(6)', // марка провода ???
                            'nf' => 3,                  // колв-о проводов в фазе
                            'cabelsNo' => 4,            // кол-во проводов в траншее
                            'areneLength' => 100,       // длина участка
                            'layingCondition' => 1,     // id условие прокладки
                        ],
                        [
                            'mark' => 3,                // марка провода id
                            'markName' => 'ААБ-10(6)',  // марка провода ???
                            'nf' => 3,                  // колв-о проводов в фазе
                            'cabelsNo' => 4,            // кол-во проводов в траншее
                            'areneLength' => 100,       // длина участка
                            'layingCondition' => 1,     // id условие прокладки
                        ]
                    ];

                // обьект LineSegment (линия)
                $LineSegment [] =
                    [
                        'startPoint' => 1,      // начальная точка
                        'endPoint' => 2,        // конечная точка
                        'type' => 702,          // тип линии (701 - воздушная, 702 - кабельная)
                        'issubline' => true,    // отпайка
                        'cables' => $cables,    // список кабельных линий
                        'points' => $points,    // хараетернеы точки
                        'files' => $files,      // изображения
                    ];

                break;
        }

        // возвращаемый параметр
        return $LineSegment;
    }

    // получить обьект newPoint (новая точка, шаг 2 из 6)
    // из принятого json-а - это point
    public function getNewPoint($points)
    {
        $newPoint = [];
        if (isset($points) and count($points) > 0) {
            foreach ($points as $key => $item) {
                // обьект $newPoint
                $newPoint [] =
                    [
                        'id' => isset($item['id']) ? $item['id'] : '',                                                          // id
                        'type' => isset($item['type']) ? $item['type'] : '',                                                    // тип начальной точки: 1 - ТП, 2 - опора
                        'name' => isset($item['dispatcherName']) ? $item['dispatcherName'] : '',                                // диспетчесркое имя
                        'phaseNo' => isset($item['phaseno']) ? $item['phaseno'] : '',                                           // кол-во фаз
                        'address' => isset($item['address']) ? $item['address'] : '',                                           // адрес
                        'lat' => isset($item['lat']) ? $item['lat'] : '',                                                       // долгота
                        'long' => isset($item['long']) ? $item['long'] : '',                                                    // широта
                        'mainView' => isset($item['mainview']) ? $item['mainview'] : '',      // основное фото
                        //'identViews' => isset($item['identview']) ? $item['identview'] : '', // информационный знак(-и)
                    ];
            }
        }

        // возвращаемый параметр
        return $newPoint;
    }

    // получить обьект Tower (опора, шаг 3 из 6)
    public function getTower($request)
    {
        // обьект Tower
        $Tower [] =
            [
                'subClass' => 1,                            // назначение
                'mark' => 2,                                // марка опоры id
                'towerMaterial' => 3,                       // материад опоры
                'phaseNo' => 4,                             // кол-во фаз
                'baseKind' => 5,                            // тип фундамента
                'baseAmount' => 6,                          // кол-во фкндаментов
                'baseMaterial' => 7,                        // материал приставки
                'annexNo' => 8,                             // кол-во приставок
                'strutMaterial' => 9,                       // материал подкоса
                'strutNo' => 10,                            // кол-во подкосов
                'woodbReserv' => 'характер консервации',    // характер консервации древесины
                'guy' => false,                             // оттяжка
                'grounding' => true,                        // заземление
            ];

        // возвращаемый параметр
        return $Tower;
    }

    // получить обьект TowerMounting (подвес опоры, ша 4 из 6)
    public function getTowerMounting($request)
    {
        // заземление
        $groinding [] =
            [
                [
                    'groundingType' => 1,       // назначение заземления
                    'constructionType' => 2,    // конструктив заземления
                    'resist' => 3,              // сопротивление
                    'onTowerMaterial' => 4,     // материал на опоре
                    'inearMaterial' => 5,       // материал в земле
                    'priming' => 6,             // грунт
                ],
                [
                    'groundingType' => 7,       // назначение заземления
                    'constructionType' => 8,    // конструктив заземления
                    'resist' => 9,              // сопротивление
                    'onTowerMaterial' => 10,    // материал на опоре
                    'inearMaterial' => 11,      // материал в земле
                    'priming' => 12,            // грунт
                ]
            ];

        // файлы с комментариями
        $files [] =
            [
                [
                    'file' => '',                   // файл
                    'comment' => 'комментарий-5',   // комментарий
                ],
                [
                    'file' => '',                   // файл
                    'comment' => 'комментарий-6',   // комментарий
                ],
                [
                    'file' => '',                   // файл
                    'comment' => 'комментарий-7',   // комментарий
                ]
            ];

        // обьект TowerMounting
        $TowerMounting [] =
            [
                'insulatorMounting' => 1,               // способ крепления изоляторов
                'insulatorNo' => 2,                     // кол-во изоляторов
                'preservativeKind' => 3,                // защитное покрытие изоляторов
                'insulatorMarkind' => 4,                // тип изоляторов
                'protDevType' => 5,                     // класс защиты от перенапряжения
                'protDevMark' => 6,                     // марка защиты от перенапряжения
                'lampsNo' => 7,                         // кол-во светильников
                'comments' => 'комментарий по подвесу', // комментарий
                'groinding' => $groinding,              // заземление
                'files' => $files,                      // файлы с комментриями
            ];

        // возвращаемый параметр
        return $TowerMounting;
    }

    // получить обьект PotrebitelPoint
    public function getPotrebitelPoint($request)
    {
        // изображения
        $files [] =
            [
                '',
                '',
                ','
            ];

        // обьект PotrebitelPoint (ввод к потребителю)
        $PotrebitelPoint [] =
            [
                'type' => 1,                        // тип - 1 – ТП, 2 – опора, 3 - ввод в здание, 4 - ввод к Абоненту (по умолчанию)
                'address' => 'ул.Ленина, 48',       // адрес
                'lat' => 57.77,                     // долгота
                'long' => 52.33,                    // широта
                'name' => 'Ленина, 48',             // наименование
                'files' => $files,                  // изображения
            ];

        // возвращаемый параметр
        return $PotrebitelPoint;
    }
}

