<?php

namespace App\Imports;

use App\Helpers\UnitsMultipliersHelper;
use App\Models\Gost;
use App\Models\SwitchInfo;
use App\Models\WireMaterialKind;
use App\Models\WireInsulationKind;
use App\Models\Towerconstructionaccessory;
use App\Models\WireAssemblyInfo;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

// для импорта в Excel
class LoadBreakSwitchInfoImport implements ToCollection
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
                'enproMaxVoltage' => ['value' => (empty($row[5])) ? null : $row[4]],
                'ratedFrequency' => ['value' => (empty($row[6])) ? null : $row[5]],
                'ratedCurrent' => ['value' => (empty($row[6])) ? null : $row[6]],
                'ratedImpulseWithstandVoltage' => ['value' => (empty($row[13])) ? null : $row[13]],
                'enproRatedPressure' => ['value' => (empty($row[16])) ? null : $row[16]],
                'enproInsulationLength' => ['value' => (empty($row[17])) ? null : $row[17]],
                'enpro_secondary_voltage_kind_id' => $this->getKindId('GostClimaticModPlacementKind', $row[18]),
                'enproTemperatureRange' => [
                    'minTemperature' => ['value' => (empty($row[19])) ? null : explode(' +', $row[19])[0]],
                    'maxTemperature' => ['value' => (empty($row[19])) ? null : explode(' +', $row[19])[1]],
                ],
                'enpro_gost_id' => $this->getGostId($row[20]),
                'OldSwitchInfo' => [
                    'loadBreak' => true,
                    'poleCount' => (empty($row[8])) ? null : $row[8],
                    'withstandCurrent' => ['value' => (empty($row[9])) ? null : $row[9]],
                    'makingCapacity' => ['value' => (empty($row[10])) ? null : $row[10]],
                    'enproWithstandCurrentDuration' => ['value' => (empty($row[11])) ? null : $row[11]],
                    'enproEarthSwitchCurrentDuration' => ['value' => (empty($row[12])) ? null : $row[12]],
                    'enpro_secondary_voltage_kind_id' => $this->getKindId('SecondaryCircuitsVoltageKind', $row[14]),
                    'enproSecondaryVoltage' => ['value' => (empty($row[15])) ? null : $row[15]],
                    'remote' => (mb_strtolower(trim($row[21])) === 'да') ? true : false,
                    'LoadBreakSwitchInfo' => ['value' => 1]
                ]
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
        $model = Gost::where('name', '=', $value)->first();
        if (! empty($model)) {
            return $model->id;
        } else {
            $newModel = Gost::create(['name' => $value]);
            return $newModel->id;
        }
    }
}
