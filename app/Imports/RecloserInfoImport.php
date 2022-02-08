<?php


namespace App\Imports;


use App\Helpers\UnitsMultipliersHelper;
use App\Models\Gost;
use App\Models\SwitchInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RecloserInfoImport implements ToCollection
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
                    'withstandCurrent' => ['value' => (empty($row[9])) ? null : $row[9]],
                    'makingCapacity' => ['value' => (empty($row[10])) ? null : $row[10]],
                    'enproWithstandCurrentDuration' => ['value' => (empty($row[11])) ? null : $row[11]],
                    'enproEarthSwitchCurrentDuration' => ['value' => (empty($row[12])) ? null : $row[12]],
                    'enpro_secondary_voltage_kind_id' => $this->getKindId('SecondaryCircuitsVoltageKind', $row[14]),
                    'enproSecondaryVoltage' => ['value' => (empty($row[15])) ? null : $row[15]],
                    'remote' => (mb_strtolower(trim($row[20])) === 'да'),
                    'RecloserInfo' => ['value' => 1]
                ],
                'ratedImpulseWithstandVoltage' => ['value' => (empty($row[13])) ? null : $row[13]],
                'enproInsulationLength' => ['value' => (empty($row[16])) ? null : $row[16]],
                'enpro_climatic_mod_placement_id' => $this->getKindId('GostClimaticModPlacementKind', $row[17]),
                'enproTemperatureRange' => [
                    'minTemperature' => ['value' => $this->exp(' +', $row[18], 0)],
                    'maxTemperature' => ['value' => $this->exp(' +', $row[18], 1)],
                ],
                'enpro_gost_id' => $this->getGostId($row[19]),
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
