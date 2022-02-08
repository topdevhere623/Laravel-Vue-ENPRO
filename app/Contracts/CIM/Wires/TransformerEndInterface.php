<?php


namespace App\Contracts\CIM\Wires;


use App\Models\BaseVoltage;
use App\Models\Terminal;

/**
 * Interface TransformerEndInterface
 * @package App\Contracts
 */
interface TransformerEndInterface extends IdentifiedObjectInterface
{
    /**
     * @return float
     */
    public function getBmagSat(): float;

    /**
     * @param float $bmagSat
     */
    public function setBmagSat(float $bmagSat) : void;

    /**
     * @return int
     */
    public function getEndNumber(): int;

    /**
     * @param int $endNumber
     */
    public function setEndNumber(int $endNumber) : void;

    /**
     * @return bool
     */
    public function getGrounded() : bool;

    /**
     * @param bool $grounded
     */
    public function setGrounded(bool $grounded) : void;

    /**
     * @return float
     */
    public function getMagBaseU() : float;

    /**
     * @param float $magBaseU
     */
    public function setMagBaseU(float $magBaseU) : void;

    /**
     * @return float
     */
    public function getMagSatFlux() : float;

    /**
     * @param float $magSatFlux
     */
    public function setMagSatFlux(float $magSatFlux) : void;

    /**
     * @return float
     */
    public function getRground() : float;

    /**
     * @param float $rground
     */
    public function setRground(float $rground) : void;

    /**
     * @return float
     */
    public function getXground() : float;

    /**
     * @param float $xground
     */
    public function setXground(float $xground) : void;

    /**
     * @return BaseVoltageInterface
     */
    public function getBaseVoltage() : BaseVoltage;

    /**
     * @param BaseVoltageInterface $baseVoltage
     */
    public function setBaseVoltage(BaseVoltage $baseVoltage) : void;

    /**
     * @return Terminal
     */
    public function getTerminal() : Terminal;

    /**
     * @param Terminal $terminal
     */
    public function setTerminal(Terminal $terminal): void;




}
