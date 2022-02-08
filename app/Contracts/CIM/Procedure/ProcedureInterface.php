<?php
namespace App\Contracts\CIM\Procedure;

use App\Models\Document;
use App\Models\EnproDefect;
use App\Contracts\CIM\Asset\DocumentInterface;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface ProcedureInterface extends DocumentInterface
 * @package App\Contracts\CIM\Work
 */
interface ProcedureInterface extends DocumentInterface
{
    /**
     * @return string
     */
    public function getSequenceNumber() : string;

    /**
     * @param string $sequenceNumber
     */
    public function setSequenceNumber(string $sequenceNumber) : void;
    /**
     * @return string
     */
    public function getStatus() : string;

    /**
     * @param string $status
     */
    public function setStatus(string $status) : void;

    /**
     * @return Document|null
     */
    public function getDocument() : ? Document;

    /**
     * @param Document $Document
     */
    public function setDocument(Document $Document) : void;


    /**
     * @return array
     */
    public function getMeasurements() : array;

    /**
     * @param EnproDefect $measurements
     */
    public function addMeasurements(EnproDefect $measurements) : void;

    /**
     * @param EnproDefect $measurements
     */
    public function removeMeasurements(EnproDefect $measurements) : void;


}
