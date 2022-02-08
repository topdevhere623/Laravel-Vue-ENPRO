<?php


namespace App\Contracts\CIM\Currenttransformerinfo;

use App\Contracts\CIM\AssetInfo\AssetInfoInterface;
use App\Models\CurrentFlow;
use App\Models\Frequency;
use App\Models\GostClimaticModPlacementKind;
use App\Models\Ratio;
use App\Models\Voltage;

interface CurrenttransformerinfoInterface extends AssetInfoInterface
{
    public function getRatedVoltage(): ?Voltage;
    public function setRatedVoltage(Voltage $ratedVoltage): void;
    public function getEnproMaxVoltage(): ?Voltage;
    public function setEnproMaxVoltage(Voltage $enproMaxVoltage): void;
    public function getRatedFrequency() : ?Frequency;
    public function setRatedFrequency(Frequency $ratedFrequency) : void;
    public function getNominalRatio() : ? Ratio;
    public function setNominalRatio(Ratio $ratio) : void;
    public function getRatedCurrent() : ?CurrentFlow;
    public function setRatedCurrent(CurrentFlow $ratedCurrent) : void;
    public function getEnproClimaticModPlacement() : ?GostClimaticModPlacementKind;
    public function setEnproClimaticModPlacement(GostClimaticModPlacementKind $enproClimaticModPlacement) : void;
}
