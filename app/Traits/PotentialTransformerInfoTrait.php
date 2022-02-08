<?php


namespace App\Traits;


use App\Models\AssetInfo;
use App\Models\Frequency;
use App\Models\GostClimaticModPlacementKind;
use App\Models\PotentialTransformerKind;
use App\Models\Voltage;

trait PotentialTransformerInfoTrait
{
    use AssetInfoTrait;

    public function getAssetInfo() : ? AssetInfo
    {
        if($this->getPotentialTransformerInfo()->parentAssetInfo) return $this->getPotentialTransformerInfo()->parentAssetInfo;
        $this->getPotentialTransformerInfo()->parentAssetInfo = $this->AssetInfo;
        if(!$this->getPotentialTransformerInfo()->parentAssetInfo) $this->getPotentialTransformerInfo()->parentAssetInfo = new AssetInfo();
        return $this->getPotentialTransformerInfo()->parentAssetInfo;
    }

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void
    {
        $this->getPotentialTransformerInfo()->parentAssetInfo = $AssetInfo;
    }

    /**
     * @return Frequency|null
     */
    public function getRatedFrequency() : ?Frequency
    {
        return $this->ratedFrequency()->first();
    }

    /**
     * @param Frequency $ratedFrequency
     */
    public function setRatedFrequency(Frequency $ratedFrequency) : void
    {
        $this->ratedFrequency()->associate($ratedFrequency);
    }

    /**
     * @return GostClimaticModPlacementKind|null
     */
    public function getEnproClimaticModPlacement() : ?GostClimaticModPlacementKind
    {
        return $this->enproClimaticModPlacement()->first();
    }

    /**
     * @param GostClimaticModPlacementKind $enproClimaticModPlacement
     */
    public function setEnproClimaticModPlacement(GostClimaticModPlacementKind $enproClimaticModPlacement) : void
    {
        $this->enproClimaticModPlacement()->associate($enproClimaticModPlacement);
    }

    public function getEnproConstructionKind() : ?PotentialTransformerKind
    {
        return $this->enproConstructionKind()->first();
    }

    /**
     * @return Voltage|null
     */
    public function getEnproSecondary1Voltage(): ?Voltage
    {
        return $this->enproSecondary1Voltage()->first();
    }

    /**
     * @param Voltage $voltage
     */
    public function setEnproSecondary1Voltage(Voltage $voltage): void
    {
        $this->enproSecondary1Voltage()->associate($voltage);
    }

    /**
     * @return Voltage|null
     */
    public function getEnproSecondary2Voltage(): ?Voltage
    {
        return $this->enproSecondary2Voltage()->first();
    }

    /**
     * @param Voltage $voltage
     */
    public function setEnproSecondary2Voltage(Voltage $voltage): void
    {
        $this->enproSecondary2Voltage()->associate($voltage);
    }

    /**
     * @return Voltage|null
     */
    public function getRatedVoltage(): ?Voltage
    {
        return $this->ratedVoltage()->first();
    }

    /**
     * @param Voltage $voltage
     */
    public function setRatedVoltage(Voltage $voltage): void
    {
        $this->ratedVoltage()->associate($voltage);
    }


}
