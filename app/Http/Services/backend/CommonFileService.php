<?php

namespace App\Http\Services\backend;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\File as File;

// мои модели
use App\AdminModels\AdminSetting;

// общий сервис работы с файлами (изображениями)
class CommonFileService
{
    // метод загрузки на хостинг изображения
    public function serviceUploadedImage($img, $model): ?string
    {
        // вернуть исходное изображение, если не загружали нового
        if (!is_uploaded_file($img)) {
            return $img;
        }

        if (!is_null($img)) {
            // изображение передали
            // расширение файла
            $extension = $img->extension();
            // сгенерировать уникальное имя
            $unique = uniqid();

            // папка хранения модели с ее id
            $folderPath = public_path() . '/' . $model->folderPath();
            //  создать директорию для ID модели, если ее еще не было
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0775, true, true);
            }

            if ($img->getClientOriginalExtension() !== 'svg') {
                // если это не svg-файл - сделать миниатюры (_thumb и _hd)

                // размер для кропинга основного изображения
                $cropSizes = $model->imageSizes();
                if (!is_null($cropSizes)) {
                    // массив размеров существует

                    foreach ($cropSizes as $key => $size) {
                        $cropImage = Image::make($img);
                        if ($size[0] == null || $size[1] == null) {
                            $cropImage->resize($size[0], $size[1], function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        } else {
                            $cropImage->fit($size[0], $size[1]);
                        }
                        $cropImage->save($folderPath . '/' . $unique . '_' . $key . '.' . $extension, 85);
                    }
                }
            }

            // перенести оригинал как есть
            $img->move($folderPath, $unique . '.' . $extension);

            // возвращаемый параметр - уникальное имя с расширением без пути
            return $unique . '.' . $extension;
        } else {
            return null;
        }
    }

    // метод загрузки на хостинг изображения
    public function serviceUploadedFile($file, $model): ?string
    {
        // расширение файла
        $extension = $file->getClientOriginalExtension();
        // сгенерировать уникальное имя
        $unique = uniqid();
        //dd($model->folderPath());
        $file->move(public_path($model->folderPath()), $unique . '.' . $extension); // возвращаемый параметр - уникальное имя с расширением без пути
        return $unique . '.' . $extension;
    }

    // удаление старого изображения с диска
    public function serviceDeleteOldImage($model, $fieldImg = 'img')
    {
        if ($model->$fieldImg <> '') {
            // поле было не пустое
            // имена суффикксов взять из массива
            $cropSizes = $model->imageSizes();
            if (!is_null($cropSizes)) {
                // массив размеров существует
                foreach ($cropSizes as $key => $size) {
                    // только имя изображения
                    $imageName = str_replace('.', '_' . $key . '.', $model->$fieldImg);
                    // полный путь изображения
                    $imagePath = $model->folderPath() . '/' . $imageName;
                    Storage::disk('public')->delete($imagePath);
                }
            } else {
                // массив размеров не существует - только оригинал изображения
                // полный путь изображения
                $imagePath = $model->folderPath() . '/' . $model->$fieldImg;
                Storage::disk('public')->delete($imagePath);
            }
        }
    }

    // удаление старого изображения с диска
    public function serviceDeleteOldFile($model, $fieldField)
    {
        Storage::disk('public')->delete($model->$fieldField);
        return true;
    }

    // удаление папки модели с ее содержимым
    public function serviceDeleteAllImages($model): bool
    {
        // папка модели
        $path = $model->folderPath();
        // список файлов в папке
        $files = Storage::disk('public')->allFiles($path);
        Storage::disk('public')->delete($files);
        // удаление пустой папки
        Storage::disk('public')->deleteDirectory($path);

        // возвращаемый параметр
        return true;
    }

    // получение списка файлов (оригиналов и миниатюр)
    // $dir - папка для чтения без 'public', например 'uploads/models/task/1'
    // $needExt - расширения, которые надо получить, например 'jpg'. Если не указано, то все получить
    // $getNameMaska - маска в имени файла, например '_thumb'
    public function getImgFromDir($getDir, $getNeedExt = null, $getNameMaska = null)
    {
        // начальное значение
        $myFindFiles = [];

        // полный путь
        $dir = public_path() . '/' . $getDir;
        // проверить читается ли эта диретория
        if (!is_readable($dir)) return false;

        // прочитать содержимое директории
        $d = opendir($dir);
        // сканировать диреторию
        while ($name = readdir($d)) {
            if ($name == ' . ' || $name == ' ..') continue;
            // полный путь до файла
            $myFullPath = $dir . DIRECTORY_SEPARATOR . $name;
            if (is_file($myFullPath)) {
                // да, это файл

                // проверка, указаны ли только нужные рсширения
                if (!is_null($getNeedExt) and pathinfo($myFullPath, PATHINFO_EXTENSION) <> $getNeedExt) continue;
                // проверка, указаны маска в имени файла
                if (!is_null($getNameMaska) and !Str::contains($name, $getNameMaska)) continue;

                // запомнить файл
                $myFindFiles[] = $name;
            }
        }
        // закрыть дескриптор диреториии
        closedir($d);

        // возвращаемый параметр
        return $myFindFiles;
    }

    // рекурсивная функция поиска файлов (пока нигде не используется и сейчас возвращает список директорий)
    function servieRecursionDir($dir)
    {
        // прочитать содержимое дирекории
        $d = opendir($dir);
        while ($fileName = readdir($d)) {
            // убрать из списка . и ..
            if ($fileName == '.' || $fileName == '..') continue;
            // полный путь до файла
            $myFullPath = $dir . DIRECTORY_SEPARATOR . $fileName;

            // записать в массив
            $files[] = ['thisFile' => 'yes', 'file' => $fileName, 'fullPath' => $myFullPath];

            if (is_dir($myFullPath)) {
                // это директория

                // рекурсивно прочитать содержимое этой директории
                self::servieRecursionDir($myFullPath);
            }
        }

        // закрыть дескриптор директории
        closedir($d);
        return $files;
    }

    // получить Mime-тип файла
    function getMimeType($filename)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $filename);
        finfo_close($finfo);

        // возвращаемый параметр
        return $mime;
    }
}
