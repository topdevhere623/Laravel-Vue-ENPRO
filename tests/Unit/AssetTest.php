<?php


namespace Tests\Unit;


use App\Contracts\CIM\Asset\AssetInterface;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;
use App\Models\Asset;
use App\Models\Identifiedobject;
use App\Models\Name;
use App\Models\PowerSystemResource;
use App\Models\PowerTransformerEnd;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AssetTrait;
use Tests\Traits\IdentifiedObjectTrait;

class AssetTest extends TestCase
{

    use AssetTrait;

    public function testCreateAsset()
    {
        $asset = new Asset();
        $this->asset($asset);
        $fakerAsset = factory(Asset::class, 1)->make()->get(0);
        $this->asset($fakerAsset);
        $this->assertInstanceOf(Asset::class, $asset);
    }

    public function getSaved(int $id) : AssetInterface {
        return Asset::find($id);
    }

}
