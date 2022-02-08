<?php


namespace App\Imports;


use App\Helpers\UnitsMultipliersHelper;
use App\Models\Gost;
use App\Models\SwitchInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FuseInfoImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $items = [];
        foreach ($rows as $row)
        {
            $switchInfo = [
                'AssetInfo' => [
                    'CatalogAssetType' => [
                        'IdentifiedObject' => [
                            'name' => $row[0],
                            'names' => [
                                ['name' => $row[1]]
                            ]
                        ]
                    ]
                ],
                'enpro_breaker_kind_id' =>  $this->getKindId('BreakerConstructionKind', $row[2]),
                'ratedVoltage' => ['value' => (empty($row[3])) ? null : $row[3]],
                'enproMaxVoltage' => ['value' => (empty($row[4])) ? null : $row[4]],
                'ratedFrequency' => ['value' => (empty($row[5])) ? null : $row[5]],
                'ratedCurrent' => ['value' => (empty($row[6])) ? null : $row[6]],
                'breakingCapacity' => ['value' => (empty($row[7])) ? null : $row[7]],
                'OldSwitchInfo' => [
                    'poleCount' => (empty($row[8])) ? null : $row[8],
                    'FuseInfo' => ['value' => 1]
                ],
                'enproInsulationLength' => ['value' => (empty($row[9])) ? null : $row[9]],
                'enpro_climatic_mod_placement_id' => $this->getKindId('GostClimaticModPlacementKind', $row[10]),
                'enproTemperatureRange' => [
                    'minTemperature' => ['value' => $this->exp(' +', $row[11], 0)],
                    'maxTemperature' => ['value' => $this->exp(' +', $row[11], 1)],
                ],
                'enpro_gost_id' => $this->getGostId($row[12]),
                'isSinglePhase' => (mb_strtolower(trim($row[13])) === 'да'),
            ];

            $input = ['SwitchInfo' => $switchInfo];
            UnitsMultipliersHelper::mergeArray($input, ['SwitchInfo', 'OldSwitchInfo']);
            $items[] = $input['SwitchInfo'];
        }

        /** @var SwitchInfo $model */
        foreach ($items as $item) {
            SwitchInfo::create($item);
        }
    }

    private function exp($param, $value, $key)
    {
        $s = explode($param, $value);
        if(isset($s[$key]))
            return $s[$key];
        return null;
    }

    private function getKindId($modelName, $value)
    {
        $modelFullName = "App\\Models\\" . $modelName;
        $model = $modelFullName::where('ru_value', '=', $value)->first();
        if (! empty($model)) {
            return $model->id;
        } else {
            $newModel = $modelFullName::create(['ru_value' => $value]);
            return $newModel->id;
        }
    }

    private function getGostId($value)
    {
        if($value == null)
            return null;
        $model = Gost::where('name', '=', $value)->first();
        if (! empty($model)) {
            return $model->id;
        } else {
            $newModel = Gost::create(['name' => $value]);
            return $newModel->id;
        }
    }
}
