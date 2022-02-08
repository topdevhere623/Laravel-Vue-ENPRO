<?php


namespace App\Traits;


use App\Models\AssetInfo;
use App\Models\CurrentFlow;
use App\Models\Frequency;
use App\Models\GostClimaticModPlacementKind;
use App\Models\Voltage;
use App\Models\Ratio;

trait CurrenttransformerinfoTrait
{
    use AssetInfoTrait;
    /**
     * @return Voltage|null
     */
    public function getRatedVoltage(): ?Voltage
    {
        return $this->ratedVoltage()->first();
    }

    /**
     * @param Voltage $ratedVoltage
     */
    public function setRatedVoltage(Voltage $ratedVoltage): void
    {
        $this->ratedVoltage()->associate($ratedVoltage);
    }

    /**
     * @return Voltage|null
     */
    public function getEnproMaxVoltage(): ?Voltage
    {
        return $this->enproMaxVoltage()->first();
    }

    /**
     * @param Voltage $enproMaxVoltage
     */
    public function setEnproMaxVoltage(Voltage $enproMaxVoltage): void
    {
        $this->enproMaxVoltage()->associate($enproMaxVoltage);
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
     * @return Ratio|null
     */
    public function getNominalRatio() : ? Ratio
    {
        return $this->nominalRatio()->first();
    }

    /**
     * @param Ratio $ratio
     */
    public function setNominalRatio(Ratio $ratio) : void
    {
        $this->nominalRatio()->associate($ratio);
    }

    /**
     * @return CurrentFlow|null
     */
    public function getRatedCurrent() : ?CurrentFlow
    {
        return $this->ratedCurrent()->first();
    }

    /**
     * @param CurrentFlow $ratedCurrent
     */
    public function setRatedCurrent(CurrentFlow $ratedCurrent) : void
    {
        $this->ratedCurrent()->associate($ratedCurrent);
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

    /**
     * @return AssetInfo
     */
    public function getAssetInfo() : ? AssetInfo
    {
        if($this->getCurrentTransformerInfo()->parentAssetInfo) return $this->getCurrentTransformerInfo()->parentAssetInfo;
        $this->getCurrentTransformerInfo()->parentAssetInfo = $this->AssetInfo;
        if(!$this->getCurrentTransformerInfo()->parentAssetInfo) $this->getCurrentTransformerInfo()->parentAssetInfo = new AssetInfo();
        return $this->getCurrentTransformerInfo()->parentAssetInfo;
    }

    /**
     * @param AssetInfo $AssetInfo
     */
    public function setAssetInfo(AssetInfo $AssetInfo) : void
    {
        $this->getCurrentTransformerInfo()->parentAssetInfo = $AssetInfo;
    }

}
