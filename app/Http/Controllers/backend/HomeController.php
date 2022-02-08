<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Acline;
use App\Models\Aclinesegment;
use App\Models\Customer;
use App\Models\Identifiedobject;
use App\Models\Span;
use App\Models\Substation;
use App\Models\Terminal;
use App\Models\Tower;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\ModelService;
use App\Http\Services\backend\FirebirdService;
use App\Http\Services\backend\YandexMapService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// контроллер главной страницы Админки
class HomeController extends Controller
{
    // подключение сервисов
    public function __construct(ModelService $modelService, FirebirdService $firebirdService, YandexMapService $yandexMapService)
    {
        $this->modelService = $modelService;
        $this->firebirdService = $firebirdService;
        $this->yandexMapService = $yandexMapService;
    }

    // вывод главной страницы
    public function index(Request $request)
    {
        // список всех моделей
        $models = $this->modelService->models();
        // список таблиц, которые используют модели и их поля
        $tablesMySQLwithFields = $this->modelService->tablesWithFields($models, 0, 1);

        // список точек для карты
        $return = $this->yandexMapService->getMapContent('identifiedobject', false);
        $mapContent = $return['mapContent'];

        // открыть вьшку
        return view('backend.home.index', compact('tablesMySQLwithFields', 'mapContent'));
    }

    public function getSubstationIconPath(Substation $substation)
    {
        /** @var Substation $content */
        $content = $substation;
        /** @var Identifiedobject $identifiedobjects */
        $identifiedobjects = $content->identifiedobject()->get()->get(0);
        $iconFolder = '/assets/backend/icons/mainmap/';
        $addFolder = 'blue/';
        if (trim($content->passport) == 'М') $addFolder = 'turquoise/';
        if (trim($content->passport) == 'Л') $addFolder = 'green/';
        if (trim($content->passport) == 'С') $addFolder = 'purple/';
        $svgFilename = $addFolder.'tp.svg';
        if (mb_substr(trim($identifiedobjects->name), 0, 3, 'UTF-8') == ' КТ' || mb_substr(trim($identifiedobjects->name), 0, 3, 'UTF-8') == 'КТП') $svgFilename = $addFolder.'ktpn.svg';
        if (mb_substr(trim($identifiedobjects->name), 0, 2, 'UTF-8') == 'РП') $svgFilename = $addFolder.'rp.svg';
        if (mb_substr(trim($identifiedobjects->name), 0, 2, 'UTF-8') == 'ПС') {
            $svgFilename = 'ps-r.svg';
            if (trim($content->passport) == 'М') $svgFilename = 'ps-m.svg';
            if (trim($content->passport) == 'Л') $svgFilename = 'ps-l.svg';
            if (trim($content->passport) == 'С') $svgFilename = 'ps-s.svg';
        }
       return $iconFolder . $svgFilename;
    }

    public function mainMap(Request $request)
    {

        $substationsToMap = [];
        $substationsToMap = $this->getCached('substation', 'substations', 'all');
        if(!$substationsToMap) {
            $substationsToMap = [];
            $substations  = Substation::with(
                [
                    'identifiedobject',
                ]
            )->paginate(1000)->items();
            foreach ($substations as $substation) {
                $color = 'blue';
                if (trim($substation->passport) == 'М') $color = 'turquoise';
                if (trim($substation->passport) == 'Л') $color = 'green';
                if (trim($substation->passport) == 'С') $color = 'purple';
                /** @var Substation $substation */
                $substationsToMap[] = [
                    $substation->id,
                    $substation->identifiedobject()->get()->get(0)->name,
                    $substation->identifiedobject()->get()->get(0)->lat,
                    $substation->identifiedobject()->get()->get(0)->long,
                    $color,
                    $this->getSubstationIconPath($substation)
                ];
            }
            $this->setCached('substation', 'substations', 'all', $substationsToMap);
        }
        $linesToMap = $this->getCached('acline', 'linesToMap', 'all');
        if(!$linesToMap) {
            $linesToMap = [];
            $linesToMap[400] = [];
            $linesToMap[6000] = [];
        }
        $linesToMapNames = $this->getCached('acline', 'linesToMapNames', 'all');
        $linesForSubstations = [];
        if(!$linesToMapNames) {
            $linesToMapNames = [];
            $linesToMapNames[400] = [];
            $linesToMapNames[6000] = [];
        }
        $linesToMapWithSegments = $this->getCached('acline', 'linesToMapWithSegments', 'all');
        //$linesToMapWithSegments = null;
        if(!$linesToMapWithSegments) {
            $linesToMapWithSegments = [400 => [],6000 => []];
            /** @var Builder $lines */
            $lines = Acline::with([
                'identifiedobject',
                'aclinesegments' => function ($query) {
                    $query->with('identifiedobject');
                }
            ])->paginate(10000);
            foreach ($lines as $line) {
                $voltageLevel = 0;
                /** @var Acline $line*/
                /** @var Acline $acLineSegment */
                /** @var Identifiedobject $io */
                $io = $line->identifiedobject()->get()->get(0);
                if(count($line->aclinesegments()->get()) && ($io->basevoltage()->first()->id == 6 || $io->basevoltage()->first()->id == 10 || $io->basevoltage()->first()->id == 35)) {
                    $linesToMap[6000][$line->id] = [];
                    $voltageLevel = 6000;
                }
                else if($line->aclinesegments()->get()) {
                    $linesToMap[400][$line->id] = [];
                    $voltageLevel = 400;
                }

                $lineToMap = $this->getCached('acline', 'lineToMap', $line->id);
                $lineToMapWithSegments = $this->getCached('acline', 'lineToMapWithSegments', $line->id);
                //$lineToMapWithSegments = null;
                if($lineToMapWithSegments) {
                    $linesToMapWithSegments[$voltageLevel][$line->id] = $lineToMapWithSegments;
                    $linesToMap[$voltageLevel][$line->id] = $lineToMap;
                    $linesToMapNames[$voltageLevel][$line->id] = $io->name;
                    continue;
                } else {
                    $linesToMapWithSegments[$voltageLevel][$line->id] = [];
                    $linesToMap[$voltageLevel][$line->id] = [];
                }
                foreach($line->aclinesegments()->get() as $acLineSegment) {
                    /** @var Aclinesegment $acLineSegment */
                    if(!array_key_exists($acLineSegment->id, $linesToMapWithSegments[$voltageLevel][$line->id])) {
                        $linesToMapWithSegments[$voltageLevel][$line->id][$acLineSegment->id] = [];
                    }

                    foreach ($acLineSegment->spans()->get() as $span) {
                        try {
                            $add = [];
                            /** @var Span $span */
                            $startTowerType = -1;
                            $endTowerType = -1;
                            if($span->spantype == 702) {
                                $add = json_decode($span->points, 1);
                                /** @var Tower $towerStart */
                                $towerStart = Tower::where('identifiedobject_id', $span->startIO()->get()->get(0)->id)->get()->get(0);
                                if(!$towerStart) {
                                    $towerStart = Customer::where('identifiedobject_id', $span->startIO()->get()->get(0)->id)->get()->get(0);
                                }
                                /** @var Tower $towerEnd */
                                $towerEnd = Tower::where('identifiedobject_id', $span->endIO()->get()->get(0)->id)->get()->get(0);
                                if(!$towerEnd) {
                                    $towerEnd = Customer::where('identifiedobject_id', $span->endIO()->get()->get(0)->id)->get()->get(0);
                                }
                                if($add) {
                                    $pointsLength = count($add);
                                } else {
                                    $add = [[$towerStart->identifiedobject()->get()->get(0)->lat, $towerStart->identifiedobject()->get()->get(0)->long],
                                        [$towerEnd->identifiedobject()->get()->get(0)->lat, $towerEnd->identifiedobject()->get()->get(0)->long]];
                                    $pointsLength = 2;
                                }
                                $firstPoint = $add[0];
                                $lastPoint = $add[$pointsLength - 1];
                                //if($towerStart->identifiedobject()->get()->get(0)->lat == $firstPoint[0] && $towerStart->identifiedobject()->get()->get(0)->long == $firstPoint[1]) {

                                $add[0] = ($towerStart instanceof Tower) ? $towerStart->getCoordinates() : [
                                    $span->startIO()->get()->get(0)->lat,
                                    $span->startIO()->get()->get(0)->long
                                ];
                                $add[$pointsLength - 1] = ($towerEnd instanceof  Tower) ? $towerEnd->getCoordinates() : [
                                    $span->endIO()->get()->get(0)->lat,
                                    $span->endIO()->get()->get(0)->long
                                ];
                                //} else {
                                //  $add[$pointsLength - 1] = $towerStart->getCoordinates();
                                // $add[0] = $towerEnd->getCoordinates();
                                // }
                            }
                            else {
                                /** @var Tower $towerStart */
                                if($span->startIO_id) $towerStart = Tower::where('identifiedobject_id', $span->startIO()->get()->get(0)->id)->get()->get(0);
                                /** @var Tower $towerEnd */
                                if($span->endIO_id) $towerEnd = Tower::where('identifiedobject_id', $span->endIO()->get()->get(0)->id)->get()->get(0);

                                if($towerStart instanceof Tower && $towerStart->towerkind()->get()->get(0)) {
                                    $startTowerType = $towerStart->towerkind()->get()->get(0)->id;
                                    if($startTowerType == 1) $startTowerType = -1;
                                    else $startTowerType = 3;
                                    if($towerStart->fict_tp) $startTowerType = 3;
                                    if($towerStart->strutn) $startTowerType = 3;
                                    if($io->basevoltage()->first()->id == 380 || $io->basevoltage()->first()->id == 400) {
                                        $startTowerType = 3;
                                    }
                                }
                                if($towerEnd instanceof Tower && $towerEnd->towerkind()->get()->get(0)) {
                                    $endTowerType =$towerEnd->towerkind()->get()->get(0)->id;
                                    if($endTowerType == 1) $endTowerType = -1;
                                    else $endTowerType = 3;
                                    if($towerEnd->fict_tp) $endTowerType = 3;
                                    if($towerEnd->strutn) $endTowerType = 3;
                                    if($io->basevoltage()->first()->id == 380 || $io->basevoltage()->first()->id == 400) {
                                        $endTowerType = 3;
                                    }
                                }
                            }
                            $name1 = $span->endIO()->get()->get(0)->localname;
                            $name2 = $span->startIO()->get()->get(0)->localname;
                            $startIsCustomer = 0;
                            $endIsCustomer = 0;
                            if((!$towerStart || !($towerStart instanceof Tower)) && $span->startIO_id) {
                                $startCoords = [$span->startIO()->get()->get(0)->lat, $span->startIO()->get()->get(0)->long];
                                if(Customer::where('identifiedobject_id', $span->startIO_id)->count()) {
                                    $startIsCustomer = 1;
                                }
                            }
                            if((!$towerEnd || !($towerEnd instanceof Tower))  && $span->endIO_id) {
                                $endCoords = [$span->endIO()->get()->get(0)->lat, $span->endIO()->get()->get(0)->long];
                                if(Customer::where('identifiedobject_id', $span->endIO_id)->count()) {
                                    $endIsCustomer = 1;
                                }
                            }
                            $terminalTower = null;
                            if($towerStart instanceof Tower && $towerStart->connectivitycode_id) {
                                $terminalTower = $towerStart;
                            } else if($towerEnd instanceof Tower && $towerEnd->connectivitycode_id) {
                                $terminalTower = $towerEnd;
                            }
                            if($terminalTower) {
                                $terminals = Terminal::where('connectivitycode_id',$terminalTower->connectivitycode_id)->get();
                                /** @var Terminal $terminal */
                                foreach ($terminals as $terminal) {
                                    $terminalIo = $terminal->identifiedobject()->get()->get(0);
                                    if($terminalIo && $terminalIo->enobj_id) {
                                        $enobj = Substation::where('identifiedobject_id', $terminalIo->enobj_id)->get()->get(0);
                                        if($enobj) {
                                            if(!@$linesForSubstations[$enobj->id]) {
                                                $linesForSubstations[$enobj->id] = [$line->id];
                                            } else {
                                                if(!in_array($line->id, $linesForSubstations[$enobj->id])) {
                                                    $linesForSubstations[$enobj->id][] = $line->id;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if($span->startIO_id && $span->endIO_id) {
                                $spanData = [
                                    $span->spantype,
                                    ($towerStart instanceof Tower) ? $towerStart->getCoordinates()[1] : ($startCoords ? $startCoords[1] : 0),
                                    ($towerStart instanceof Tower) ? $towerStart->getCoordinates()[0] : ($startCoords ? $startCoords[0] : 0),
                                    ($towerEnd instanceof Tower) ? $towerEnd->getCoordinates()[1] :  ($endCoords ? $endCoords[1] : 0),
                                    ($towerEnd instanceof Tower) ? $towerEnd->getCoordinates()[0] : ($endCoords ? $endCoords[0] : 0),
                                    $add,
                                    [$span->startIO()->get()->get(0)->id, $startTowerType, $startIsCustomer],
                                    [$span->endIO()->get()->get(0)->id, $endTowerType, $endIsCustomer]
                                ];
                                $linesToMapWithSegments[$voltageLevel][$line->id][$acLineSegment->id][] = $spanData;
                                $linesToMap[$voltageLevel][$line->id][] = $spanData;
                            }
                        } catch (Exception $e) {
                            continue;
                        }
                    }
                    if($voltageLevel != 400) $linesToMapWithSegments[$voltageLevel][$line->id][$acLineSegment->id] = $this->sortAclineSegment($linesToMapWithSegments[$voltageLevel][$line->id][$acLineSegment->id]);
                }
                $this->setCached('acline', 'lineToMapWithSegments', $line->id, $linesToMapWithSegments[$voltageLevel][$line->id]);
                $this->setCached('acline', 'lineToMap', $line->id, $linesToMap[$voltageLevel][$line->id]);
                $linesToMapNames[$voltageLevel][$line->id] = $io->name;
            }
            $this->setCached('acline', 'linesToMap', 'all', $linesToMap);
            $this->setCached('acline', 'linesToMapNames', 'all', $linesToMapNames);
            $this->setCached('acline', 'linesToMapWithSegments', 'all', $linesToMapWithSegments);
        }

         return view('backend.home.mainmap')->with(
            [
                //'content' => $content,
                'lineNames' => base64_encode(json_encode($linesToMapNames)),
                'lineWithSegments' => base64_encode(json_encode($linesToMapWithSegments)),
                'lines' => base64_encode(json_encode($linesToMap)),
                'substations' => base64_encode(json_encode($substationsToMap)),
                'linesForSubstations' => base64_encode(json_encode($linesForSubstations))
                //'identifiedobject' => $identifiedobjects
            ]);
    }


    protected function sortAclineSegment($spansArray = [])
    {
        $sortedArray = [];
        $recursiv = 1000;
        $firstSpan = array_shift($spansArray);
        $sortedArray[] = $firstSpan;
        $startTower = @$firstSpan[6][0];
        $endTower =  @$firstSpan[7][0];
        $beginner = 0;
        $ender = 0;
        foreach ($spansArray as $span) {
            if(@$span[6][0] == $startTower || @$span[7][0] == $startTower) {
                $beginner = $startTower;
                $ender = $endTower;
                break;
            }
            if(@$span[6][0] == $endTower || @$span[7][0] == $endTower) {
                $beginner = $endTower;
                $ender = $startTower;
                break;
            }
        }
        if(!$beginner) return $sortedArray;
        while(count($spansArray) > 0 && $recursiv > 0) {
            $recursiv--;
            $i = 0;
            foreach ($spansArray as $span) {
                if(@$span[6][0] == $beginner) {
                    $beginner = @$span[7][0];
                    $sortedArray[] = $span;
                    unset($spansArray[$i]);

                } else if(@$span[7][0] == $beginner) {
                    $beginner =@$span[6][0];
                    $sortedArray[] = $span;
                    unset($spansArray[$i]);
                } else if(@$span[6][0] == $ender) {
                    $ender = @$span[7][0];
                    array_unshift($sortedArray, $span);
                    unset($spansArray[$i]);
                } else if(@$span[7][0] == $ender) {
                    $ender = @$span[6][0];
                    array_unshift($sortedArray, $span);
                    unset($spansArray[$i]);
                }
                $i++;
            }

        }
        return $sortedArray;

    }

    protected function getCached($table = '', $name = '', $id = 'all')
    {
        if($table) {
            if(!intval($id)) $lastTableData = DB::table($table)->orderBy('updated_at', 'desc')->limit(1)->first();
            else $lastTableData = DB::table($table)->find($id);
            if(!$lastTableData) return null;
            $lastTableDate = new \DateTime($lastTableData->updated_at);
            $lastCachedDate = Cache::get(($name ? $name : $table) . '_' . $id . '_time');
            if($lastTableDate->getTimestamp() > $lastCachedDate) return null;
            $data = Cache::get(($name ? $name : $table) . '_' . $id);
            return $data;
        }
    }

    protected function setCached ($table = '', $name = '', $id = 'all', $data = null)
    {
        if($table) {
            Cache::put(($name ? $name : $table) . '_' . $id . '_time', now()->getTimestamp(), now()->addDays(7));
            Cache::put(($name ? $name : $table) . '_' . $id, $data, now()->addDays(7));
        }
    }

    public function allMap()
    {
        return view('backend.home.allmap')->with(['allmap' => true]);
    }

    // API инструкция
    public function apiInstruction()
    {
        // открыть вьшку
        return view('backend.pages.api_instruction');
    }

    // API отладка запросов
    public function apiQueries()
    {
        // открыть вьшку
        return view('backend.pages.api_queries');
    }

    // phpinfo
    public function phpinfo()
    {
        // открыть вьшку
        return view('backend.pages.phpinfo');
    }
}

