<?php

namespace App\Imports;

use App\Helpers\UnitsMultipliersHelper;
use App\Models\Gost;
use App\Models\WireInfo;
use App\Models\WireMaterialKind;
use App\Models\WireInsulationKind;
use App\Models\Towerconstructionaccessory;
use App\Models\WireAssemblyInfo;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

// для импорта в Excel
class CableInfoImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $items = [];
        foreach ($rows as $row)
        {
            $wirePhaseInfo = $this->getWirePhaseInfo($row);
            UnitsMultipliersHelper::mergeArray($wirePhaseInfo, ['WireInfo']);
            if (array_key_exists($row[0], $items)) {
                //Добавляем данные по новой фазе
                $items[$row[0]]['WirePhaseInfo'][] = $wirePhaseInfo;
            } else {
                //Добавляем в массив новую запись
                $items[$row[0]] = [
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
                  'WirePhaseInfo' => [$wirePhaseInfo]
                ];
            }
        }

        /** @var WireAssemblyInfo $model */
        foreach ($items as $item) {
            WireAssemblyInfo::create($item);
        }
    }

    private function getWirePhaseInfo($row)
    {
        return [
            'phase_info_id' => $this->getEnumKindId('SinglePhaseKind', $row[3]),
            'WireInfo' => [
                'insulated' => (mb_strtolower(trim($row[4])) === 'да'),
                'material_id' => $this->getKindId('WireMaterialKind', $row[6]),
                'sizeDescription' => $row[5],
                'strandCount' => $row[7],
                'radius' => ['value' => $row[8]],
                'insulation_material_id' => $this->getKindId('WireInsulationKind', $row[10]),
                'insulationThickness' => ['value' => $row[11]],
                'ratedCurrent' => ['value' => $row[17]],
                'rDC20' => ['value' => empty($row[18]) ? null : round($row[18], 3)],
                'enproBreakForce' => ['value' => $row[19]],
                'enproWeightPerLength' => ['value' => $row[20]],
                'nominalVoltage' => ['value' => (empty($row[2])) ? null : $row[2]],
                'standardServiceLife' => ['value'=> ['years' => $row[15]]],
                'gost_id' => $this->getGostId($row[21]),
                'CableInfo' => [
                    'construction_kind_id' => $this->getKindId('CableConstructionKind', $row[9]),
                    'fire_safety_id' => $this->getKindId('EnproFireSafetyKind', $row[14]),
                    'shield_material_id' => $this->getKindId('CableShieldMaterialKind', $row[13]),
                    'outer_jacket_kind_id' => $this->getKindId('CableOuterJacketKind', $row[12]),
                    'diameterOverJacket' => ['value' => $row[16]],
                ],
            ]
        ];
    }

    private function getEnumKindId($modelName, $literal)
    {
        if ($literal == 'С') $literal = 'C';
        if ($literal == 'А') $literal = 'A';
        if ($literal == 'В') $literal = 'B';
        $modelFullName = "App\\Models\\" . $modelName;
        $model = $modelFullName::where('literal', '=', $literal)->first();
        if (empty($model)) {
            return null;
        }
        return $modelFullName::where('literal', '=', $literal)->first()->id;
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
