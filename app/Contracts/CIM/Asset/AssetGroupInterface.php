<?php
namespace App\Contracts\CIM\Asset;

use App\Models\Asset;
use App\Models\Document;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface AssetGroupInterface extends AssetInterface
 * @package App\Contracts\CIM\Asset
 */
interface AssetGroupInterface extends DocumentInterface
{

    /**
     * @return array
     */
    public function getAssets() : array;

    /**
     * @param Asset $Assets
     */
    public function addAssets(Asset $Assets) : void;

    /**
     * @param Asset $Assets
     */
    public function removeAssets(Asset $Assets) : void;

    /**
     * @return Document
     */
    public function getDocument() : ? Document;

    /**
     * @param Document $Document
     */
    public function setDocument(Document $Document) : void;


}
