<?php


namespace App\Traits;


use App\Models\Clamp;
use App\Models\Aclinesegmentinfo;
use App\Models\Conductance;
use App\Models\Conductor;
use App\Models\Reactance;
use App\Models\Resistance;
use App\Models\Susceptance;
use App\Models\SwitchPhase;
use App\Models\Temperature;
use App\Models\UnitMultiplier;
use App\Models\UnitSymbol;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait ACLineSegmentTrait
{
    use ConductorTrait;

    public function b0ch(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Susceptance::class, 'b0ch_id');
    }
    public function bch(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Susceptance::class, 'bch_id');
    }
    public function g0ch(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Conductance::class, 'g0ch_id');
    }
    public function gch(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Conductance::class, 'gch_id');
    }
    public function r(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Resistance::class, 'r_id');
    }
    public function r0(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Resistance::class, 'r0_id');
    }
    public function x(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Reactance::class, 'x_id');
    }
    public function x0(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Reactance::class, 'x0_id');
    }
    public function shortCircuitEndTemperature(): BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Temperature::class, 'temperatures_id');
    }

    public function conductor() : BelongsTo
    {
        return $this->getACLineSegment()->belongsTo(Conductor::class, 'conductors_id');
    }

    public function clamp() : HasOne
    {
        return $this->getACLineSegment()->hasOne(Clamp::class, 'aclinesegment_id');
    }

    public function addClamp(Clamp $clamp) : void
    {
        $this->getACLineSegment()->clamp[] = $clamp;
    }

    public function removeClamp(Clamp $clamp) : void
    {
        if(in_array($clamp, $this->getACLineSegment()->clamp)) {
            array_splice($this->getACLineSegment()->clamp, array_search($clamp, $this->getACLineSegment()->clamp), 1);
        }
    }

    public function getClamp() : array
    {
        if($this->getACLineSegment()->clamp) return $this->getACLineSegment()->clamp;
        if($this->clamp()->get()->count()) {
            foreach ($this->clamp()->get() as $clamp) $this->getACLineSegment()->clamp[] = $clamp;
        }
        return $this->getACLineSegment()->clamp;
    }




    /**
     * @return Susceptance
     */
    public function getB0ch(): ?Susceptance
    {
        if($this->getACLineSegment()->b0ch) return $this->getACLineSegment()->b0ch;
        $this->getACLineSegment()->b0ch = $this->b0ch()->get()->get(0);
        $markInfo = Aclinesegmentinfo::find($this->getACLineSegment()->wiremark_id);
        if(!$this->getACLineSegment()->b0ch && $markInfo) {
            $bch0 = new Susceptance();
            $bch0->setValue($markInfo->b);
            $bch0->setUnit(UnitSymbol::find(18));// find "S"
            $bch0->setMultiplier(UnitMultiplier::find(11));
            $this->setB0ch($bch0);
        }
        return $this->getACLineSegment()->b0ch;
    }

    /**
     * @param Susceptance $b0ch
     */
    public function setB0ch(?Susceptance $b0ch): void
    {
        $this->getACLineSegment()->b0ch = $b0ch;
    }

    /**
     * @return Susceptance
     */
    public function getBch(): ?Susceptance
    {
         //if($this->getACLineSegment()->bch) return $this->getACLineSegment()->bch;
        //$this->getACLineSegment()->bch = $this->bch()->get()->get(0);
        if($this->getB0ch()) {
            $l = 0;
            if($this->getACLineSegment()->getLength()) {
                $l = $this->getACLineSegment()->getLength()->getValue() *
                    pow(10, $this->getACLineSegment()->getLength()->getMultiplier()->getValue());
                if(!$this->getB0ch()) {

                }
                $bchValue = $this->getB0ch()->getValue() * ($l / 1000);
                $bch = new Susceptance();
                $bch->setValue($bchValue);
                $bch->setUnit($this->getB0ch()->getUnit());// find "S"
                $bch->setMultiplier(UnitMultiplier::find(7));//micro
                $this->setBch($bch);
            }
        }
        return $this->getACLineSegment()->bch;
    }

    /**
     * @param Susceptance $bch
     */
    public function setBch(?Susceptance $bch): void
    {
        $this->getACLineSegment()->bch = $bch;
    }

    /**
     * @return Conductance
     */
    public function getG0ch(): ?Conductance
    {
        if($this->getACLineSegment()->g0ch) return $this->getACLineSegment()->g0ch;
        $this->getACLineSegment()->g0ch = $this->g0ch()->get()->get(0);
        $markInfo = Aclinesegmentinfo::find($this->getACLineSegment()->wiremark_id);
        if(!$this->getACLineSegment()->g0ch && $markInfo) {
            $gch0 = new Conductance();
            $gch0->setValue($markInfo->g);
            $gch0->setUnit(UnitSymbol::find(18));// find "S"
            $gch0->setMultiplier(UnitMultiplier::find(11));
            $this->setG0ch($gch0);
        }
        return $this->getACLineSegment()->g0ch;
    }

    /**
     * @param Conductance $g0ch
     */
    public function setG0ch(?Conductance $g0ch): void
    {

        $this->getACLineSegment()->g0ch = $g0ch;
    }

    /**
     * @return Conductance
     */
    public function getGch(): ?Conductance
    {
        //if($this->getACLineSegment()->gch) return $this->getACLineSegment()->gch;
       //$this->getACLineSegment()->gch = $this->gch()->get()->get(0);
        if(!$this->getACLineSegment()->gch && $this->getG0ch()) {
            $l = 0;
            if($this->getACLineSegment()->getLength()) {
                $l = $this->getACLineSegment()->getLength()->getValue() *
                    pow(10, $this->getACLineSegment()->getLength()->getMultiplier()->getValue());
                $gchValue = $this->getG0ch()->getValue() * ($l / 1000) * pow(10, -6);
                $gch = new Conductance();
                $gch->setValue($gchValue);
                $gch->setUnit($this->getG0ch()->getUnit());// find "S"
                $gch->setMultiplier(UnitMultiplier::find(7));
                $this->setGch($gch);
            }
        }
        return $this->getACLineSegment()->gch;
    }

    /**
     * @param Conductance $gch
     */
    public function setGch(?Conductance $gch): void
    {
        $this->getACLineSegment()->gch = $gch;
    }

    /**
     * @return Resistance
     */
    public function getR(): ?Resistance
    {
        //if($this->getACLineSegment()->r) return $this->getACLineSegment()->r;
        //$this->getACLineSegment()->r = $this->r()->get()->get(0);
        if($this->getR0()) {
            $l = 0;
            if($this->getACLineSegment()->getLength()) {
                $l = $this->getACLineSegment()->getLength()->getValue() *
                    pow(10, $this->getACLineSegment()->getLength()->getMultiplier()->getValue());
                $rValue = $this->getR0()->getValue() * ($l / 1000);
                $r = new Resistance();
                $r->setValue($rValue);
                $r->setUnit($this->getR0()->getUnit());// find "ohm"
                $r->setMultiplier($this->getR0()->getMultiplier());
                $this->setR($r);
            }
        }
        return $this->getACLineSegment()->r;
    }

    /**
     * @param Resistance $r
     */
    public function setR(?Resistance $r): void
    {
        $this->getACLineSegment()->r = $r;
    }

    /**
     * @return Resistance
     */
    public function getR0(): ?Resistance
    {
        if($this->getACLineSegment()->r0) return $this->getACLineSegment()->r0;
        $this->getACLineSegment()->r0 = $this->r0()->get()->get(0);
        $markInfo = Aclinesegmentinfo::find($this->getACLineSegment()->wiremark_id);
        if(!$this->getACLineSegment()->r0 && $markInfo) {
            $r0 = new Resistance();
            $r0->setValue($markInfo->r);
            $r0->setUnit(UnitSymbol::find(21));// find "ohm"
            $r0->setMultiplier(UnitMultiplier::find(11));
            $this->setR0($r0);
        }
        return $this->getACLineSegment()->r0;
    }

    /**
     * @param Resistance $r0
     */
    public function setR0(?Resistance $r0): void
    {
        $this->getACLineSegment()->r0 = $r0;
    }

    /**
     * @return Reactance
     */
    public function getX(): ?Reactance
    {
        //if($this->getACLineSegment()->x) return $this->getACLineSegment()->x;
        //$this->getACLineSegment()->x = $this->x()->get()->get(0);
        if($this->getACLineSegment()->wiremark_id) {
            if($this->getX0()) {
                $l = 0;
                if($this->getACLineSegment()->getLength()) {
                    $l = $this->getACLineSegment()->getLength()->getValue() *
                        pow(10, $this->getACLineSegment()->getLength()->getMultiplier()->getValue());
                    $xValue = $this->getX0()->getValue() * ($l / 1000);
                    $x = new Reactance();
                    $x->setValue($xValue);
                    $x->setUnit($this->getX0()->getUnit());
                    $x->setMultiplier($this->getX0()->getMultiplier());
                    $this->setX($x);
                }
            }
        }
        return $this->getACLineSegment()->x;
    }

    /**
     * @param Reactance $x
     */
    public function setX(?Reactance $x): void
    {
        $this->getACLineSegment()->x = $x;
    }

    /**
     * @return Reactance
     */
    public function getX0(): ?Reactance
    {
        if($this->getACLineSegment()->x0) return $this->getACLineSegment()->x0;
        $this->getACLineSegment()->x0 = $this->x0()->get()->get(0);
        $markInfo = Aclinesegmentinfo::find($this->getACLineSegment()->wiremark_id);
        if(!$this->getACLineSegment()->x0 && $markInfo) {
            $x0 = new Reactance();
            $x0->setValue($markInfo->x);
            $x0->setUnit(UnitSymbol::find(21));// find "ohm"
            $x0->setMultiplier(UnitMultiplier::find(11));
            $this->setx0($x0);
        }
        return $this->getACLineSegment()->x0;
    }

    /**
     * @param Reactance $x0
     */
    public function setX0(?Reactance $x0): void
    {
        $this->getACLineSegment()->x0 = $x0;
    }

    /**
     * @return Temperature
     */
    public function getShortCircuitEndTemperature(): ?Temperature
    {
        if($this->getACLineSegment()->shortCircuitEndTemperature) return $this->getACLineSegment()->shortCircuitEndTemperature;
        $this->getACLineSegment()->shortCircuitEndTemperature = $this->shortCircuitEndTemperature()->get()->get(0);
        return $this->getACLineSegment()->shortCircuitEndTemperature;
    }

    /**
     * @param Temperature $shortCircuitEndTemperature
     */
    public function setShortCircuitEndTemperature(?Temperature $shortCircuitEndTemperature): void
    {
        $this->getACLineSegment()->shortCircuitEndTemperature = $shortCircuitEndTemperature;
    }

    /**
     * @return Conductor
     */
    public function getConductor(): ?Conductor
    {
        if($this->getACLineSegment()->conductor) return $this->getACLineSegment()->conductor;
        $this->getACLineSegment()->conductor = $this->conductor()->get()->get(0);
        if(!$this->getACLineSegment()->conductor) $this->getACLineSegment()->conductor = new Conductor();
        return $this->getACLineSegment()->conductor;
    }

    /**
     * @param Conductor $conductor
     */
    public function setConductor(?Conductor $conductor): void
    {
        $this->getACLineSegment()->conductor = $conductor;
    }
}
