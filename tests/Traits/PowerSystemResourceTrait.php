<?php


namespace Tests\Traits;


use App\Contracts\CIM\Wires\PowerSystemResourceInterface;
use App\Models\PowerSystemResource;
use App\Models\PSRType;

trait PowerSystemResourceTrait
{
    use IdentifiedObjectTrait;

    public function powerSystemResource(PowerSystemResourceInterface $powerSystemResource)
    {
        $this->IdentifiedObject($powerSystemResource);
        $psrType = new PSRType();
        $psrType->setDescription('Test description for psr type');
        $powerSystemResource->setPSRType($psrType);
        $this->assertInstanceOf(PSRType::class, $powerSystemResource->getPSRType());
        $powerSystemResource->save();
        $id = $powerSystemResource->id;
        $powerSystemResource = $this->getSaved($id);
        $this->assertInstanceOf(PSRType::class, $powerSystemResource->getPSRType(), 'check saved PSRType');
        $this->assertEquals('Test description for psr type', $powerSystemResource->getPSRType()->getDescription());

    }

    public function getSaved() : PowerSystemResourceInterface
    {
        return PowerSystemResource::find(1);
    }
}
