<?php


namespace App\Http\Controllers\backend;


use App\Contracts\CIM\Wires\AuxiliaryEquipmentInterface;
use App\Contracts\CIM\Wires\ClampInterface;
use App\Contracts\CIM\Wires\ConductingEquipmentInterface;
use App\Contracts\CIM\Wires\ConnectivityNodeContainerInterface;
use App\Contracts\CIM\Wires\EquipmentInterface;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Contracts\CIM\Wires\LineInterface;
use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Contracts\CIM\Wires\SurgeArresterInterface;
use App\Http\Controllers\backend\Acline\AclineController;
use App\Http\Controllers\backend\Acline\AclineReportController;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;
use App\Models\Acline;
use App\Models\Aclinesegment;
use App\Models\AuxiliaryEquipment;
use App\Models\BaseVoltage;
use App\Models\Clamp;
use App\Models\ConductingEquipment;
use App\Models\ConnectivityNode;
use App\Models\Discharger;
use App\Models\Disconnector;
use App\Models\Equipment;
use App\Models\Identifiedobject;
use App\Models\Length;
use App\Models\Line;
use App\Models\PSRType;
use App\Models\Span;
use App\Models\SurgeArrester;
use App\Models\Terminal;
use App\Models\UnitMultiplier;
use App\Models\UnitSymbol;
use App\Models\Voltage;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;

class LineController extends \App\Http\Controllers\Controller
{
        protected $cimNS = 'http://iec.ch/TC57/2010/CIM-schema-cim15';
        protected $rdfNS = 'http://www.w3.org/1999/02/22-rdf-syntax-ns';
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    public function cim($profile_id = null, $acline_id = null)
    {
        /** Save line into CIM DB  */
        /** @var Acline $acline */
        $acline = Acline::find($acline_id);
        /** @var Identifiedobject $acLineIo */
        $acLineIo = $acline->identifiedobject()->get()->get(0);
        $acLineSegments = Aclinesegment::where('acline_id', $acline->id)->get();
        $equipmentContainer = null;
        /** @var Line $line */
        $line = null;
        /** @var Aclinesegment $acLineSegment */
        foreach ($acLineSegments as $acLineSegment) {
            if($acLineSegment->getEquipmentContainer()) {
                $equipmentContainer = $acLineSegment->getEquipmentContainer();
            }
        }
        if($equipmentContainer) {
            $line = Line::where('equipment_containers_id', $equipmentContainer->id)->get()->get(0);
        } else {
            if($acLineIo->getIdentification()) {
                $class  = explode(':', $acLineIo->getIdentification())[0];
                $id = explode(':', $acLineIo->getIdentification())[1];
                if($class == Line::class) {
                    $line = Line::find($id);
                }
            }
            if(!$line) {
                $line = new Line();
                $line->setIdentifiedObject($acLineIo);
                $line->save();
            }
        }
        if($line instanceof Line) {
            $equipments = $line->getEquipments();
            /** @var Equipment $equipment */
            foreach ($equipments as $equipment) {
                $equipment->removeEquipmentContainer();
            }
            foreach ($acLineSegments as $acLineSegment) {
                //$acLineSegment_io =
                if($acLineSegment->identifiedobject()->get()->get(0) && $acLineSegment->getIdentifiedObject()->id) {
                    $io = $acLineSegment->identifiedobject()->get()->get(0);
                    $acLineSegment->setIdentifiedObject();
                }
                $acLineSegment->selfIdentification();
                $acLineSegment->setEquipmentContainer($line->getEquipmentContainer());
                $baseVoltage = $acLineSegment->getBaseVoltage();
                if(!$baseVoltage && $line->getIdentifiedObject()->voltage_id) {
                    /** @var BaseVoltage $baseVoltage */
                    $baseVoltage = BaseVoltage::find($line->getIdentifiedObject()->voltage_id);
                    if(!$baseVoltage->getNominalVoltage()) {
                        $nominalVoltage = Voltage::where([
                            ['value','=', $baseVoltage->nominalvoltage],
                            ['multiplier_id','=', 14]
                        ])->get()->get(0);
                        if(!$nominalVoltage) {
                            $nominalVoltage = new Voltage();
                            $nominalVoltage->setValue($baseVoltage->nominalvoltage);
                            $nominalVoltage->setMultiplier(UnitMultiplier::find(14));
                            $nominalVoltage->setUnit(UnitSymbol::find(20));
                        }
                        $baseVoltage->setNominalVoltage($nominalVoltage);
                    }
                    $acLineSegment->setBaseVoltage($baseVoltage);
                }
                $psrType = $acLineSegment->getPSRType();
                if(!$psrType) {
                    $psrType = $this->getPSRType('AC Line Segment');
                    $acLineSegment->setPSRType($psrType);
                }

                $acLineSegment->save();
            }
            $thisLineData = AclineController::getThisLineDatas($acline->id);
            $disconnectors = $thisLineData['disconnectors'];
            $surgeArresters = $thisLineData['dischargers'];
            $segmentsAnalyze = AclineReportController::report2_content($thisLineData, true);
            $connectivity = [];
            /** @var Aclinesegment $item */
            foreach ($segmentsAnalyze as $item) {
                $length = 0;
                $surgeArrestersSegment = [];
                /** @var Discharger $surgeArrester */
                foreach ($surgeArresters as $surgeArrester) {
                    $span = Span::where('startIO_id', $surgeArrester->startIO_id)->first();
                    if(!$span) $span = Span::where('endIO_id', $surgeArrester->startIO_id)->first();
                    /** @var Aclinesegment $spanAclineSegment */
                    $spanAclineSegment = $span->aclinesegment()->get()->get(0);
                    if($spanAclineSegment && $spanAclineSegment->id == $item['segmentID']) {
                        $firstTowerId = $item['segmentStartPoint']['id'];
                        $beforeTowerId = 0;
                        $poleIds = [];
                        while($firstTowerId) {
                            $span = Span::where('startIO_id', '=', $firstTowerId)->
                            where('aclinesegment_id', '=', $spanAclineSegment->id)->
                            where('endIO_id', '!=', $beforeTowerId)->first();
                            if(!$span) {
                                $span = Span::where('endIO_id', '=', $firstTowerId)->
                                where('aclinesegment_id', '=', $spanAclineSegment->id)->
                                where('startIO_id', '!=', $beforeTowerId)->first();
                                if(!$span) {
                                    break;
                                } else {
                                    $poleIds[] = $firstTowerId;
                                    $beforeTowerId = $firstTowerId = $firstTowerId;
                                    $firstTowerId = $span->startIO_id;
                                }
                            } else {
                                $poleIds[] = $firstTowerId;
                                $beforeTowerId = $firstTowerId = $firstTowerId;
                                $firstTowerId = $span->endIO_id;
                            }
                        }
                        $mrid = $surgeArrester->identifiedobject()->first()->mrid;
                        /** @var Clamp $clamp */
                        if(!$mrid) {
                            $clamp = new Clamp();
                            $newSurgeArrester = new SurgeArrester();
                            $surgeArresterIo = $surgeArrester->identifiedobject()->first();
                            $surgeArresterIo->mrid = $newSurgeArrester->getMRID();
                            $newSurgeArrester->setName($surgeArresterIo->name);
                            $length = 0;
                            $poleIds = explode(',', $item['spansPointsIds']);
                            if($poleIds) {
                                foreach ($poleIds as $poleId) {
                                    $spanForLength = Span::where('startIO_id', $poleId)->first();
                                    if($spanForLength) {
                                        if($poleId == $surgeArrester->startIO_id) break;
                                        else if($surgeArrester->startIO_id == $spanForLength->endIO_id) {
                                            $length += $spanForLength->spanlength;
                                            break;
                                        } else $length += $spanForLength->spanlength;
                                    }
                                }
                            }
                            $lengthObject = new Length();
                            $lengthObject->setValue($length);
                            $lengthObject->setUnit(UnitSymbol::where('value', 2)->get()->get(0));
                            $lengthObject->setMultiplier(UnitMultiplier::where('value', 0)->get()->get(0));
                            $clamp->setLength($lengthObject);
                            $newSurgeArrester->setEquipmentContainer($line->getEquipmentContainer());
                            $newSurgeArrester->setInService(true);
                            $psrType = $this->getPSRType('Surge Arrester');
                            $newSurgeArrester->setPSRType($psrType);
                            $acLineSegment->addClamp($clamp);
                            $connectivityNode = new ConnectivityNode();
                            $terminalOfClamp = new Terminal();
                            $terminalOfClamp->setSequenceNumber(1);
                            $terminalOfClamp->setConnectivityNode($connectivityNode);
                            $newSurgeArrester->getTerminal()->setConnectivityNode($connectivityNode);
                            $connectivityNode->save();
                            $terminalOfClamp->save();
                            $clamp->addTerminal($terminalOfClamp);
                            $clamp->save();
                            $psrType->save();
                            $newSurgeArrester->save();
                            $spanAclineSegment->addClamp($clamp);
                            $spanAclineSegment->save();
                            $surgeArresterIo->save();
                        } else {
                            /** @var SurgeArresterInterface $surgeArresterSaved */
                            $surgeArresterSaved = $this->getObjectByMRID($mrid);
                            if($surgeArresterSaved) {
                                $length = 0;
                                //$poleIds = explode(',', $item['spansPointsIds']);
                                if($poleIds) {
                                    foreach ($poleIds as $i => $poleId) {
                                        if($i + 1 < count($poleIds)) {
                                            $spanForLength = Span::where('startIO_id', $poleId)->
                                            where('endIO_id', $poleIds[$i + 1])->first();
                                            if(!$spanForLength) {
                                                $spanForLength = Span::where('endIO_id', $poleId)->
                                                where('startIO_id', $poleIds[$i + 1])->first();
                                            }
                                        } else {
                                            $spanForLength = Span::where('endIO_id', $poleId)->
                                            where('aclinesegment_id', '=', $spanAclineSegment->id)->first();
                                            if(!$spanForLength) {
                                                $spanForLength = Span::where('startIO_id', $poleId)->
                                                where('aclinesegment_id', '=', $spanAclineSegment->id)->first();
                                            }
                                        }
                                        if($poleId) {
                                            if($poleId == $surgeArrester->startIO_id) break;
                                            else $length += $spanForLength->spanlength;
                                        }
                                    }
                                }
                                $terminal = $surgeArresterSaved->getTerminal();
                                $cn = $terminal->getConnectivityNode();
                                if($cn) {
                                    //$cmTerminals = $cn->getTerminals();
                                    /** @var  Terminal $cnTerminal */
                                    foreach ($cn->getTerminals() as $cnTerminal) {
                                        if($cnTerminal->getConductingEquipment()) {
                                            $ceMrid = $cnTerminal->getConductingEquipment()->getMRID();
                                            $clamp = $this->getObjectByMRID($ceMrid);
                                            if($clamp instanceof ClampInterface) {
                                                $clampLength = $clamp->getlengthFromTerminal1();
                                                $clampLength->setValue($length);
                                                $clampLength->save();
                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
                if($item) {
                    $addDisconnectors = [
                        'startLine' => [],
                        'endLine' => []
                    ];
                    /** @var Disconnector $disconnector */
                    foreach($disconnectors as $disconnector) {
                        if($disconnector->span_id) {
                            /** @var Span $span */
                            $span = Span::find($disconnector->span_id);
                            $spanAclineSegment = $span->aclinesegment()->get()->get(0);
                            if($spanAclineSegment && $spanAclineSegment->id == $item['segmentID']) {
                                if($disconnector->startIO_id == $item['segmentStartPoint']['id']) {
                                    $addDisconnectors['startLine'][] = $disconnector;
                                } else if($disconnector->startIO_id == $item['segmentEndPoint']['id']) {
                                    $addDisconnectors['endLine'][] = $disconnector;
                                }
                                if(!$disconnector->getTerminals()) {
                                    $startDisconnectorTerminal = new Terminal();
                                    $startDisconnectorTerminal->setSequenceNumber(1);
                                    $endDisconnectorTerminal = new Terminal();
                                    $endDisconnectorTerminal->setSequenceNumber(2);
                                    $disconnector->addTerminal($startDisconnectorTerminal);
                                    $disconnector->addTerminal($endDisconnectorTerminal);
                                } else {
                                    /** @var Terminal $disconnectorTerminal */
                                    foreach ($disconnector->getTerminals() as $disconnectorTerminal) {
                                        if($disconnectorTerminal->getSequenceNumber() == 1) {
                                            $startDisconnectorTerminal = $disconnectorTerminal;
                                        }
                                        if($disconnectorTerminal->getSequenceNumber() == 2) {
                                            $endDisconnectorTerminal = $disconnectorTerminal;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $acLineSegment = Aclinesegment::find($item['segmentID']);
                    if($item['segmentStartPoint']) {

                    }
                    $startTerminal = null;
                    $endTerminal = null;
                    if($acLineSegment->getTerminals()) {
                        /** @var Terminal $terminal */
                        foreach ($acLineSegment->getTerminals() as $terminal) {
                            if($terminal->getSequenceNumber() == 1) $startTerminal = $terminal;
                            if($terminal->getSequenceNumber() == 2) $endTerminal = $terminal;
                            if($terminal->getSequenceNumber() == 0) $terminal->delete();
                        }
                    }
                    if(!$startTerminal) {
                        $startTerminal = new Terminal();
                        $startTerminal->setSequenceNumber(1);
                    }
                    if(!$endTerminal) {
                        $endTerminal = new Terminal();
                        $endTerminal->setSequenceNumber(2);
                    }
                    $acLineSegment->addTerminal($startTerminal);
                    $acLineSegment->addTerminal($endTerminal);
                    $acLineSegment->getTerminals();
                    if($item['segmentStartPoint']) {
                        if(!@$connectivity[$item['segmentStartPoint']['id']]) {
                            $connectivity[$item['segmentStartPoint']['id']] = [];
                        }
                        if(!in_array($startTerminal, $connectivity[$item['segmentStartPoint']['id']], true)) {
                            if($addDisconnectors['startLine']) {
                                /** @var Disconnector $disconnector */
                                foreach ($addDisconnectors['startLine'] as $disconnector) {
                                    $connectivity[$item['segmentStartPoint']['id']][] = $startDisconnectorTerminal;
                                    $connectivityNode = $endDisconnectorTerminal->getConnectivityNode();
                                    if(!$connectivityNode && !$startTerminal->getConnectivityNode()) {
                                        $connectivityNode = new ConnectivityNode();
                                        $endDisconnectorTerminal->setConnectivityNode($connectivityNode);
                                        $startTerminal->setConnectivityNode($connectivityNode);
                                        $connectivityNode->save();
                                    } else if($connectivityNode) {
                                        /** @var Terminal $cnTerminal */
                                        foreach ($connectivityNode->getTerminals() as $cnTerminal) {
                                            if($cnTerminal->getMRID() != $endDisconnectorTerminal->getMRID() &&
                                                $cnTerminal->getMRID() != $startTerminal->getMRID()
                                            ) {
                                                $cnTerminal->removeConnectivityNode();
                                                $cnTerminal->save();
                                            }
                                        }
                                        $startTerminal->removeConnectivityNode();
                                        $startTerminal->setConnectivityNode($connectivityNode);
                                    } else {
                                        $connectivityNode = $startTerminal->getConnectivityNode();
                                        foreach ($connectivityNode->getTerminals() as $cnTerminal) {
                                            if($cnTerminal->getMRID() != $endDisconnectorTerminal->getMRID() &&
                                                $cnTerminal->getMRID() != $startTerminal->getMRID()
                                            ) {
                                                $cnTerminal->removeConnectivityNode();
                                                $cnTerminal->save();
                                            }
                                        }
                                        $endDisconnectorTerminal->removeConnectivityNode();
                                        $endDisconnectorTerminal->setConnectivityNode($connectivityNode);
                                    }

                                }
                            } else {
                                $connectivity[$item['segmentStartPoint']['id']][] = $startTerminal;
                            }
                        }
                    }
                    if($item['segmentEndPoint']) {
                        if(!@$connectivity[$item['segmentEndPoint']['id']]) {
                            $connectivity[$item['segmentEndPoint']['id']] = [];
                        }
                        if(!in_array($endTerminal, $connectivity[$item['segmentEndPoint']['id']], true)) {
                            if($addDisconnectors['endLine']) {
                                /** @var Disconnector $disconnector */
                                foreach ($addDisconnectors['endLine'] as $disconnector) {
                                    $connectivity[$item['segmentEndPoint']['id']][] = $endDisconnectorTerminal;
                                    $connectivityNode = $startDisconnectorTerminal->getConnectivityNode();
                                    if(!$connectivityNode && !$endTerminal->getConnectivityNode()) {
                                        $connectivityNode = new ConnectivityNode();
                                        $startDisconnectorTerminal->setConnectivityNode($connectivityNode);
                                        $endTerminal->setConnectivityNode($connectivityNode);
                                        $connectivityNode->save();
                                    } else if($connectivityNode) {
                                        /** @var Terminal $cnTerminal */
                                        foreach ($connectivityNode->getTerminals() as $cnTerminal) {
                                            if($cnTerminal->getMRID() != $startDisconnectorTerminal->getMRID() &&
                                                $cnTerminal->getMRID() != $endTerminal->getMRID()
                                            ) {
                                                $cnTerminal->removeConnectivityNode();
                                                $cnTerminal->save();
                                            }
                                        }
                                        $endTerminal->removeConnectivityNode();
                                        $endTerminal->setConnectivityNode($connectivityNode);
                                    } else {
                                        $connectivityNode = $endTerminal->getConnectivityNode();
                                        foreach ($connectivityNode->getTerminals() as $cnTerminal) {
                                            if($cnTerminal->getMRID() != $startDisconnectorTerminal->getMRID() &&
                                                $cnTerminal->getMRID() != $endTerminal->getMRID()
                                            ) {
                                                $cnTerminal->removeConnectivityNode();
                                                $cnTerminal->save();
                                            }
                                        }
                                        $startDisconnectorTerminal->removeConnectivityNode();
                                        $startDisconnectorTerminal->setConnectivityNode($connectivityNode);
                                    }

                                }

                            } else {
                                $connectivity[$item['segmentEndPoint']['id']][] = $endTerminal;
                            }

                        }
                    }
                    if($item['segmentName']) {
                        $acLineSegment->setName($item['segmentName']);
                    }
                    if($item['segmentLenght']) {
                        $newLength = $item['segmentLenght'];
                        if($acLineSegment->getLength()) {
                            $length  = $acLineSegment->getLength();
                        } else {
                            $length = new Length();
                        }
                        $length->setValue($newLength);
                        $length->setUnit(UnitSymbol::where('value', 2)->get()->get(0));
                        $length->setMultiplier(UnitMultiplier::where('value', 0)->get()->get(0));
                        $acLineSegment->setLength($length);
                    }
                    foreach ($disconnectors as $disconnector) {
                        $disconnector->setEquipmentContainer($line->getEquipmentContainer());
                        $disconnector->selfIdentification();
                        $psrType = $disconnector->getPSRType();
                        if(!$psrType) {
                            $psrType = $this->getPSRType('Disconnector');
                            $disconnector->setPSRType($psrType);
                        }
                        if($baseVoltage) $disconnector->setBaseVoltage($baseVoltage);

                        $disconnector->save();
                    }
                    $acLineSegment->save();

                }
            }
            $terminals = '';
            foreach ($connectivity as $connectivities) {
                if(count($connectivities) > 1) {
                    $connectivityNode = null;
                    foreach ($connectivities as $terminal) {
                        if($terminal->getConnectivityNode() && !$connectivityNode) {
                            $connectivityNode = $terminal->getConnectivityNode();
                        }
                        $terminal->removeConnectivityNode();
                    }
                    if(!$connectivityNode) {
                        $connectivityNode = new ConnectivityNode();
                    } else {
                        $a = 19;
                    }
                    foreach ($connectivityNode->getTerminals() as $terminal) {
                        $terminal->removeConnectivityNode();
                        $terminal->save();
                    }
                    foreach ($connectivities as $terminal) {
                        $terminals .= $terminal->id . ',';
                        $terminal->setConnectivityNode($connectivityNode);
                        $terminal->save();
                    }
                }
            }
            //$psrType  = $this->getPSRType('AC Line');
            if(!$line->getPSRType()) {
                $psrType  = $this->getPSRType('AC Line');
                $line->setPSRType($psrType);
            }
            $line->save();
            $dom = $this->createCim($line);
            return response($dom->saveXML(), 200, ['Content-Type' => 'application/xml']);
        }
        /** end save line into CIM DB  */

    }
    /**
     * add R,X,Bch,Gch -
     */
    protected function addRBToACLineSegment(Aclinesegment $aclinesegment, \DOMDocument $dom, \DOMNode $aclineSegmentNode)
    {
        $cimNS = $this->cimNS;
        if($aclinesegment->getR0() && false) $aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.r0', $aclinesegment->getR0()->getValue() . ' ' .
            (($aclinesegment->getR0()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getR0()->getMultiplier()->getLiteral() : '') .
            $aclinesegment->getR0()->getUnit()->literal));
        if($aclinesegment->getX0() && false)$aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.x0', $aclinesegment->getX0()->getValue() . ' ' .
        (($aclinesegment->getX0()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getX0()->getMultiplier()->getLiteral() : '').
            $aclinesegment->getX0()->getUnit()->literal));
        if($aclinesegment->getB0ch() && false)$aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.b0ch', $aclinesegment->getB0ch()->getValue() . ' ' .
        (($aclinesegment->getB0ch()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getB0ch()->getMultiplier()->getLiteral() : '') .
            $aclinesegment->getB0ch()->getUnit()->literal));
        if($aclinesegment->getG0ch() && false)$aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.g0ch', $aclinesegment->getG0ch()->getValue() . ' ' .
        (($aclinesegment->getG0ch()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getG0ch()->getMultiplier()->getLiteral() : '') .
            $aclinesegment->getG0ch()->getUnit()->literal));

        if($aclinesegment->getR())$aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.r', $aclinesegment->getR()->getValue() . ' ' .
        (($aclinesegment->getR()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getR()->getMultiplier()->getLiteral() : '') .
            $aclinesegment->getR()->getUnit()->literal));
        if($aclinesegment->getX())$aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.x', $aclinesegment->getX()->getValue() . ' ' .
        (($aclinesegment->getX()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getX()->getMultiplier()->getLiteral() : '') .
            $aclinesegment->getX()->getUnit()->literal));
        if($aclinesegment->getBch())$aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.bch', $aclinesegment->getBch()->getValue() . ' ' .
        (($aclinesegment->getBch()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getBch()->getMultiplier()->getLiteral() : '') .
            $aclinesegment->getBch()->getUnit()->literal));
        if($aclinesegment->getGch())$aclineSegmentNode->appendChild($dom->createElementNS($cimNS, 'cim:ACLineSegment.gch', $aclinesegment->getGch()->getValue() . ' ' .
        (($aclinesegment->getGch()->getMultiplier()->getLiteral() != 'none') ? $aclinesegment->getGch()->getMultiplier()->getLiteral() : '') .
            $aclinesegment->getGch()->getUnit()->literal));

    }
    protected function getTerminalCim(Terminal $terminal, \DOMDocument  $dom, IdentifiedObjectInterface $conductingEquipment) : \DOMNode
    {
        $cimNS = $this->cimNS;
        $rdfNS = $this->rdfNS;
        $terminalNode = $dom->createElementNS($cimNS, 'cim:Terminal');
        $terminalNode->setAttributeNS($rdfNS, 'rdf:about', '#_' . $terminal->getMRID());
        $terminalNode->appendChild(
            $dom->createElementNS($cimNS, 'cim:Terminal.sequenceNumber', $terminal->getSequenceNumber())
        );
        $phases = $dom->createElementNS($cimNS, 'cim:Terminal.phases');
        $phases->setAttributeNS($rdfNS, 'rdf:resource', 'http://iec.ch/TC57/2016/CIM-schema-cim17#PhaseCode.ABC');
        $terminalNode->appendChild($phases);
        if($conductingEquipment instanceof ConductingEquipmentInterface) $ce =  $dom->createElementNS($cimNS, 'cim:Terminal.ConductingEquipment');
        else if($conductingEquipment instanceof AuxiliaryEquipmentInterface) $ce =  $dom->createElementNS($cimNS, 'cim:Terminal.AuxiliaryEquipment');
        if($ce) $ce->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $conductingEquipment->getMRID());
        $terminalNode->appendChild($ce);
        if($terminal->getConnectivityNode()) {
            $cnNode = $dom->createElementNS($cimNS, 'cim:Terminal.ConnectivityNode');
            $cnNode->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $terminal->getConnectivityNode()->getMRID());
            $terminalNode->appendChild($cnNode);

        }
        return $terminalNode;
    }

    protected function getConnectivityNodeCim(\DOMDocument  $dom, ConnectivityNode $cn, ConnectivityNodeContainerInterface $cc) : \DOMNode
    {
        $cimNS = $this->cimNS;
        $rdfNS = $this->rdfNS;
        $cnNode =  $dom->createElementNS($cimNS, 'cim:ConnectivityNode');
        $cnNode->setAttributeNS($rdfNS, 'rdf:about', '#_' . $cn->getMRID());
        $cnContainer =  $dom->createElementNS($cimNS, 'cim:ConnectivityNode.ConnectivityNodeContainer');
        $cnContainer->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $cc->getMRID());
        $cnNode->appendChild($cnContainer);
        $cnTerminals = $cn->getTerminals();
        /** @var Terminal $cnTerminal */
        foreach ($cnTerminals as $cnTerminal) {
            $cnTerminalNode = $dom->createElementNS($cimNS, 'cim:ConnectivityNode.Terminals');
            $cnTerminalNode->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $cnTerminal->getMRID());
            $cnNode->appendChild($cnTerminalNode);
        }
        return $cnNode;
    }
    function createCim(Line $line) : \DOMDocument
    {
        $dom = new \DOMDocument();
        $cimNS = $this->cimNS;
        $rdfNS = $this->rdfNS;
        $rdfNode = $dom->createElementNS($rdfNS, 'rdf:RDF' );
        $dom->appendChild($rdfNode);
        $rdfNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cim', $cimNS);
        $lineNode = $dom->createElementNS($cimNS, 'cim:Line');
        $lineNode->setAttributeNS($rdfNS, 'rdf:about', '#_' . $line->getMRID());
        $lineNodeIdentifiedObjectName =  $dom->createElementNS($cimNS, 'cim:IdentifiedObject.name', $line->getName());
        $lineNodeIdentifiedObjectMRID =  $dom->createElementNS($cimNS, 'cim:IdentifiedObject.MRID', $line->getMRID());
        $linePowerSystemResourcePSRType =  $dom->createElementNS($cimNS, 'cim:PowerSystemResource.PSRType');
        $linePowerSystemResourcePSRType->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $line->getPSRType()->getMRID());
        $psrTypes = [];
        $psrTypes[] = $line->getPSRType();

        $lineNode->appendChild($lineNodeIdentifiedObjectName);
        $lineNode->appendChild($lineNodeIdentifiedObjectMRID);
        $lineNode->appendChild($linePowerSystemResourcePSRType);
        $rdfNode->appendChild($lineNode);
        $equipments = $line->getEquipments();
        /** @var Equipment $equipment */
        $cnAdded = [];
        $baseVoltages = [];
        foreach ($equipments as $equipment) {
            /** @var EquipmentInterface $equipmentObj */
            $equipmentObj = $this->getObjectByMRID($equipment->getMRID());
            if($equipmentObj) {
                if($equipmentObj instanceof Aclinesegment) {
                    $equipmentNode = $dom->createElementNS($cimNS, 'cim:ACLineSegment');
                    $this->addRBToACLineSegment($equipmentObj, $dom, $equipmentNode);
                } else if($equipmentObj instanceof Disconnector) {
                    $equipmentNode = $dom->createElementNS($cimNS, 'cim:Disconnector');
                } else if($equipmentObj instanceof SurgeArrester){
                    $equipmentNode = $dom->createElementNS($cimNS, 'cim:SurgeArrester');
                }
                $equipmentNode->setAttributeNS($rdfNS, 'rdf:about', '#_' . $equipmentObj->getMRID());
                if($equipment->getName()) {
                    $equipmentName = $dom->createElementNS($cimNS, 'cim:IdentifiedObject.name', $equipment->getName());
                    $equipmentNode->appendChild($equipmentName);
                }
                if($equipment instanceof ConductingEquipmentInterface) {
                    $equipmentBaseVoltage = $dom->createElementNS($cimNS, 'cim:ConductingEquipment.BaseVoltage');
                    $equipmentBaseVoltage->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $equipmentObj->getBaseVoltage()->getMRID());
                    $equipmentNode->appendChild($equipmentBaseVoltage);
                    if(!in_array($equipmentObj->getBaseVoltage()->getMRID(), $baseVoltages)) {
                        $baseVoltages[] = $equipmentObj->getBaseVoltage();
                    }
                }
                $clamps = [];
                if($equipmentObj instanceof Aclinesegment && $equipmentObj->getLength()) {
                    $Conductorlength = $dom->createElementNS($cimNS, 'cim:Conductor.length',
                        $equipmentObj->getLength()->getValue() . ' '.
                        (($equipmentObj->getLength()->getMultiplier()->getValue() != 0) ?
                            $equipmentObj->getLength()->getMultiplier()->getLiteral() : '' ) .
                        $equipmentObj->getLength()->getUnit()->literal );
                    $equipmentNode->appendChild($Conductorlength);
                    /** @var Clamp $clamp */

                    foreach ($equipmentObj->getClamp() as $clamp) {
                        $clampNode = $dom->createElementNS($cimNS, 'cim:Clamp');
                        $clampNode->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $clamp->getMRID());
                        $equipmentNode->appendChild($clampNode);
                        $clamps[] = $clamp;
                    }
                }

                $ceContainerNode = $dom->createElementNS($cimNS, 'cim:Equipment.EquipmentContainer');
                $ceContainerNode->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $line->getMRID());
                $equipmentNode->appendChild($ceContainerNode);
                $MRIDNpode =  $dom->createElementNS($cimNS, 'cim:IdentifiedObject.MRID', $equipmentObj->getMRID());
                $equipmentNode->appendChild($MRIDNpode);
                $PSRTypeNode =  $dom->createElementNS($cimNS, 'cim:PowerSystemResource.PSRType');
                $PSRTypeNode->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $equipmentObj->getPSRType()->getMRID());
                $equipmentNode->appendChild($PSRTypeNode);
                if(!in_array($equipmentObj->getPSRType(), $psrTypes)) {
                    $psrTypes[] = $equipmentObj->getPSRType();
                }
                $rdfNode->appendChild($equipmentNode);

                if($equipmentObj instanceof ConductingEquipmentInterface) $terminals = $equipmentObj->getTerminals();
                if($equipmentObj instanceof AuxiliaryEquipmentInterface) $terminals = [$equipmentObj->getTerminal()];
                /** @var Terminal $terminal */
                $cnArray = [];
                if($clamps) {
                    foreach ($clamps as $clamp) {
                        $clampNode = $dom->createElementNS($cimNS, 'cim:Clamp');
                        $clampNode->setAttributeNS($rdfNS, 'rdf:about', '#_' . $clamp->getMRID());
                        $segmentForClampNode = $dom->createElementNS($cimNS, 'cim:Clamp.ACLineSegment');
                        $segmentForClampNode->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $clamp->getACLineSegment()->getMRID());
                        $clampNode->appendChild($segmentForClampNode);
                        if($clamp->getlengthFromTerminal1()) {
                            $ClampLength = $dom->createElementNS($cimNS, 'cim:lengthFromTerminal1',
                                $clamp->getlengthFromTerminal1()->getValue() . ' '.
                                (($clamp->getlengthFromTerminal1()->getMultiplier()->getValue() != 0) ?
                                    $clamp->getlengthFromTerminal1()->getMultiplier()->getLiteral() : '' ) .
                                $clamp->getlengthFromTerminal1()->getUnit()->literal );
                            $clampNode->appendChild($ClampLength);
                        }
                        foreach ($clamp->getTerminals() as $clampTerminal) {
                            $terminalForClamp = $dom->createElementNS($cimNS, 'cim:ConductingEquipmen.Terminal');
                            $terminalForClamp->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $clampTerminal->getMRID());
                            $clampNode->appendChild($terminalForClamp);
                            $cnArray[] = $clampTerminal->getConnectivityNode();
                            $terminalNode = $this->getTerminalCim($clampTerminal, $dom, $equipmentObj);
                            $rdfNode->appendChild($terminalNode);
                        }
                        $clampIo =  $dom->createElementNS($cimNS, 'cim:IdentifiedObject.MRID', $clamp->getMRID());
                        $clampNode->appendChild($clampIo);
                        $equipmentBaseVoltage = $dom->createElementNS($cimNS, 'cim:ConductingEquipment.BaseVoltage');
                        $equipmentBaseVoltage->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $equipmentObj->getBaseVoltage()->getMRID());
                        $clampNode->appendChild($equipmentBaseVoltage);
                        if(!in_array($equipmentObj->getBaseVoltage()->getMRID(), $baseVoltages)) {
                            $baseVoltages[] = $equipmentObj->getBaseVoltage();
                        }
                        $rdfNode->appendChild($clampNode);


                    }
                }
                foreach ($terminals as $terminal) {
                    $terminalNode =  $dom->createElementNS($cimNS, 'cim:ConductingEquipment.Terminals');
                    $terminalNode->setAttributeNS($rdfNS, 'rdf:resource', '#_' . $terminal->getMRID());
                    $equipmentNode->appendChild($terminalNode);
                    if($terminal->getConnectivityNode() && !in_array($terminal->getConnectivityNode(), $cnArray)) {
                        $cnArray[] = $terminal->getConnectivityNode();
                    }
                    $terminalNode = $this->getTerminalCim($terminal, $dom, $equipmentObj);
                    $rdfNode->appendChild($terminalNode);
                }
                foreach ($cnArray as $cn) {
                    if(!in_array($cn->getMRID(), $cnAdded)) {
                        $cnAdded[] = $cn->getMRID();
                        $cnNode = $this->getConnectivityNodeCim($dom, $cn, $line);
                        $rdfNode->appendChild($cnNode);

                    }

                }
            }
        }
        /** add base voltages */
        /** @var BaseVoltage $baseVoltage */
        $baseVoltageShowed = [];
        foreach ($baseVoltages as $baseVoltage) {
            if(in_array($baseVoltage->getMRID(), $baseVoltageShowed)) continue;
            $baseVoltageShowed[] = $baseVoltage->getMRID();
            $baseVoltageNode = $dom->createElementNS($cimNS, 'cim:BaseVoltage');
            $baseVoltageNode->setAttributeNS($rdfNS, 'rdf:about', '#_' . $baseVoltage->getMRID());
            $voltageNode =  $dom->createElementNS($cimNS, 'cim:BaseVoltage.nominalVoltage',
                $baseVoltage->getNominalVoltage()->getValue(). ' ' .
                $baseVoltage->getNominalVoltage()->getMultiplier()->getLiteral() .
                $baseVoltage->getNominalVoltage()->getUnit()->literal
            );
            $baseVoltageNode->appendChild($voltageNode);
            $rdfNode->appendChild($baseVoltageNode);
        }
        //** add psr type  etc */
        foreach ($psrTypes as $PSRType) {
            $linePSRType = $dom->createElementNS($cimNS, 'cim:PSRType');
            $linePSRType->setAttributeNS($rdfNS, 'rdf:about', '#_' . $PSRType->getMRID());
            $linePSRTypeName = $dom->createElementNS($cimNS, 'cim:IdentifiedObject.name',  $PSRType->getName());
            $linePSRType->appendChild($linePSRTypeName);
            $rdfNode->appendChild($linePSRType);
        }

        return $dom;


    }

    protected function getObjectByMRID($mrid = '') :? IdentifiedObjectInterface
    {
        $io = Identifiedobject::whereMrid($mrid)->where('class_object', '!=', "")->get()->get(0);
        if($io && $io->class_object) {
            $classAndId = explode(':', $io->class_object);
            if(@$classAndId[0] && @$classAndId[1]) {
                $className = $classAndId[0];
                $id = $classAndId[1];
                if(class_exists($className)) {
                    return $className::find($id);
                }
            }
        } else return null;
    }

    protected function getPSRType($type = '') : PSRType
    {
        $psrType = null;
        $ioWithThisType = Identifiedobject::where([
            ['name', '=', $type],
            ['class_object', 'like', '%PSRType:%']
        ])->get()->get(0);
        if($ioWithThisType) {
            $psrType = PSRType::where('identifiedobject_id', $ioWithThisType->id)->get()->get(0);
        }
        if(!$psrType) {
            $psrType = new PSRType();
            $psrType->setName($type);
            $psrType->save();
            $psrType->selfIdentification();
            $psrType->save();
            //$psrType->selfIdentification();
        }
        return $psrType;
    }
}
