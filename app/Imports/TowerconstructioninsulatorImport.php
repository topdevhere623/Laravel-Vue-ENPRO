<?php

namespace App\Imports;

use App\Models\Towerconstructioninsulator;
use Maatwebsite\Excel\Concerns\ToModel;

// для импорта в Excel
class TowerconstructioninsulatorImport implements ToModel
{

    public function model(array $row)
    {
        //  импортируемые поля, которые должны быть в файле
        return new Towerconstructioninsulator([
            'series' => (string)$row[0],
            'album' => (string)$row[1],
            'name' => (string)$row[2],
            'mark' => (string)$row[3],
            'weight' => (float)$row[4],
        ]);
    }
}
