<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView; // для вывода с использованием blade
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // автоширина столбцов
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // ширина столбцов вручную
use Maatwebsite\Excel\Concerns\WithTitle; // тайтл
use Maatwebsite\Excel\Concerns\WithStyles; // стилизация ячеек
use Illuminate\Contracts\View\View;

// модели

class AclineReport2 implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    // мой параметр, который принимаю с контроллера
    protected $id;

    // конструктор
    function __construct($getData)
    {
        $this->getData = $getData;
    }

    // экспорт с использованием шаблонизатора blade
    public function view(): View
    {
        // открыть вьюшку
        return view('backend.acline.export.report2',
            [
                'aclineData' => $this->getData['aclineData'],
                'aclineName' => $this->getData['aclineName'],
                'aclineVoltage' => $this->getData['aclineVoltage'],
                'aclineLenght' => $this->getData['aclineLenght'],
                'aclineTowers' => $this->getData['aclineTowers'],
            ]);
    }

    // вывод с названием листа
    public function title(): string
    {
        return $this->getData['aclineVoltage'];
    }

    // вывод со стилизацией ячеек
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            //1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            //'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
    }
}
