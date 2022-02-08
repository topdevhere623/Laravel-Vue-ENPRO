<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Gost;
use App\Models\Manufacturer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Общий список
     */
    public function testAllAsset()
    {
        $response = $this->get('/api/asset');
        $response->assertStatus(200);
    }

    private function getData()
    {
        $data = [
            "gost_id" => Gost::query()->inRandomOrder()->first()->id,
            "manufacturer_id" => Manufacturer::query()->inRandomOrder()->first()->id,
            "keylink" => "",
            "initiallossoflife" => 0,
            "corporatecode" => "",
            "installationdate" => "",
            "manufactureddate" => "",
            "serialnumber" => "",
            "inventorynumber" => "",
            "initialcondition" => 0,
            "purchasedate" => "",
            "purchaseprice" => 100.1,
            "receiveddate" => "",
            "retireddate" => "",
            "orgmanagerkey" => "",
            "fgc_parentkey" => "",
            "orgassetownerkey" => "",
            "type" => "",
            "assetinfokey" => "",
            "manufactureddt" => "",
            "assetcol" => "",
            "deliverydate" => "",
            "ownereqassetid" => 0,
            "comment" => "",
            "critical" => 0,
            "cadastralnumber" => "",
            "manufacturer" => "",
            "warehouse" => 0,
            "inventorynumbermp" => "",
            "inventorynumberbp" => "" ,
            "powersystemresources_id" => null
        ];
        return $data;
    }

    /**
     * Создание записи
     */
    public function testCreateAsset()
    {
        $data = $this->getData();
        $response = $this->post('/api/asset', $data);
        $response->assertStatus(200);
    }

    /**
     * Отоюражение 1 записи
     */
    public function testShowIdAsset()
    {
        $id = Asset::query()->inRandomOrder()->first()->id;
        $response = $this->get("/api/asset/$id");
        $response->assertStatus(200);
    }

    /**
     * Редактирование
     */
    public function testUpdateAsset()
    {
        $id = Asset::query()->inRandomOrder()->first()->id;
        $data = $this->getData();
        $response = $this->put("/api/asset/$id", $data);
        $response->assertStatus(200);
    }

    /**
     * Удаление
     */
    public function testDeleteAsset()
    {
        $id = Asset::query()->inRandomOrder()->first()->id;
        $response = $this->delete("/api/asset/$id");
        $response->assertStatus(200);
    }

    /**
     * Запрос на получение справочников при редактировании записи
     */
    public function testEditAsset()
    {
        $response = $this->get("api/asset/0/edit");
        $response->assertStatus(200);
    }
}
