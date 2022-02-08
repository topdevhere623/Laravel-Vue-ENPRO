<?php


namespace App\Traits;


use App\Models\ConnectivityNodeContainer;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait EquipmentContainerTrait
{
    use ConnectivityNodeContainerTrait;

    public function connectivityNodeContainer(): BelongsTo
    {
        return $this->getEquipmentContainer()->belongsTo(ConnectivityNodeContainer::class);
    }

    public function getConnectivityNodeContainer() : ConnectivityNodeContainer
    {
        if($this->getEquipmentContainer()->cnc) return $this->getEquipmentContainer()->cnc;
        if($this->connectivityNodeContainer()->get()->get(0)) {
            $this->getEquipmentContainer()->cnc = $this->connectivityNodeContainer()->get()->get(0);
        } else {
            $this->getEquipmentContainer()->cnc = new ConnectivityNodeContainer();
        }
        return $this->getEquipmentContainer()->cnc;
    }
    public function getEquipments() : array
    {
        $equipments = $this->getEquipmentContainer()->hasMany(Equipment::class,    'equipment_containers_id')->get();
        $returnEquipments= [];
        foreach ($equipments as $equipment) {
            if(!in_array($equipment,$returnEquipments)) $returnEquipments[] = $equipment;
        }
        return $returnEquipments;
    }


}
