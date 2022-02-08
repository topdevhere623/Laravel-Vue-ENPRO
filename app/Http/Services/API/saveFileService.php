<?php

namespace App\Http\Services\API;

use Illuminate\Support\Facades\File;

// мои сервисы

// сервис сохранения принимаемого по API файла
class saveFileService
{
    // сохранить полученный файл
    public function saveFile($dirName = null, $fileName = null, $fileHash = null, $fileData = null)
    {
        // проверка на получение данных
        if (false) {
            if (is_null($dirName)) return ['message' => "Ошибка! Не получено имя директории!", 'code' => 400];
            if (is_null($fileName)) return ['message' => "Ошибка! Не получено имя файла!", 'code' => 400];
            if (is_null($fileHash)) return ['message' => "Ошибка! Не получен хеш файла!", 'code' => 400];
            if (is_null($fileData)) return ['message' => "Ошибка! Не получен массив данных файла!", 'code' => 400];
        }

        // проверка закаченного на сервер временного файла
        if (isset($fileData['tmp_name']) and !is_null($fileData['tmp_name'])) {
            // содержимое временного файла
            $fileContent = $fileData['tmp_name'];
        } else {
            return ['message' => "Ошибка! Файл на сервер не закачен!", 'code' => 400];
        }

        // проверка на допустимое расширение принимаемого на сервер файла
        if (false) {
            // расширение файла
            $fileExtension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
            // белый список допустимых расширения принимаемых на сервер файлов
            $whiteExtensionList = self::getWhiteExtensionList();
            if (!in_array($fileExtension, $whiteExtensionList)) return ['message' => "Ошибка! Недопустимое расширение! Необходимо одно из: " . implode(", ", $whiteExtensionList), 'code' => 400];
        }

        // проверка хеша
        if (false) {
            $inServerHash = md5_file($fileData['tmp_name']);
            if ($fileHash <> $inServerHash) {
                // ошибка
                return ['message' => "Ошибка! Хеш полученного файла не совпадает с присланным! Его хеш на сервере: " . $inServerHash, 'code' => 400];
            }
        }

        // папка для сохранения
        $dirSave = public_path() . $dirName . '/';
        // создать папку, если ее еще не было
        if (!File::exists($dirSave)) {
            File::makeDirectory($dirSave, 0775, true, true);
        }

        // сохранение файла (перемещение из папки временной загрузки в нужное место)
        move_uploaded_file($fileData['tmp_name'], $dirSave . $fileName);

        // возвращаемый параметр
        return
            [
                'message' => "Готово! Полученный файл успешно сохранен на сервере!",
                'code' => 200
            ];
    }

    // белый список допустимых расширения принимаемых на сервер файлов
    public function getWhiteExtensionList()
    {
        $whiteExtensionList =
            [
                'json',
                'rar',
                'zip',
                'tar',
                'tar.gz',
                'gz'
            ];
        return $whiteExtensionList;
    }

    // проверка, файл json или нет
    function isJSON($string)
    {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }
}

