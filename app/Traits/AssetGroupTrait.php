<?php
namespace App\Traits;

use App\Models\Asset;
use App\Models\Document;
use App\Traits\AssetTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait AssetContainerTrait
 * @package App\Models\Traits
 */
trait AssetGroupTrait
{
    use DocumentTrait;
    public $Assets;



    /**
     * @return array
     */
    public function getAssets() : array
    {
        if($this->getAssetGroup()->Assets) return $this->getAssetGroup()->Assets;
        $this->getAssetGroup()->Assets = [];
        foreach($this->getAssetGroup()->Assets()->get() as $relationModel) {
            $this->getAssetGroup()->Assets[] = $relationModel;
        };
        return $this->getAssetGroup()->Assets;
    }

    /**
     * @param Asset $Assets
     */
    public function addAssets(Asset $Assets) : void
    {
        $this->getAssetGroup()->Assets = $this->getAssets();
        if(!in_array($Assets, $this->getAssetGroup()->Assets)) {
            array_push($this->getAssetGroup()->Assets, $Assets);
        }
    }

    /**
     * @param Asset $Assets
     */
    public function removeAssets(Asset $Assets) : void
    {
        array_splice($this->getAssetGroup()->Assets, array_search($Assets, $this->getAssetGroup()->Assets ), 1);
        if($Assets->id) {
            $Assets->delete();
        }
    }


    /**
     * @return Document
     */
    public function getDocument() : ? Document
    {
        if($this->getAssetGroup()->parentDocument) return $this->getAssetGroup()->parentDocument;
        $this->getAssetGroup()->parentDocument = $this->Document;
        if(!$this->getAssetGroup()->parentDocument) $this->getAssetGroup()->parentDocument = new Document();
        return $this->getAssetGroup()->parentDocument;
    }

    /**
     * @param Document $Document
     */
    public function setDocument(Document $Document) : void
    {
        $this->getAssetGroup()->parentDocument = $Document;
    }


}
