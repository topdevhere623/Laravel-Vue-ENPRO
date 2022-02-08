<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets; // несколько листов

// модели

class AclineReport3Sheet implements WithMultipleSheets
{
    // мой параметр, который принимаю с контроллера
    protected $id;

    // конструктор
    function __construct($getData)
    {
        $this->getData = $getData;
    }

    // вывод по листам
    public function sheets(): array
    {
        $sheets = [];

        // два листа 6,10 и 0,4 кВ
        foreach ($this->getData as $value) {
            $sheets[] = new AclineReport2($value);
        }

        // возвращаемый параметр - массив листов
        return $sheets;
    }
}
