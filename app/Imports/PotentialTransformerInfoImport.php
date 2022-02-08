<?php


namespace App\Imports;


use App\Helpers\UnitsMultipliersHelper;
use App\Models\PotentialTransformerInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PotentialTransformerInfoImport implements ToCollection
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
                'ratedFrequency' => ['value' => (empty($row[3])) ? null : $row[3]],
                'enproSecondary1Voltage' => ['value' => (empty($row[4])) ? null : $row[4]],
                'enproSecondary2Voltage' => ['value' => (empty($row[5])) ? null : $row[5]],
                'accuracyClass' => (empty($row[6])) ? null : $row[6],
                'enpro_construction_kind_id' => $this->getKindId('PotentialTransformerKind', $row[7]),
                'enpro_climatic_mod_placement_id' => $this->getKindId('GostClimaticModPlacementKind', $row[8]),
                'massa' => (empty($row[9])) ? null : $row[9],
            ];

            $input = ['PotentialTransformerInfo' => $ot];
            UnitsMultipliersHelper::mergeArray($input, ['PotentialTransformerInfo']);
            $items[] = $input['PotentialTransformerInfo'];
        }

        /** @var PotentialTransformerInfo $model */
        foreach ($items as $item) {
            PotentialTransformerInfo::create($item);
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
}
