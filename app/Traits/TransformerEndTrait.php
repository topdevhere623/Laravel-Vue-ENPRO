<?php


namespace App\Traits;


use App\Models\BaseVoltage;
use App\Models\Identifiedobject;
use App\Models\Terminal;
use App\Models\TransformerEnd;

/**
 * Trait TransformerEndTrait
 * @package App\Traits
 */
trait TransformerEndTrait
{
    use IdentifiedObjectTrait;

    /**
     * @var TransformerEnd
     */
    protected $transformerEnd;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identifiedobject()
    {
        return $this->getTransformerEnd()->belongsTo(Identifiedobject::class);
    }

    /**
     * @param Identifiedobject $identifiedobject
     */
    public function setIdentifiedObject(Identifiedobject $identifiedobject)
    {
        $this->identifiedobject()->associate($identifiedobject);
    }

    public function getIdentifiedObject() : Identifiedobject
    {
        if($this->getTransformerEnd()->identifiedObject) return $this->getTransformerEnd()->identifiedObject;
        $this->getTransformerEnd()->identifiedObject = $this->identifiedobject()->get()->get(0);
        if(!$this->getTransformerEnd()->identifiedObject) $this->getTransformerEnd()->identifiedObject = new Identifiedobject();
        return $this->getTransformerEnd()->identifiedObject;
    }

    /**
     * @return BaseVoltage
     */
    public function getBaseVoltage() : BaseVoltage
    {
        return $this->basevoltage()->get()->get(0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function basevoltage()
    {
        return $this->getTransformerEnd()->belongsTo(BaseVoltage::class);
    }

    /**
     * @param BaseVoltage $baseVoltage
     */
    public function setBaseVoltage(BaseVoltage $baseVoltage) : void
    {
        $this->basevoltage()->associate($baseVoltage);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function terminal()
    {
        return $this->getTransformerEnd()->belongsTo(Terminal::class);
    }

    /**
     * @return Terminal
     */
    public function getTerminal() : Terminal
    {
        $terminal = $this->terminal()->get()->get(0);
        if(!($terminal instanceof Terminal)) return new Terminal();
    }

    /**
     * @param Terminal $terminal
     */
    public function setTerminal(Terminal $terminal): void
    {
        $this->terminal()->associate($terminal);
    }

    /**
     * @return float
     */
    public function getBmagSat(): float
    {
        return $this->getTransformerEnd()->bmag_sat;
    }

    /**
     * @param float $bmagSat
     */
    public function setBmagSat(float $bmagSat) : void
    {
        $this->getTransformerEnd()->bmag_sat = $bmagSat;
    }

    /**
     * @return int
     */
    public function getEndNumber(): int
    {
        return $this->getTransformerEnd()->end_number;
    }

    /**
     * @param int $endNumber
     */
    public function setEndNumber(int $endNumber) : void
    {
        $this->getTransformerEnd()->end_number = $endNumber;
    }

    /**
     * @return bool
     */
    public function getGrounded() : bool
    {
        return $this->getTransformerEnd()->grounded ? true : false;
    }

    /**
     * @param bool $grounded
     */
    public function setGrounded(bool $grounded) : void
    {
        $this->getTransformerEnd()->grounded = $grounded ? 1 : 0;
    }

    /**
     * @return float
     */
    public function getMagBaseU() : float
    {
        return $this->getTransformerEnd()->mag_base_u;
    }

    /**
     * @param float $magBaseU
     */
    public function setMagBaseU(float $magBaseU) : void
    {
        $this->getTransformerEnd()->mag_base_u = $magBaseU;
    }

    /**
     * @return float
     */
    public function getMagSatFlux() : float
    {
        return $this->getTransformerEnd()->mag_sat_flux;
    }

    /**
     * @param float $magSatFlux
     */
    public function setMagSatFlux(float $magSatFlux) : void
    {
        $this->getTransformerEnd()->mag_sat_flux = $magSatFlux;
    }

    /**
     * @return float
     */
    public function getRground() : float
    {
        return $this->getTransformerEnd()->rground;
    }

    /**
     * @param float $rground
     */
    public function setRground(float $rground) : void
    {
        $this->getTransformerEnd()->rground = $rground;
    }

    /**
     * @return float
     */
    public function getXground() : float
    {
        return $this->getTransformerEnd()->xground;
    }

    /**
     * @param float $xground
     */
    public function setXground(float $xground) : void
    {
        $this->getTransformerEnd()->xground = $xground;
    }


}
