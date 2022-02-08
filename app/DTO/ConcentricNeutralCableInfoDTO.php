<?php


namespace App\DTO;

use App\Models\ConcentricNeutralCableInfo;

use App\DTO\CableInfoDTO;


/**
 * Class ConcentricNeutralCableInfoDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property integer $neutralStrandCount
 * @property string $name

 * @property AllDataTypeDTO $diameterOverNeutral
 * @property AllDataTypeDTO $neutralStrandRDC20
 * @property CableInfoDTO $CableInfo

 *
 */
class ConcentricNeutralCableInfoDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\ConcentricNeutralCableInfo $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->neutralStrandCount = $model->neutralStrandCount;
        $IdentifiedObject = $model->getIdentifiedObject();
        $this->name = $IdentifiedObject->name;

        return $this;
    }

    /**
     * @param \App\Models\ConcentricNeutralCableInfo $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->diameterOverNeutral = (! empty($model->Length)) ? AllDataTypeDTO::instance()->load($model->Length) : null;
        $this->neutralStrandRDC20 = (! empty($model->ResistancePerLength)) ? AllDataTypeDTO::instance()->load($model->ResistancePerLength) : null;
        $this->CableInfo = (! empty($model->CableInfo)) ? CableInfoDTO::instance()->load($model->CableInfo) : null;

        return $this;
    }
}
