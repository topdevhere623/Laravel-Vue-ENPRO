<?php
namespace App\Traits;

use App\Models\ProductAssetModel;
use App\Models\Identifiedobject;
use App\Traits\IdentifiedObjectTrait;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait AssetInfoTrait
 * @package App\Models\Traits
 */
trait AssetInfoTrait
{
    use IdentifiedObjectTrait;



    /**
     * @return ProductAssetModel|null
     */
    public function getProductAssetModel() : ?ProductAssetModel
    {
        return $this->ProductAssetModel()->first();
    }

    /**
     * @param ProductAssetModel $ProductAssetModel
     */
    public function setProductAssetModel(ProductAssetModel $ProductAssetModel) : void
    {
        $this->ProductAssetModel()->associate($ProductAssetModel);
    }
    /**
     * @return Identifiedobject
     */
    public function getIdentifiedObject() : ? Identifiedobject
    {
        if($this->getAssetInfo()->parentIdentifiedObject) return $this->getAssetInfo()->parentIdentifiedObject;
        $this->getAssetInfo()->parentIdentifiedObject = $this->IdentifiedObject;
        if(!$this->getAssetInfo()->parentIdentifiedObject) $this->getAssetInfo()->parentIdentifiedObject = new Identifiedobject();
        return $this->getAssetInfo()->parentIdentifiedObject;
    }

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void
    {
        $this->getAssetInfo()->parentIdentifiedObject = $IdentifiedObject;
    }


}
