<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ProductAssetModelTrait;
use App\Contracts\CIM\AssetInfo\ProductAssetModelInterface;

/**
 * Class ProductAssetModel
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\AssetModelUsageKind $usageKind
 * @property \App\Models\Manufacturer $Manufacturer
 * @property \App\Models\CorporateStandardKind $corporateStandardKind
 * @property \App\Models\Length $overallLength
 * @property \App\Models\Mass $weightTotal
 * @property \App\Models\IdentifiedObject $IdentifiedObject
 * @property \App\Models\Name[] $Names
 * @property string $catalogueNumber
 * @property string $drawingNumber
 * @property string $instructionManual
 * @property string $modelNumber
 * @property string $modelVersion
 * @property string $styleNumber

 */
class ProductAssetModel extends BaseModel implements ProductAssetModelInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use ProductAssetModelTrait;
    public $parentIdentifiedObject = null;

    // управляемая таблица
    public $table = 'product_asset_model';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'usage_kind_id',
      'manufacturer_id',
      'corporate_standard_kind_id',
      'overall_length_id',
      'weight_total_id',
      'identifiedobject_id',
      'catalogueNumber',
      'drawingNumber',
      'instructionManual',
      'modelNumber',
      'modelVersion',
      'styleNumber',

    ];

    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'usage_kind_id' => 'integer',
        'manufacturer_id' => 'integer',
        'corporate_standard_kind_id' => 'integer',
        'overall_length_id' => 'integer',
        'weight_total_id' => 'integer',
        'identifiedobject_id' => 'integer',
        'catalogueNumber' => 'string',
        'drawingNumber' => 'string',
        'instructionManual' => 'string',
        'modelNumber' => 'string',
        'modelVersion' => 'string',
        'styleNumber' => 'string',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usageKind()
    {
        return $this->belongsTo(\App\Models\AssetModelUsageKind::class, 'usage_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Manufacturer()
    {
        return $this->belongsTo(\App\Models\Manufacturer::class, 'manufacturer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function corporateStandardKind()
    {
        return $this->belongsTo(\App\Models\CorporateStandardKind::class, 'corporate_standard_kind_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function overallLength()
    {
        return $this->belongsTo(\App\Models\Length::class, 'overall_length_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weightTotal()
    {
        return $this->belongsTo(\App\Models\Mass::class, 'weight_total_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function IdentifiedObject()
    {
        return $this->belongsTo(\App\Models\IdentifiedObject::class, 'identifiedobject_id');
    }

    /**
     * @return array
     */
    public function Names(): array
    {
        return $this->IdentifiedObject->Names;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (ProductAssetModel $model) {
            $IdentifiedObject = $model->getIdentifiedObject();
            if (! empty($IdentifiedObject)) {
                $IdentifiedObject->save();
                $model->IdentifiedObject()->associate($IdentifiedObject);
            };

            
            
        });

    }


    public function getProductAssetModel() : ProductAssetModel
    {
        return $this;
    }
}
