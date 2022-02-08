<?php

namespace App\Http\Services\API;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

// мои сервисы
use App\Http\Services\API\TaskService;

// модели
use App\Models\Task;

// сервис парсинга json-файла task
class TaskParseService
{
    // подключение сервисов
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    // глобальные переменнеы внутри класса
    // *******************************************************************
    // текст комментария
    private static $Comment = '';

    public function setComment($Comment)
    {
        self::$Comment = $Comment;
    }

    public function getComment()
    {
        return self::$Comment;
    }

    public function updateComment($Comment)
    {
        self::$Comment .= $Comment;
    }
    // *******************************************************************
    // директория с фото (для сохранения и для путей <img>)
    private static $dir = '';

    public function setdir($dir)
    {
        self::$dir = $dir;
    }

    public function getdir()
    {
        return self::$dir;
    }

    // распарсить полученный json-файл от задачи task (картинки сохранить на диске)
    // имя файла приходит в таком виде: uploads/models/task/1/20200720_015847.json
    public function parseJsonFile($getJsonFileName)
    {
        // проверка на получение данных
        if (is_null($getJsonFileName)) return ['message' => "Ошибка! Не получено имя json-файла!", 'code' => 400];

        // проверить наличие файла на диске
        if (!file_exists(public_path() . '/' . $getJsonFileName)) return ['message' => "Ошибка! Файл [" . $getJsonFileName . "] не обнаружен на диске!", 'code' => 400];

        // открыть и декодировать json-файл по его имени
        $return = $this->taskService->loadJson($getJsonFileName);
        $jsonFile = $return['jsonFile'];
        $dirNameJsonNew = $return['dirNameJsonNew'];
        // создать папку с таким же именем (в нее будут сохраняться полученные изображения и новый "легкий" json)
        if (!File::exists($dirNameJsonNew)) {
            File::makeDirectory($dirNameJsonNew, 0755, true, true);
        }

        // начальное значение
        self::setComment('');
        self::setdir($dirNameJsonNew);

        // распарсить обьекты
        $object_new = self::parseObject($jsonFile);

        // сохранить новый "легкий" json на дсике
        self::saveNewJsonInDisk($object_new);

        // возвращаемый параметр
        return [
            'object' => $object_new,
            'comment' => self::getComment(),
            'message' => "Файл успешно распарсен (фото разделены от текста, они сохранены на диске)!",
            'code' => 200];
    }

    // сохранить новый "легкий" json на дсике
    public function saveNewJsonInDisk($object_new)
    {
        $jsonNew = json_encode($object_new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
        file_put_contents(public_path() . '/' . self::getdir() . 'parsed.json', $jsonNew);
    }

    // сохранить в задаче комментарий парсинга - !!!! сейчас не используется
    public function saveCommentInTask($getTaskId)
    {
        $taskSave = Task::find($getTaskId);
        $taskSave->json_parse_text = self::getComment();
        $taskSave->save();
    }

    // распарсить обьекты
    public function parseObject($object)
    {
        // общие данные
        $object_new =
            [
                'uuid' => isset($object['id']) ? $object['id'] : '',
                'localname' => isset($object['localName']) ? $object['localName'] : '',
                'name' => isset($object['name']) ? $object['name'] : '',
                'voltage_id' => isset($object['voltage_id']) ? $object['voltage_id'] : '',
                'voltage' => isset($object['voltage']) ? $object['voltage'] : '',
            ];

        // обновить комментарий
        self::updateComment(
            "<p><strong>Задача:</strong>
        <p>
            - uuid задачи: " . (isset($object['id']) ? $object['id'] : '') . "<br>
            - диспетчерский номер: " . (isset($object['localName']) ? $object['localName'] : '') . "<br>
            - диспетчерское имя: " . (isset($object['name']) ? $object['name'] : '') . "<br>
            - базовое напряжение id: " . (isset($object['voltage_id']) ? $object['voltage_id'] : '') . "<br>
            - базовое напряжение: " . (isset($object['voltage']) ? $object['voltage'] : '') . "
        </p>"
        );

        // распарсить points
        $object_new['points'] = array_key_exists('points', $object) ? self::parsePoints($object['points']) : [];
        // распарсить lines701
        $object_new['lines701'] = array_key_exists('lines701', $object) ? self::parseLines701($object['lines701']) : [];
        // распарсить lines702
        $object_new['lines702'] = array_key_exists('lines702', $object) ? self::parseLines702($object['lines702']) : [];

        // обновить комментарий
        self::updateComment("</p>");

        // возвращаемый параметр
        return $object_new;
    }

    // распарсить points
    public function parsePoints($object)
    {
        // начальное значение
        $points_new = [];

        // точки
        if (count($object) > 0) {

            // обновить комментарий
            self::updateComment(
                "<p>
                <strong>Точки:</strong>"
            );

            foreach ($object as $key => $item) {

                $points_new [] =
                    [
                        'id' => isset($item['id']) ? $item['id'] : '',
                        'type' => isset($item['type']) ? $item['type'] : '',
                        'address' => isset($item['address']) ? $item['address'] : '',
                        'dispatcherName' => isset($item['dispatcherName']) ? $item['dispatcherName'] : '',
                        'lat' => isset($item['lat']) ? $item['lat'] : '',
                        'long' => isset($item['long']) ? $item['long'] : '',
                        'phaseNo' => isset($item['phaseno']) ? $item['phaseno'] : '',
                    ];

                // обновить комментарий
                self::updateComment(
                    "<br><br>
                    <strong>Точка id: " . (isset($item['id']) ? $item['id'] : '') . "</strong><br>
                    - тип: " . (isset($item['type']) ? $item['type'] : '') . "<br>
                    - адрес: " . (isset($item['address']) ? $item['address'] : '') . "<br>
                    - диспетчерское имя: " . (isset($item['dispatcherName']) ? $item['dispatcherName'] : '') . "<br>
                    - долгота: " . (isset($item['lat']) ? $item['lat'] : '') . "<br>
                    - широта: " . (isset($item['long']) ? $item['long'] : '') . "<br>
                    - кол-во фаз: " . (isset($item['phaseno']) ? $item['phaseno'] : '') . "<br>"
                );

                // основное фото
                if (isset($item['mainview']) and !is_null($item['mainview'])) {
                    // раскодировать и сохранить изображение
                    $nameFile = self::decodeAndSaveImg($item['mainview'], 'point_mainView_' . $key);
                    $points_new [$key]['mainview'] = $nameFile;
                }

                // дополнительное фото
                if (isset($item['identview']) and !is_null($item['identview'])) {
                    // раскодировать и сохранить изображение
                    $nameFile = self::decodeAndSaveImg($item['identview'], 'point_identview_' . $key);
                    $points_new [$key]['identview'] = $nameFile;
                }

                // файлы
                if (isset($item['files']) and count($item['files']) > 0) {

                    $files = [];
                    foreach ($item['files'] as $key2 => $item2) {
                        // раскодировать и сохранить изображение
                        $nameFile = self::decodeAndSaveImg($item2, 'point_' . $key . '-' . $key2);
                        $files [] =
                            [
                                'file' => $nameFile,
                            ];
                    }
                    $points_new [$key]['files'] = $files;
                }

                // ввод к потребителю
                if (isset($item['consumerInputDTOS']) and count($item['consumerInputDTOS']) > 0) {

                    // обновить комментарий
                    self::updateComment(
                        "<p>
                        <strong>- ввод к потребителю:</strong>"
                    );

                    foreach ($item['consumerInputDTOS'] as $key2 => $item2) {
                        $points_new [$key]['consumerInputDTOS'] [] =
                            [
                                'name' => isset($item2['name']) ? $item2['name'] : '',
                                'address' => isset($item2['address']) ? $item2['address'] : '',
                                'lat' => isset($item2['latitude']) ? $item2['latitude'] : '',
                                'long' => isset($item2['longitude']) ? $item2['longitude'] : '',
                                'lineType' => isset($item2['lineType']) ? $item2['lineType'] : '',
                                'wireBrand' => isset($item2['wireBrand']) ? $item2['wireBrand'] : '',
                                'wireCut' => isset($item2['wireCut']) ? $item2['wireCut'] : '',
                                'wireLength' => isset($item2['wireLength']) ? $item2['wireLength'] : '',
                                'wireNumber' => isset($item2['wireNumber']) ? $item2['wireNumber'] : '',
                                'plotLength' => isset($item2['plotLength']) ? $item2['plotLength'] : '',
                                'layingConditionsId' => isset($item2['layingConditionsId']) ? $item2['layingConditionsId'] : '',
                                'size' => isset($item2['size']) ? $item2['size'] : '',
                                'spanLength' => isset($item2['spanLength']) ? $item2['spanLength'] : '',
                            ];

                        // обновить комментарий
                        self::updateComment(
                            "<br><br>
                            - имя Потребителя: " . (isset($item2['name']) ? $item2['name'] : '') . "<br>
                            - адрес: " . (isset($item2['address']) ? $item2['address'] : '') . "<br>
                            - долгота: " . (isset($item2['latitude']) ? $item2['latitude'] : '') . "<br>
                            - широта: " . (isset($item2['longitude']) ? $item2['longitude'] : '') . "<br>
                            - тип линии: " . (isset($item2['lineType']) ? $item2['lineType'] : '') . "<br>
                            - марка провода: " . (isset($item2['wireBrand']) ? $item2['wireBrand'] : '') . "<br>
                            - сечение провода: " . (isset($item2['wireCut']) ? $item2['wireCut'] : '') . "<br>
                            - длина провода: " . (isset($item2['wireLength']) ? $item2['wireLength'] : '') . "<br>
                            - число проводов: " . (isset($item2['wireNumber']) ? $item2['wireNumber'] : '') . "<br>
                            - длина участка, для 701: " . (isset($item2['plotLength']) ? $item2['plotLength'] : '') . "<br>
                            - условие прокладки, для 701: " . (isset($item2['layingConditionsId']) ? $item2['layingConditionsId'] : '') . "<br>
                            - габарит, для 702: " . (isset($item2['size']) ? $item2['size'] : '') . "<br>
                            - длина пролета, для 702: " . (isset($item2['spanLength']) ? $item2['spanLength'] : '') . "<br>"
                        );

                        // файлы
                        if (isset($item2['files']) and count($item2['files']) > 0) {

                            $files = [];
                            foreach ($item2['files'] as $key3 => $item3) {
                                // раскодировать и сохранить изображение
                                $nameFile = self::decodeAndSaveImg($item3, 'point_vvod_k_potrebitelu_' . $key . '-' . $key2 . '-' . $key3);
                                $files [] =
                                    [
                                        'file' => $nameFile,
                                    ];
                            }
                            $points_new [$key]['consumerInputDTOS'][$key2]['files'] = $files;
                        }
                    }

                    // обновить комментарий
                    self::updateComment("</p>");
                }

                // опоры
                if (isset($item['tower'])) {

                    $points_new [$key]['tower'] =
                        [
                            'subClass' => isset($item["tower"]['subClass']) ? $item["tower"]['subClass'] : '',
                            'mark' => isset($item["tower"]['mark']) ? $item["tower"]['mark'] : '',
                            'markName' => isset($item["tower"]['markName']) ? $item["tower"]['markName'] : '',
                            'materialName' => isset($item["tower"]['materialName']) ? $item["tower"]['materialName'] : '',
                            'phaseNumber' => isset($item["tower"]['phaseNumber']) ? $item["tower"]['phaseNumber'] : '',
                            'foundationType' => isset($item["tower"]['foundationType']) ? $item["tower"]['foundationType'] : '',
                            'foundationNumber' => isset($item["tower"]['foundationNumber']) ? $item["tower"]['foundationNumber'] : '',
                            'annexMaterial' => isset($item["tower"]['annexMaterial']) ? $item["tower"]['annexMaterial'] : '',
                            'annexNumber' => isset($item["tower"]['annexNumber']) ? $item["tower"]['annexNumber'] : '',
                            'strutMaterial' => isset($item["tower"]['strutMaterial']) ? $item["tower"]['strutMaterial'] : '',
                            'strutNumber' => isset($item["tower"]['strutNumber']) ? $item["tower"]['strutNumber'] : '',
                            'preserveComment' => isset($item["tower"]['preserveComment']) ? $item["tower"]['preserveComment'] : '',
                            'guy' => isset($item["tower"]['guy']) ? $item["tower"]['guy'] : '',
                            'grounding' => isset($item["tower"]['grounding']) ? $item["tower"]['grounding'] : '',
                        ];

                    // обновить комментарий
                    self::updateComment(
                        "<br><br>
                        <strong>- опоры (шаг 3 из 6):</strong>
                        <br><br>
                        - назначение: " . (isset($item['tower']['subClass']) ? $item['tower']['subClass'] : '') . "<br>
                        - марка: " . (isset($item['tower']['mark']) ? $item['tower']['mark'] : '') . "<br>
                        - марка: " . (isset($item['tower']['markName']) ? $item['tower']['markName'] : '') . "<br>
                        - материал: " . (isset($item['tower']['materialName']) ? $item['tower']['materialName'] : '') . "<br>
                        - кол-во фаз: " . (isset($item['tower']['phaseNumber']) ? $item['tower']['phaseNumber'] : '') . "<br>
                        - тип фундамента: " . (isset($item['tower']['foundationType']) ? $item['tower']['foundationType'] : '') . "<br>
                        - кол-во фундаментов: " . (isset($item['tower']['foundationNumber']) ? $item['tower']['foundationNumber'] : '') . "<br>
                        - материал приставки: " . (isset($item['tower']['annexMaterial']) ? $item['tower']['annexMaterial'] : '') . "<br>
                        - кол-во приставок: " . (isset($item['tower']['annexNumber']) ? $item['tower']['annexNumber'] : '') . "<br>
                        - материал подкоса: " . (isset($item['tower']['strutMaterial']) ? $item['tower']['strutMaterial'] : '') . "<br>
                        - кол-во подкосов: " . (isset($item['tower']['strutNumber']) ? $item['tower']['strutNumber'] : '') . "<br>
                        - характер консервации древесины: " . (isset($item['tower']['preserveComment']) ? $item['tower']['preserveComment'] : '') . "<br>
                        - оттяжка: " . (isset($item['tower']['guy']) ? $item['tower']['guy'] : '') . "<br>
                        - заземление: " . (isset($item['tower']['grounding']) ? $item['tower']['grounding'] : '')
                    );

                    // подвесы опор
                    if (isset($item["tower"]["towerSuspensionDTO"])) {

                        $points_new [$key]["tower"]['towerSuspensionDTO'] =
                            [
                                'insulatorMounting' => isset($item["tower"]["towerSuspensionDTO"]["insulatorMounting"]) ? $item["tower"]["towerSuspensionDTO"]["insulatorMounting"] : '',
                                'insulatorNo' => isset($item["tower"]["towerSuspensionDTO"]["insulatorNo"]) ? $item["tower"]["towerSuspensionDTO"]["insulatorNo"] : '',
                                'insulatorMarkind' => isset($item["tower"]["towerSuspensionDTO"]["insulatorMarkind"]) ? $item["tower"]["towerSuspensionDTO"]["insulatorMarkind"] : '',
                                'protDevType' => isset($item["tower"]["towerSuspensionDTO"]["protDevType"]) ? $item["tower"]["towerSuspensionDTO"]["protDevType"] : '',
                                'protDevMark' => isset($item["tower"]["towerSuspensionDTO"]["protDevMark"]) ? $item["tower"]["towerSuspensionDTO"]["protDevMark"] : '',
                                'lampsNo' => isset($item["tower"]["towerSuspensionDTO"]["lampsNo"]) ? $item["tower"]["towerSuspensionDTO"]["lampsNo"] : '',
                                'preservativeKind' => isset($item["tower"]["towerSuspensionDTO"]["preservativeKind"]) ? $item["tower"]["towerSuspensionDTO"]["preservativeKind"] : '',
                                'comments' => isset($item["tower"]["towerSuspensionDTO"]["comments"]) ? $item["tower"]["towerSuspensionDTO"]["comments"] : '',
                            ];

                        // обновить комментарий
                        self::updateComment(
                            "<br><br>
                        <strong>- подвес опоры, шаг 4 из 6:</strong>
                        <br><br>
                        - способ крепления изоляторов: " . (isset($item['tower']['towerSuspensionDTO']['insulatorMounting']) ? $item['tower']['towerSuspensionDTO']['insulatorMounting'] : '') . "<br>
                        - кол-во изоляторов: " . (isset($item['tower']['towerSuspensionDTO']['insulatorNo']) ? $item['tower']['towerSuspensionDTO']['insulatorNo'] : '') . "<br>
                        - тип изолятора: " . (isset($item['tower']['towerSuspensionDTO']['insulatorMarkind']) ? $item['tower']['towerSuspensionDTO']['insulatorMarkind'] : '') . "<br>
                        - класс защиты от перенапрежения: " . (isset($item['tower']['towerSuspensionDTO']['protDevType']) ? $item['tower']['towerSuspensionDTO']['protDevType'] : '') . "<br>
                        - марка защиты от пренапряжения: " . (isset($item['tower']['towerSuspensionDTO']['protDevMark']) ? $item['tower']['towerSuspensionDTO']['protDevMark'] : '') . "<br>
                        - кол-во светильников: " . (isset($item['tower']['towerSuspensionDTO']['lampsNo']) ? $item['tower']['towerSuspensionDTO']['lampsNo'] : '') . "<br>
                        - защитное покрытие изолятора: " . (isset($item['tower']['towerSuspensionDTO']['preservativeKind']) ? $item['tower']['towerSuspensionDTO']['preservativeKind'] : '') . "<br>
                        - комментарий: " . (isset($item['tower']['towerSuspensionDTO']['comments']) ? $item['tower']['towerSuspensionDTO']['comments'] : '')
                        );
                    }

                    // файлы с комментарием (возможно еще file нужно будет использовать)
                    if (isset($item["tower"]["towerSuspensionDTO"]['photosWithComments']) and count($item["tower"]["towerSuspensionDTO"]['photosWithComments']) > 0) {

                        $files = [];
                        foreach ($item["tower"]["towerSuspensionDTO"]['photosWithComments'] as $key2 => $item2) {

                            if (isset($item2['second']) and $item2['second'] <> '') {
                                // раскодировать и сохранить изображение
                                $nameFile = self::decodeAndSaveImg($item2['second'], 'point_towerSuspension_' . $key . '-' . $key2);
                                $files [] =
                                    [
                                        'file' => $nameFile,
                                        'comment' => $item2['first'],
                                    ];

                                // обновить комментарий
                                self::updateComment(
                                    "<br>
                                    - комментарий к фото: " . $item2['first']
                                );
                            }
                        }
                        $points_new [$key]["tower"]["towerSuspensionDTO"]['files'] = $files;
                    }

                    // заземление
                    if (isset($item["tower"]["towerSuspensionDTO"]["grounding"]) and count($item["tower"]["towerSuspensionDTO"]["grounding"]) > 0) {

                        $grounding = [];

                        // обновить комментарий
                        self::updateComment(
                            "<p>
                            <strong>- заземление:</strong>"
                        );

                        foreach ($item["tower"]["towerSuspensionDTO"]["grounding"] as $key2 => $item2) {
                            $grounding [] =
                                [
                                    $key2 => $item2,
                                ];

                            // обновить комментарий
                            self::updateComment(
                                "<br>
                                " . $key2 . " - " . $item2 . "<br>"
                            );
                        }
                        $points_new [$key]["tower"]["towerSuspensionDTO"]['grounding'] = $grounding;

                        // обновить комментарий
                        self::updateComment("</p>");
                    }
                }
            }

            // обновить комментарий
            self::updateComment("</p>");
        }

        // возвращаемый параметр
        return $points_new;
    }

    // распарсить lines701
    public function parseLines701($object)
    {
        // начальное значение
        $lines701_new = [];

        // линии кабельные 701
        if (count($object) > 0) {

            // обновить комментарий
            self::updateComment(
                "<p>
                <strong>Линия воздушная (lines701), шаг 1 из 6:</strong>"
            );

            foreach ($object as $key => $item) {

                $lines701_new [] =
                    [
                        'type' => isset($item['type']) ? $item['type'] : '',
                        'isSubline' => isset($item['isSubline']) ? $item['isSubline'] : '',
                        'startPoint' => isset($item['startPoint']) ? $item['startPoint'] : '',
                        'endPoint' => isset($item['endPoint']) ? $item['endPoint'] : '',
                        'markId' => isset($item['markId']) ? $item['markId'] : '',
                        'markName' => isset($item['markName']) ? $item['markName'] : '',
                        'cutSize' => isset($item['cutSize']) ? $item['cutSize'] : '',
                        'size' => isset($item['size']) ? $item['size'] : '',
                        'spanLength' => isset($item['spanLength']) ? $item['spanLength'] : '',
                        'wireLength' => isset($item['wireLength']) ? $item['wireLength'] : '',
                        'wireNumber' => isset($item['wireNumber']) ? $item['wireNumber'] : '',
                    ];

                // обновить комментарий
                self::updateComment(
                    "<br><br>
                    - тип: " . (isset($item['type']) ? $item['type'] : '') . "<br>
                    - начальная точка: " . (isset($item['startPoint']) ? $item['startPoint'] : '') . "<br>
                    - конечная точка: " . (isset($item['endPoint']) ? $item['endPoint'] : '') . "<br>
                    - отпайка: " . (isset($item['isSubline']) ? $item['isSubline'] : '') . "<br>
                    - марка провода: " . (isset($item['markId']) ? $item['markId'] : '') . "<br>
                    - марка провода: " . (isset($item['markName']) ? $item['markName'] : '') . "<br>
                    - сечение: " . (isset($item['cutSize']) ? $item['cutSize'] : '') . "<br>
                    - габарит: " . (isset($item['size']) ? $item['size'] : '') . "<br>
                    - длина пролета: " . (isset($item['spanLength']) ? $item['spanLength'] : '') . "<br>
                    - длина провода: " . (isset($item['wireLength']) ? $item['wireLength'] : '') . "<br>
                    - число проводов: " . (isset($item['wireNumber']) ? $item['wireNumber'] : '') . "<br>"
                );

                // файлы
                if (isset($item['files']) and count($item['files']) > 0) {

                    $files = [];
                    foreach ($item['files'] as $key2 => $item2) {
                        // раскодировать и сохранить изображение
                        $nameFile = self::decodeAndSaveImg($item2, 'line701_' . $key . '-' . $key2);
                        $files [] =
                            [
                                'file' => $nameFile,
                            ];
                    }
                    $lines701_new [$key]['files'] = $files;
                }

                // кабельный переход
                if (isset($item['cables']) and count($item['cables']) > 0) {

                    // обновить комментарий
                    self::updateComment(
                        "<p>
                        <strong>- кабельный переход(-ы):</strong>"
                    );

                    foreach ($item['cables'] as $key2 => $item2) {

                        $lines701_new [$key]['cables'] [] =
                            [
                                'connectorType' => isset($item2['connectorType']) ? $item2['connectorType'] : '',
                                'mark' => isset($item2['mark']) ? $item2['mark'] : '',
                                'markName' => isset($item2['markName']) ? $item2['markName'] : '',
                                'lat' => isset($item2['latitude']) ? $item2['latitude'] : '',
                                'long' => isset($item2['longitude']) ? $item2['longitude'] : '',
                                'cutSize' => isset($item2['cutSize']) ? $item2['cutSize'] : '',
                                'length' => isset($item2['length']) ? $item2['length'] : '',
                                'roundAboutType' => isset($item2['roundAboutType']) ? $item2['roundAboutType'] : '',
                            ];

                        // обновить комментарий
                        self::updateComment(
                            "<br><br>
                            - тип соединения: " . (isset($item2['connectorType']) ? $item2['connectorType'] : '') . "<br>
                            - марка провода (mark): " . (isset($item2['mark']) ? $item2['mark'] : '') . "<br>
                            - марка провода (markName): " . (isset($item2['markName']) ? $item2['markName'] : '') . "<br>
                            - долгота (latitude): " . (isset($item2['latitude']) ? $item2['latitude'] : '') . "<br>
                            - широта (longitude): " . (isset($item2['longitude']) ? $item2['longitude'] : '') . "<br>
                            - сечение (cutSize): " . (isset($item2['cutSize']) ? $item2['cutSize'] : '') . "<br>
                            - длина (length): " . (isset($item2['length']) ? $item2['length'] : '') . "<br>
                            - roundAboutType: " . (isset($item2['roundAboutType']) ? $item2['roundAboutType'] : '') . "<br>"
                        );

                        // файлы
                        if (isset($item2['files']) and count($item2['files']) > 0) {

                            $files = [];
                            foreach ($item2['files'] as $key3 => $item3) {
                                // раскодировать и сохранить изображение
                                $nameFile = self::decodeAndSaveImg($item3, 'line701_cabel_perehod_' . $key . '-' . $key2 . '-' . $key3);
                                $files [] =
                                    [
                                        'file' => $nameFile,
                                    ];
                            }
                            $lines701_new [$key]['cables'][$key2]['files'] = $files;
                        }
                    }

                    // обновить комментарий
                    self::updateComment("</p>");
                }

                // переходы и пересечения
                if (isset($item['transAndInterpas']) and count($item['transAndInterpas']) > 0) {

                    // обновить комментарий
                    self::updateComment(
                        "<p>
                        <strong>- переход(-ы) и пересечение(-я) (transAndInterpas):</strong>"
                    );

                    foreach ($item['transAndInterpas'] as $key2 => $item2) {

                        $lines701_new [$key]['transAndInterpas'] [] =
                            [
                                'crossedObjectName' => isset($item2['crossedObjectName']) ? $item2['crossedObjectName'] : '',
                                'lat' => isset($item2["coords"][0]) ? $item2["coords"][0] : '',
                                'long' => isset($item2["coords"][1]) ? $item2["coords"][1] : '',
                            ];

                        // обновить комментарий
                        self::updateComment(
                            "<br><br>
                            - наименование перехода/пересечения: " . (isset($item2['crossedObjectName']) ? $item2['crossedObjectName'] : '') . "<br>
                            - долгота: " . (isset($item2['coords'][0]) ? $item2['coords'][0] : '') . "<br>
                            - широта: " . (isset($item2['coords'][1]) ? $item2['coords'][1] : '') . "<br>"
                        );

                        // файлы
                        if (isset($item2['files']) and count($item2['files']) > 0) {

                            $files = [];
                            foreach ($item2['files'] as $key3 => $item3) {
                                // раскодировать и сохранить изображение
                                $nameFile = self::decodeAndSaveImg($item3, 'line701_perehodi_i_peresecheniya_' . $key . '-' . $key2 . '-' . $key3);
                                $files [] =
                                    [
                                        'file' => $nameFile,
                                    ];
                            }
                            $lines701_new [$key]['transAndInterpas'][$key2]['files'] = $files;
                        }
                    }

                    // обновить комментарий
                    self::updateComment("</p>");
                }
            }

            // обновить комментарий
            self::updateComment("</p>");
        }

        // возвращаемый параметр
        return $lines701_new;
    }

    // распарсить lines702
    public function parseLines702($object)
    {
        // начальное значение
        $lines702_new = [];

        // линии воздушные 702
        if (count($object) > 0) {

            // обновить комментарий
            self::updateComment(
                "<p>
                <strong>Линия кабельная (lines702), шаг 1 из 6:</strong>"
            );

            foreach ($object as $key => $item) {

                $lines702_new [] =
                    [
                        'type' => isset($item['type']) ? $item['type'] : '',
                        'startPoint' => isset($item['startPoint']) ? $item['startPoint'] : '',
                        'endPoint' => isset($item['endPoint']) ? $item['endPoint'] : '',
                        'isSubline' => isset($item['issubline']) ? $item['issubline'] : '',
                        'markId' => isset($item['markId']) ? $item['markId'] : '',
                        'markName' => isset($item['markName']) ? $item['markName'] : '',
                        'cutSize' => isset($item['cutSize']) ? $item['cutSize'] : '',
                        'wireInPhase' => isset($item['wireInPhase']) ? $item['wireInPhase'] : '',
                        'wireLength' => isset($item['wireLength']) ? $item['wireLength'] : '',
                        'layingConditions' => isset($item['layingConditions']) ? $item['layingConditions'] : '',
                        'cabelsNumber' => isset($item['cabelsNumber']) ? $item['cabelsNumber'] : '',
                    ];

                // обновить комментарий
                self::updateComment(
                    "<br><br>
                    - тип: " . (isset($item['type']) ? $item['type'] : '') . "<br>
                    - начальная точка: " . (isset($item['startPoint']) ? $item['startPoint'] : '') . "<br>
                    - конечная точка: " . (isset($item['endPoint']) ? $item['endPoint'] : '') . "<br>
                    - отпайка: " . (isset($item['issubline']) ? $item['issubline'] : '') . "<br>
                    - марка провода: " . (isset($item['markId']) ? $item['markId'] : '') . "<br>
                    - марка провода: " . (isset($item['markName']) ? $item['markName'] : '') . "<br>
                    - сечение: " . (isset($item['cutSize']) ? $item['cutSize'] : '') . "<br>
                    - проводов в фазе: " . (isset($item['wireInPhase']) ? $item['wireInPhase'] : '') . "<br>
                    - длина участка: " . (isset($item['wireLength']) ? $item['wireLength'] : '') . "<br>
                    - траншея: " . (isset($item['layingConditions']) ? $item['layingConditions'] : '') . "<br>
                    - кабелей в траншее: " . (isset($item['cabelsNumber']) ? $item['cabelsNumber'] : '') . "<br>"
                );

                // файлы
                if (isset($item['files']) and count($item['files']) > 0) {

                    $files = [];
                    foreach ($item['files'] as $key2 => $item2) {
                        // раскодировать и сохранить изображение
                        $nameFile = self::decodeAndSaveImg($item2, 'line702_' . $key . '-' . $key2);
                        $files [] =
                            [
                                'file' => $nameFile,
                            ];
                    }
                    $lines702_new [$key]['files'] = $files;
                }

                // характерные точки
                if (isset($item['points']) and count($item['points']) > 0) {

                    // обновить комментарий
                    self::updateComment(
                        "<p>
                        <strong>- характерные точки</strong>"
                    );

                    foreach ($item['points'] as $key2 => $item2) {

                        $lines702_new [$key]['points'] [] =
                            [
                                'id' => isset($item2['id']) ? $item2['id'] : '',
                                'depth' => isset($item2['depth']) ? $item2['depth'] : '',
                                'lat' => isset($item2['latitude']) ? $item2['latitude'] : '',
                                'long' => isset($item2['longitude']) ? $item2['longitude'] : '',
                            ];

                        // обновить комментарий
                        self::updateComment(
                            "<br><br>
                            - характерная точка id: " . (isset($item2['id']) ? $item2['id'] : '') . "<br>
                            - глубина: " . (isset($item2['depth']) ? $item2['depth'] : '') . "<br>
                            - долгота: " . (isset($item2['latitude']) ? $item2['latitude'] : '') . "<br>
                            - широта: " . (isset($item2['longitude']) ? $item2['longitude'] : '') . "<br>"
                        );
                    }

                    // обновить комментарий
                    self::updateComment("</p>");
                }
            }

            // обновить комментарий
            self::updateComment(" </p>");
        }

        // возвращаемый параметр
        return $lines702_new;
    }

    // раскодировать и сохранить одно изображение
    public function decodeAndSaveImg($object, $fileName)
    {
        // проверка на пустоту
        if (is_null($object)) return '';

        // раскодировать
        $imgContent = base64_decode($object);
        // имя сохраняемого файла
        $dir = self::getdir() . $fileName;
        $fullFileNameHd = $dir . '_hd.jpg';
        $fullFileNameThumb = $dir . '_thumb.jpg';
        // сохранить на диске
        file_put_contents($fullFileNameHd, $imgContent);
        // попробовать создать миниатюру этого изображения (может возникнуть ошибка, если изображение поломанное или не тот формат)
        try {
            Image::make($fullFileNameHd)->resize(200, 150)->save($fullFileNameThumb);
        } catch (\Exception $e) {
            // ошибка
        }
        // добавление текста к общему комментария о фото
        self::addCommentImg($fileName);

        // возвращаемый параметр
        return $fileName;
    }

    // добавление текста к общему комментария о фото
    public function addCommentImg($nameFile)
    {
        $file = ' /public/' . self::getdir() . $nameFile;
        // обновить комментарий
        self::updateComment(
            "<br>
                <i><small>(" . $nameFile . ".jpg)</small></i> 
                <a href='" . $file . "_hd.jpg' target='_blank'>
                    <img src = '" . $file . "_thumb.jpg' class='m-5'>
                </a>"
        );
    }
}