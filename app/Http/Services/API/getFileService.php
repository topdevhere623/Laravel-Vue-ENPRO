<?php

namespace App\Http\Services\API;

use Illuminate\Support\Facades\File;

// мои сервисы
use App\Http\Services\backend\CommonFileService;

// сервис отправки по API файла (например, схемы фидера)
class getFileService
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService)
    {
        $this->commonFileService = $commonFileService;
    }

    // прочитать и подготовить указанный файл для отправки (упакованное содержимое, mime, хеш)
    public function getFile($fileFullPath = null)
    {
        // проверка на получение данных
        if (is_null($fileFullPath)) return ['message' => "Ошибка! Не получен путь к файлу!", 'code' => 400];

        $fileFullPath = public_path() . '/' . $fileFullPath;

        // получить Mime-тип файла
        $fileMime = $this->commonFileService->getMimeType($fileFullPath);

        // загрузить файл
        $fileContent = file_get_contents($fileFullPath);

        // зашифровать
        if (false) {
            $fileContent = base64_encode($fileContent);
        }

        // Gzip-запоковать (создать сжатую строку gzip)
        if (false) {
            $fileContent = gzencode($fileContent);
        }

        // хеш файла (до кодирования и сжатия)
        $fileHash = hash_file('md5', $fileFullPath);

        // возвращаемый параметр
        return
            [
                'fileContent' => $fileContent,
                'fileMime' => $fileMime,
                'fileHash' => $fileHash,
            ];
    }
}