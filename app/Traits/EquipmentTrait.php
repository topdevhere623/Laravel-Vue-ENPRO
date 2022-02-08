<?php


namespace App\Traits;


use App\Models\EquipmentContainer;
use App\Models\PowerSystemResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait EquipmentTrait
 * @package App\Traits
 */
trait EquipmentTrait
{
    use PowerSystemResourceTrait;

    /**
     * @return PowerSystemResource
     */
    public function getPowerSystemResource() : PowerSystemResource
    {
        if($this->getEquipment()->psr) return $this->getEquipment()->psr;
        if($this->powersystemresource()->get()->get(0)) {
            $this->getEquipment()->psr =  $this->powersystemresource()->get()->get(0);
        } else {
            $this->getEquipment()->psr = new PowerSystemResource();
        }
        return $this->getEquipment()->psr;
    }


    /**
     * @return BelongsTo
     */
    public function powersystemresource() : BelongsTo
    {
        return $this->getEquipment()->belongsTo(PowerSystemResource::class,'power_system_resources_id');
    }

    /**
     * @return bool
     */
    public function getAggregate() : bool
    {
        return $this->getEquipment()->aggregate ? true : false;
    }

    /**
     * @param bool $value
     */
    public function setAggregate(bool $value) : void
    {
        $this->getEquipment()->aggregate = $value;
    }

    /**
     * @return bool
     */
    public function getInService() : bool
    {
        return $this->getEquipment()->inService ? true : false;
    }

    /**
     * @param bool $value
     */
    public function setInService(bool $value) : void
    {
        $this->getEquipment()->inService = $value;
    }

    /**
     * @return bool
     */
    public function getNetworkAnalysisEnabled() : bool
    {
        return $this->getEquipment()->networkAnalysisEnabled ? true : false;
    }

    /**
     * @param bool $value
     */
    public function setNetworkAnalysisEnabled(bool  $value) : void
    {
        $this->getEquipment()->networkAnalysisEnabled = $value;
    }

    /**
     * @return bool
     */
    public function getNormallyInService() : bool
    {
        return $this->getEquipment()->normallyInService ? true : false;
    }

    /**
     * @param bool $value
     */
    public function setNormallyInService(bool $value) : void
    {
        $this->getEquipment()->normallyInService = $value;
    }

    /**
     * @return BelongsTo
     */
    public function equipmentContainer() : BelongsTo
    {
        return $this->getEquipment()->belongsTo(EquipmentContainer::class, 'equipment_containers_id');
    }

    /**
     * @return EquipmentContainer|null
     */
    public function getEquipmentContainer(): ?EquipmentContainer
    {
        if($this->getEquipment()->equipmentContainer) return $this->getEquipment()->equipmentContainer;
        if($this->equipmentContainer()->get()->get(0)) {
            $this->getEquipment()->equipmentContainer = $this->equipmentContainer()->get()->get(0);
        }
        return $this->getEquipment()->equipmentContainer;
    }

    /**
     * @param EquipmentContainer $container
     */
    public function setEquipmentContainer(EquipmentContainer $container): void
    {
        $this->getEquipment()->equipmentContainer = $container;
    }

    /**
     *
     */
    public function removeEquipmentContainer(): void
    {
        $this->getEquipment()->equipmentContainer = null;
        if($this->getEquipment()->equipmentContainer &&
            $this->getEquipment()->equipmentContainer->id) $this->equipmentContainer()->dissociate();
    }


    /**
     * @return BelongsToMany
     */
    public function additionalEquipmentContainer() :BelongsToMany
    {
        return $this->getEquipment()->belongsToMany(EquipmentContainer::class, null,   'equipment_id', 'equipment_containers_id');
    }

    /**
     * @return array|null
     */
    public function getAdditionalEquipmentContainer(): ?array
    {
        if($this->getEquipment()->addEquipmentContainers) return $this->getEquipment()->addEquipmentContainers;
        if($this->additionalEquipmentContainer()->get()->count()) {
            $this->getEquipment()->addEquipmentContainers = [];
            foreach ($this->additionalEquipmentContainer()->get() as $container) {
                $this->getEquipment()->addEquipmentContainers[] = $container;
            };
        };
        return $this->getEquipment()->addEquipmentContainers;
    }

    /**
     * @param EquipmentContainer $container
     */
    public function addAdditionalEquipmentContainer(EquipmentContainer $container): void
    {
        if(!in_array($container, $this->getEquipment()->addEquipmentContainers, true)) {
            array_push($this->getEquipment()->addEquipmentContainers, $container);
        }
    }

    /**
     * @param EquipmentContainer $container
     */
    public function removeAdditionalEquipmentContainer(EquipmentContainer $container): void
    {
        if(in_array($container, $this->getEquipment()->addEquipmentContainers)) {
            array_splice($this->getEquipment()->addEquipmentContainers, array_search($container, $this->getEquipment()->addEquipmentContainers), 1);
        };
    }
}
