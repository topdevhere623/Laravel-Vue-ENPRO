<?php

namespace App\Imports;

use App\Helpers\UnitsMultipliersHelper;
use App\Models\Gost;
use App\Models\OldTransformerTankInfo;
use App\Models\TransformerEndInfo;
use App\Models\TransformerTankInfo;
use App\Models\WireInfo;
use App\Models\WireMaterialKind;
use App\Models\WireInsulationKind;
use App\Models\Towerconstructionaccessory;
use App\Models\WireAssemblyInfo;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

// для импорта в Excel
class OldTransformerTankInfoImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $items = [];
        $transformerTankInfoIds = [];
        $nn = 0;
        foreach ($rows as $row)
        {
            if (! array_key_exists(trim($row[0]), $items)) {
                //Добавляем обмотки в существующий трансформатор
                $ot = OldTransformerTankInfo:: create([
                    'TransformerTankInfo' => [
                        'AssetInfo' => [
                            'CatalogAssetType' => [
                                'IdentifiedObject' => [
                                    'name' => $row[1],
                                    'names' => [
                                        [
                                            'name' => $row[2]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'construction_kind_id' => $this->getKindId('TransformerConstructionKind', $row[3]),
                    'coreCoilsWeight' => ['value' => empty($row[4]) ? null : (float)$row[4]],

                    'enproOilWeight' => ['value' => empty($row[5]) ? null : (float)$row[5]],
                    'enproFullWeight' => ['value' => empty($row[6]) ? null : (float)$row[6]],

                    'core_kind_id' => $this->getKindId('TransformerCoreKind', $row[7]),
                    'function_id' => $this->getKindId('TransformerFunctionKind', $row[8]),
                    'cooling_kind_id' => $this->getKindId('TransformerCoolingKind', $row[9]),

                    'enproTemperatureRange' => [
                        'minTemperature' => ['value' => (empty($row[10])) ? null : explode(' +', $row[10])[0]],
                        'maxTemperature' => ['value' => (empty($row[10])) ? null : explode(' +', $row[10])[1]],
                    ],
                    'gost_id' => $this->getGostId($row[11])
                ]);
                $ttiId = $ot->TransformerTankInfo->id;
                $transformerTankInfoIds[$nn] = $ttiId;
                $items[trim($row[0])] = $nn;
            } else {
                $transformerTankInfoIds[$nn] = $ttiId;
            }
            $nn++;
        }

        $nn = 0;
        $transformerEndInfoIds = [];
        foreach ($rows as $row)
        {
            if ((! empty(trim($row[13]))) && (empty($transformerEndInfoIds[$transformerTankInfoIds[$nn]]) || ((! empty($transformerEndInfoIds[$transformerTankInfoIds[$nn]]) && (! in_array(trim($row[13]), $transformerEndInfoIds[$transformerTankInfoIds[$nn]])))))) {
                $transformerEndInfoIds[$transformerTankInfoIds[$nn]][] = trim($row[13]);
                TransformerEndInfo::create($this->getTransformerEndInfo($row, $transformerTankInfoIds[$nn]));
            }
            $nn++;
        }

        $nn = 0;
        foreach ($rows as $row)
        {
            if (! empty($row[21])) {
                $sct = $this->getShortCircuitTest($row, $transformerTankInfoIds[$nn]);
                $tei = TransformerEndInfo::find($sct['energised_end_id']);
                $tei->update(['ShortCircuitTests' => [$sct]]);
                $tei->save();
            }
            $nn++;
        }

        $nn = 0;
        foreach ($rows as $row)
        {
            if (! empty(trim($row[26]))) {
                $nlt = $this->getNoLoadTest($row, $transformerTankInfoIds[$nn]);
                $tei = TransformerEndInfo::find($nlt['energised_end_id']);
                $tei->update(['NoLoadTests' => [$nlt]]);
                $tei->save();
            }
            $nn++;
        }
    }

    private function getShortCircuitTest($row, $ttiId)
    {
        $teiId = TransformerEndInfo::whereHas('TransformerTankInfo', function($q) use ($ttiId) {
           $q->where('id', '=', $ttiId);
        })->whereHas('AssetInfo', function($q) use ($row) {
            $q->whereHas('IdentifiedObject', function($q) use ($row) {
                $q->whereHas('names', function($q) use ($row) {
                    $q->where('name', '=', trim($row[21]));
                });
            });
        })->first()->id;
        $teiId2 = TransformerEndInfo::whereHas('TransformerTankInfo', function($q) use ($ttiId) {
            $q->where('id', '=', $ttiId);
        })->whereHas('AssetInfo', function($q) use ($row) {
            $q->whereHas('IdentifiedObject', function($q) use ($row) {
                $q->whereHas('names', function($q) use ($row) {
                    $q->where('name', '=', trim($row[22]));
                });
            });
        })->first()->id;

        return [
            'energised_end_id' => $teiId,
            'GroundedEnds' => [
                [
                    'id' => $teiId2
                ]
            ],
            'loss' => ['value' => empty($row[23]) ? null : (float)$row[23]],
            'voltage' => ['value' => empty($row[24]) ? null : (float)$row[24]],
            'TransformerTest' => [
                'temperature' => [
                    'value' => empty($row[25]) ? null : (float)$row[25]
                ]
            ]
        ];
    }

    private function getNoLoadTest($row, $ttiId)
    {
        $teiId = TransformerEndInfo::whereHas('TransformerTankInfo', function($q) use ($ttiId) {
            $q->where('id', '=', $ttiId);
        })->whereHas('AssetInfo', function($q) use ($row) {
            $q->whereHas('IdentifiedObject', function($q) use ($row) {
                $q->whereHas('names', function($q) use ($row) {
                    $q->where('name', '=', trim($row[26]));
                });
            });
        })->first()->id;

        return [
            'energised_end_id' => $teiId,
            'loss' => ['value' => empty($row[27]) ? null : (float)$row[27]],
            'excitingCurrent' => ['value' => empty($row[28]) ? null : (float)$row[28]],
            'TransformerTest' => [
                'value' => null
            ]
        ];
    }


    private function getTransformerEndInfo($row, $ttiId)
    {
        return [
            'transformer_tank_info_id' => $ttiId,
            'AssetInfo' => [
                'IdentifiedObject' => [
                    'name' => $row[12],
                    'names' => [
                        [
                            'name' => $row[13]
                        ],
                    ]
                ]
            ],
            'endNumber' => $row[14],
            'ratedS' => ['value' => empty($row[15]) ? null : (float)$row[15]],
            'ratedU' => ['value' => empty($row[16]) ? null : (float)$row[16]],
            'connection_kind_id' => $this->getEnumKindId('WindingConnection', trim($row[17])),
            'phaseAngleClock' => (int)$row[18],
            'r' => ['value' => empty($row[19]) ? null : (float)$row[19]],
            'OldTransformerEndInfo' => [
                'winding_insulation_kind_id' => $this->getKindId('WindingInsulationKind', $row[20]),
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
        return $model->id;
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
