<?php


namespace App\Traits;


use App\Models\PowerSystemResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ConnectivityNodeContainerTrait
{
    use PowerSystemResourceTrait;

    public function getPowerSystemResource() : PowerSystemResource
    {
        if($this->getConnectivityNodeContainer()->psr) return $this->getConnectivityNodeContainer()->psr;
        if($this->powersystemresource()->get()->get(0) ) {
            $this->getConnectivityNodeContainer()->psr = $this->powersystemresource()->get()->get(0);
        } else {
            $this->getConnectivityNodeContainer()->psr = new PowerSystemResource();
        }
        return $this->getConnectivityNodeContainer()->psr;
    }



    public function powersystemresource() : BelongsTo
    {
        return $this->getConnectivityNodeContainer()->belongsTo(PowerSystemResource::class,'power_system_resources_id');
    }
}
