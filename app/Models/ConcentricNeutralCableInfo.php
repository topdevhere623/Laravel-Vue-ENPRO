<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use App\Models\BaseModel as BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ConcentricNeutralCableInfoTrait;
use App\Contracts\CIM\AssetInfo\ConcentricNeutralCableInfoInterface;

/**
 * Class ConcentricNeutralCableInfo
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 *
 * @property integer $id
 * @property \App\Models\Length $diameterOverNeutral
 * @property \App\Models\Length $neutralStrandGmr
 * @property \App\Models\Length $neutralStrandRadius
 * @property \App\Models\ResistancePerLength $neutralStrandRDC20
 * @property \App\Models\CableInfo $CableInfo
 * @property integer $neutralStrandCount

 */
class ConcentricNeutralCableInfo extends BaseModel implements ConcentricNeutralCableInfoInterface
{
    // использование мягкого удаления
    use SoftDeletes;
    use ConcentricNeutralCableInfoTrait;
    public $parentCableInfo = null;

    // управляемая таблица
    public $table = 'concentric_neutral_cable_info';

    // список полей, разрешенных на редактирование
    public $fillable = [
      'diameter_over_neutral_id',
      'neutral_strand_gmr_id',
      'neutral_strand_radius_id',
      'neutral_strand_r_d_c20_id',
      'cable_info_id',
      'neutralStrandCount',

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
        'diameter_over_neutral_id' => 'integer',
        'neutral_strand_gmr_id' => 'integer',
        'neutral_strand_radius_id' => 'integer',
        'neutral_strand_r_d_c20_id' => 'integer',
        'cable_info_id' => 'integer',
        'neutralStrandCount' => 'integer',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diameterOverNeutral()
    {
        return $this->belongsTo(\App\Models\Length::class, 'diameter_over_neutral_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function neutralStrandGmr()
    {
        return $this->belongsTo(\App\Models\Length::class, 'neutral_strand_gmr_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function neutralStrandRadius()
    {
        return $this->belongsTo(\App\Models\Length::class, 'neutral_strand_radius_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function neutralStrandRDC20()
    {
        return $this->belongsTo(\App\Models\ResistancePerLength::class, 'neutral_strand_r_d_c20_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CableInfo()
    {
        return $this->belongsTo(\App\Models\CableInfo::class, 'cable_info_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (ConcentricNeutralCableInfo $model) {
            $CableInfo = $model->getCableInfo();
            if (! empty($CableInfo)) {
                $CableInfo->save();
                $model->CableInfo()->associate($CableInfo);
            };

            
            
        });

    }


    public function getConcentricNeutralCableInfo() : ConcentricNeutralCableInfo
    {
        return $this;
    }
}
