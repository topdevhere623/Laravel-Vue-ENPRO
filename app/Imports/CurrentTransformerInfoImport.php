<?php


namespace App\Imports;


use App\Helpers\UnitsMultipliersHelper;
use App\Models\Currenttransformerinfo;
use App\Models\Ratio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CurrentTransformerInfoImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $items = [];
        foreach ($rows as $row) {
            $ot = [
                'AssetInfo' => [
                    'CatalogAssetType' => [
                        'IdentifiedObject' => [
                            'name' => $row[0],
                            'names' => [
                                [
                                    'name' => $row[1]
                                ]
                            ]
                        ]
                    ]
                ],
                'ratedVoltage' => ['value' => (empty($row[2])) ? null : $row[2]],
                'enproMaxVoltage' => ['value' => (empty($row[3])) ? null : $row[3]],
                'ratedFrequency' => ['value' => (empty($row[4])) ? null : $row[4]],
                //'coreCount' => ['value' => (empty($row[5])) ? null : $row[5]], //-------
                'coreCount' => (empty($row[5])) ? null : $row[5],
                'nominal_ratio_id' => $this->getRation($row[6]),
                'ratedCurrent' => ['value' => (empty($row[7])) ? null : $row[7]],
                'enpro_climatic_mod_placement_id' => $this->getKindId('GostClimaticModPlacementKind', $row[8]),

                //'accuracyClass' => ['value' => (empty($row[9])) ? null : $row[9]], //-------
                'accuracyClass' => (empty($row[9])) ? null : $row[9],
            ];
            $input = ['CurrentTransformerInfo' => $ot];
            UnitsMultipliersHelper::mergeArray($input, ['CurrentTransformerInfo']);
            $items[] = $input['CurrentTransformerInfo'];
        }

        /** @var Currenttransformerinfo $model */
        foreach ($items as $item) {
            Currenttransformerinfo::create($item);
        }

    }

    private function getKindId($modelName, $value)
    {
        $modelFullName = "App\\Models\\" . $modelName;
        $model = $modelFullName::where('ru_value', '=', $value)->first();
        if (!empty($model)) {
            return $model->id;
        } else {
            $newModel = $modelFullName::create(['ru_value' => $value]);
            return $newModel->id;
        }
    }

    private function getRation($value)
    {
        if($value == null) return null;
        $s = explode('/', $value);

        $model = Ratio::where(['denominator' => $s[0], "numerator" => $s[1]])->first();
        if (! empty($model)) {
            return $model->id;
        } else {
            $newModel = Ratio::create(['denominator' => $s[0], "numerator" => $s[1]]);
            return $newModel->id;
        }
    }

}
