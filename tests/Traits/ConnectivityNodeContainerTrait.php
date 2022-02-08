<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\ConnectivityNodeContainerInterface;

trait ConnectivityNodeContainerTrait
{
    use PowerSystemResourceTrait;
    public function connectivityNodeContainer(ConnectivityNodeContainerInterface $container)
    {
        $this->powerSystemResource($container);
    }
}
