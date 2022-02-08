<?php


namespace App\Contracts\CIM\Wires;


use App\Models\EquipmentContainer;

/**
 * Interface EquipmentInterface
 * @package App\Contracts\CIM\Wires
 */
interface EquipmentInterface extends PowerSystemResourceInterface
{
    /**
     * @return bool
     */
    public function getNormallyInService() : bool;

    /**
     * @param bool $value
     */
    public function setNormallyInService(bool $value) : void;

    /**
     * @return bool
     */
    public function getInService() : bool;

    /**
     * @param bool $value
     */
    public function setInService(bool $value) : void;

    /**
     * @return bool
     */
    public function getAggregate() : bool;

    /**
     * @param bool $value
     */
    public function setAggregate(bool $value) : void;

    /**
     * @return bool
     */
    public function getNetworkAnalysisEnabled() : bool;

    /**
     * @param bool $value
     */
    public function setNetworkAnalysisEnabled(bool $value) : void;

    /**
     * @return EquipmentContainer|null
     */
    public function getEquipmentContainer() : ?EquipmentContainer;

    /**
     * @param EquipmentContainer $container
     */
    public function setEquipmentContainer(EquipmentContainer $container) : void;

    /**
     *
     */
    public function removeEquipmentContainer(): void;

    /**
     * @return array|null
     */public function getAdditionalEquipmentContainer() : ?array;

    /**
     * @param EquipmentContainer $container
     */public function addAdditionalEquipmentContainer(EquipmentContainer $container) : void;

    /**
     * @param EquipmentContainer $container
     */public function removeAdditionalEquipmentContainer(EquipmentContainer $container) : void;

}
