<?php
namespace App\Contracts\CIM\AssetInfo;

use App\Models\ProductAssetModel;
use App\Models\Identifiedobject;
use App\Contracts\CIM\Wires\IdentifiedObjectInterface;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Interface AssetInfoInterface extends IdentifiedObjectInterface
 * @package App\Contracts\CIM\Work
 */
interface AssetInfoInterface extends IdentifiedObjectInterface
{

    /**
     * @return ProductAssetModel|null
     */
    public function getProductAssetModel() : ? ProductAssetModel;

    /**
     * @param ProductAssetModel $ProductAssetModel
     */
    public function setProductAssetModel(ProductAssetModel $ProductAssetModel) : void;

    /**
     * @return Identifiedobject|null
     */
    public function getIdentifiedObject() : ? Identifiedobject;

    /**
     * @param Identifiedobject $IdentifiedObject
     */
    public function setIdentifiedObject(Identifiedobject $IdentifiedObject) : void;



}
