<?php


namespace App\DTO;

use App\Models\WireInfo;
use App\Models\WirePhaseInfo;

use App\DTO\WireInsulationKindDTO;
use App\DTO\WireMaterialKindDTO;
use App\DTO\CurrentFlowDTO;
use App\DTO\AssetInfoDTO;


/**
 * Class WireInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property AssetInfoDTO $AssetInfo
 * @property WirePhaseInfoDTO[] $WirePhaseInfo
 * @property boolean|null $listInsulated
 * @property string|null $listMaterial
 * @property float|null $listNominalVoltage
 * @property int|null $phasesCount
 *
 */
class WireAssemblyInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\WireAssemblyInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->AssetInfo = empty($model->AssetInfo) ? null : AssetInfoDTO::instance()->loadCatalogAssetType($model->AssetInfo);
        $this->listInsulated = null;
        $this->listMaterial = null;
        $this->listNominalVoltage = null;
        $this->phasesCount = $model->WirePhaseInfo()->get()->count();
        $this->WirePhaseInfo = $model->WirePhaseInfo()
            ->get()
            ->map(function(WirePhaseInfo $q) {
                if (empty($q->phase_info_id) || ($q->phaseInfo->literal == 'A')) {
                    $this->listInsulated = $q->WireInfo->insulated;
                    $this->listMaterial = empty($q->WireInfo->material) ? null : $q->WireInfo->material->ru_value;
                    $this->listNominalVoltage = empty($q->WireInfo->nominalVoltage) ? null :  $q->WireInfo->nominalVoltage->value;
                }
                return WirePhaseInfoDTO::instance()->load($q);
            });

        return $this;
    }


}
