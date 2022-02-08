<?php
namespace App\Traits;

use App\Models\Document;
use App\Models\EnproDefect;
use App\Traits\DocumentTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait ProcedureTrait
 * @package App\Models\Traits
 */
trait ProcedureTrait
{
    use DocumentTrait;

    /**
     * @return string
     */
    public function getSequenceNumber() : string
    {
        return $this->getProcedure()->sequenceNumber;
    }

    /**
     * @param string  $sequenceNumber
     */
    public function setSequenceNumber(string $sequenceNumber) : void
    {
        $this->getProcedure()->sequenceNumber = $sequenceNumber;
    }
    /**
     * @return string
     */
    public function getStatus() : string
    {
        return $this->getProcedure()->status;
    }

    /**
     * @param string  $status
     */
    public function setStatus(string $status) : void
    {
        $this->getProcedure()->status = $status;
    }

    /**
     * @return Document
     */
    public function getDocument() : ? Document
    {
        return $this->getProcedure()->Document;
    }

    /**
     * @param Document $Document
     */
    public function setDocument(Document $Document) : void
    {
        $this->getProcedure()->Document = $Document;
    }

    /**
     * @return array
     */
    public function getMeasurements() : array
    {
        return $this->getProcedure()->measurements;
    }

    /**
     * @param EnproDefect $measurements
     */
    public function addMeasurements(EnproDefect $measurements) : void
    {
        if(!in_array($measurements, $this->getProcedure()->measurements)) {
            array_push($this->getProcedure()->measurements, $measurements);
        }
    }

    /**
     * @param EnproDefect $measurements
     */
    public function removeMeasurements(EnproDefect $measurements) : void
    {
        array_splice($this->getProcedure()->measurements, array_search($measurements, $this->getProcedure()->measurements ), 1);
        if($measurements->id) {
            $measurements->delete();
        }
    }


}
