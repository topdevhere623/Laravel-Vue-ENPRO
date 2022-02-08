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
class BreakerInfoImport implements ToCollection
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
                'enpro_interrupter_position_id' =>  $this->getKindId('InterrupterPositionKind', $row[3]),
                'ratedVoltage' => ['value' => (empty($row[4])) ? null : $row[4]],
                'enproMaxVoltage' => ['value' => (empty($row[5])) ? null : $row[5]],
                'ratedFrequency' => ['value' => (empty($row[6])) ? null : $row[6]],
                'ratedCurrent' => ['value' => (empty($row[7])) ? null : $row[7]],
                'breakingCapacity' => ['value' => (empty($row[8])) ? null : $row[8]],
                'ratedInterruptingTime' => ['value' => (empty($row[9])) ? null : $row[9]],
                'ratedImpulseWithstandVoltage' => ['value' => (empty($row[14])) ? null : $row[14]],
                'enproRatedPressure' => ['value' => (empty($row[17])) ? null : $row[17]],
                'lowPressureAlarm' => ['value' => (empty($row[18])) ? null : $row[18]],
                'lowPressureLockOut' => ['value' => (empty($row[19])) ? null : $row[19]],
                'enproBreakForce' => ['value' => (empty($row[20])) ? null : $row[20]],
                'enproInsulationLength' => ['value' => (empty($row[21])) ? null : $row[21]],
                'enpro_secondary_voltage_kind_id' => $this->getKindId('GostClimaticModPlacementKind', $row[22]),
                'enproTemperatureRange' => [
                    'minTemperature' => ['value' => (empty($row[23])) ? null : explode(' +', $row[23])[0]],
                    'maxTemperature' => ['value' => (empty($row[23])) ? null : explode(' +', $row[23])[1]],
                ],
                'enpro_gost_id' => $this->getGostId($row[24]),
                'isSinglePhase' => (mb_strtolower(trim($row[25])) === 'да'),
                'isUnganged' => (mb_strtolower(trim($row[26])) === 'да'),
                'gasWeightPerTank' => ['value' => (empty($row[26])) ? null : $row[28]],
                'oilVolumePerTank' => ['value' => (empty($row[27])) ? null : $row[29]],
                'OldSwitchInfo' => [
                    'poleCount' => (empty($row[10])) ? null : $row[10],
                    'withstandCurrent' => ['value' => (empty($row[11])) ? null : $row[11]],
                    'makingCapacity' => ['value' => (empty($row[12])) ? null : $row[12]],
                    'enproWithstandCurrentDuration' => ['value' => (empty($row[13])) ? null : $row[13]],
                    'enpro_secondary_voltage_kind_id' => $this->getKindId('SecondaryCircuitsVoltageKind', $row[15]),
                    'enproSecondaryVoltage' => ['value' => (empty($row[16])) ? null : $row[16]],
                    'remote' => (mb_strtolower(trim($row[27])) === 'да'),
                    'BreakerInfo' => ['value' => 1]
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
