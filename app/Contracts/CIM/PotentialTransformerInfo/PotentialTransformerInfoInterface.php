<?php


namespace App\Contracts\CIM\PotentialTransformerInfo;


use App\Contracts\CIM\AssetInfo\AssetInfoInterface;
use App\Models\AssetInfo;
use App\Models\Frequency;
use App\Models\GostClimaticModPlacementKind;
use App\Models\PotentialTransformerKind;
use App\Models\Voltage;

interface PotentialTransformerInfoInterface extends AssetInfoInterface
{
    public function getAssetInfo() : ? AssetInfo;
    public function setAssetInfo(AssetInfo $AssetInfo) : void;
    public function getRatedFrequency() : ?Frequency;
    public function setRatedFrequency(Frequency $ratedFrequency) : void;
    public function getEnproClimaticModPlacement() : ?GostClimaticModPlacementKind;
    public function setEnproClimaticModPlacement(GostClimaticModPlacementKind $enproClimaticModPlacement) : void;
    public function getEnproConstructionKind() : ?PotentialTransformerKind;
    public function getEnproSecondary1Voltage(): ?Voltage;
    public function setEnproSecondary1Voltage(Voltage $voltage): void;
    public function getEnproSecondary2Voltage(): ?Voltage;
    public function setEnproSecondary2Voltage(Voltage $voltage): void;
    public function getRatedVoltage(): ?Voltage;
    public function setRatedVoltage(Voltage $voltage): void;
}
