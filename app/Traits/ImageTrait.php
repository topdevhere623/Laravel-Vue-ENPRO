<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

// работа с изображениями
// если изображение хранится в в таблице этой же модели
trait ImageTrait
{
    // возвращает размер для кропинга основного изображения
    public function imageSizes()
    {
        $sizes = [
            'thumb' => [400, null],
            'hd' => [1920, null]
        ];

        // возвращаемый параметр
        return $sizes;
    }

    // возвращает изображение вместе с папкой
    public function imagePath($fieldImg = 'img')
    {
        // вернуть полный путь, если картинка не пустая
        return $this->$fieldImg <> '' ? $this->folderPath() . '/' . $this->$fieldImg : '';
    }

    // возвращает изображение (или заглушку) вместе с папкой, плюс добавляет к имени размер миниатюры (например, thumb)
    public function getImage($type = null, $fieldImg = null)
    {
        // с добавлением имени папки (в бд только имя файла)
        $image = $this->imagePath($fieldImg);
        if (!is_null($image) and $image <> '') {
            // изображение есть

            // определить расширение
            if (pathinfo($image, PATHINFO_EXTENSION) !== 'svg') {
                // это не svg

                if (is_null($type)) {
                    // размер изображения не передали
                    $returnImage = $image;
                } else {
                    // размер изображения передали - добавить его к имени файла
                    $returnImage = str_replace('.', '_' . $type . '.', $image);
                }

            } else {
                // это svg
                $returnImage = $image;
            }
        } else {
            // изображения нет - поставить заглушка
            $returnImage = 'uploads/default/default_hd.png';
        }

        // возвращаемый параметр
        return $returnImage;
    }
}