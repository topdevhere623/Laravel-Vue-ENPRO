<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\EquipmentContainerInterface;

trait EquipmentContainerTrait
{
    use ConnectivityNodeContainerTrait;

    public function equipmentContainer(EquipmentContainerInterface $container)
    {
        $this->connectivityNodeContainer($container);
    }

}
