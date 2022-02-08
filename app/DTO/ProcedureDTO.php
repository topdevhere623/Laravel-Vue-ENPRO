<?php


namespace App\DTO;

use App\Models\Procedure;

use App\DTO\DocumentDTO;
use App\DTO\EnproDefectDTO;


/**
 * Class ProcedureDTO
 * @package App\Models\DTO\PublicDTO
 * @property integer $id
 * @property string $sequenceNumber
 * @property string $status

 * @property DocumentDTO $Document
 * @property EnproDefectDTO[] $measurements

 *
 */
class ProcedureDTO extends AbstractPublicDTO
{
    /**
     * @param \App\Models\Procedure $model
     * @return $this
     * @throws \Exception
     */
    public function load($model)
    {
        $this->id = $model->id;
        $this->sequenceNumber = $model->sequenceNumber;
        $this->status = $model->status;
        $this->Document = (! empty($model->Document)) ? DocumentDTO::instance()->load($model->Document) : null;
        return $this;
    }

    /**
     * @param \App\Models\Procedure $model
     * @return $this
     * @throws \Exception
     */
    public function loadFull($model)
    {
        $this->load($model);
        $this->measurements = (! empty($model->measurements)) ? $model->measurements->map(function($q) { EnproDefectDTO::instance()->load($q->measurements);}) : null;
        return $this;
    }
}
