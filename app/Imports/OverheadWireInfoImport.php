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
class OverheadWireInfoImport implements ToCollection
{

    public function collection(Collection $rows)
    {

        $this->deleteRows();

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
                'strandCount' => $row[9],
                'material_id' => $this->getKindId('WireMaterialKind', $row[6]),
                'insulation_material_id' => $this->getKindId('WireInsulationKind', $row[7]),
                'radius' => ['value' => $row[12]],
                'coreRadius' => ['value' => $row[11]],
                'insulationThickness' => ['value' => $row[8]],
                'ratedCurrent' => ['value' => $row[13]],
                'rDC20' => ['value' => empty($row[14]) ? null : round($row[14], 3)],
                'enproBreakForce' => ['value' => $row[15]],
                'enproWeightPerLength' => ['value' => $row[16]],
                'insulated' => (mb_strtolower(trim($row[4])) === 'да'),
                'sizeDescription' => $row[5],
                'coreStrandCount' => $row[10],
                'nominalVoltage' => ['value' => (empty($row[2])) ? null : $row[2]],
                'standardServiceLife' => ['value'=> ['years' => null]],
                'gost_id' => $this->getGostId($row[17]),
                'OverheadWireInfo' => ['value' => 1],
            ]
        ];
    }

    private function getEnumKindId($modelName, $literal)
    {
        if ($literal == 'С') $literal = 'C';
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

    private function deleteRows()
    {
        $modelName = 'OverheadWireInfo';
        $ids = WireAssemblyInfo::whereHas('WirePhaseInfo', function($query) use ($modelName){
                $query->whereHas('WireInfo', function($query) use ($modelName){
                    $query->whereHas($modelName);
                });
            })
            ->pluck('id')
            ->toArray();
        WireAssemblyInfo::whereIn('id', $ids)->forceDelete();
        //WireInfo::query()->forceDelete();
        //WireMaterialKind::query()->forceDelete();
        //WireInsulationKind::query()->forceDelete();
    }

}
