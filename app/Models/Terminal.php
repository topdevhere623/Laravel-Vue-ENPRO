<?php

namespace App\Models;

use App\Contracts\CIM\Wires\TerminalInterface;
use App\Traits\ACDCTerminalTrait;
use App\Traits\IdentifiedObjectParentTrait;
use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Busbarsection;
use App\Models\Connectivitycode;
use App\Models\Identifiedobject;

// модель
class Terminal extends Model implements TerminalInterface
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    use ACDCTerminalTrait;

    // управляемая таблица
    protected $table = "terminal";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = ['deleted_at', 'updated_at', 'created_at'];

    /**
     * @var ACDCTerminal
     */
    public $acdcterminal = null;

    /**
     * @var PhaseCode
     */
    public $phases = null;
    /**
     * @var ConnectivityNode
     */
    public $connectivityNode = null;


    // мои атрибуты модели
    const title1 = "Привод переключателя";
    const title2 = "Приводы переключателей";

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Terminal  $model) {
            if($model->getACDCTerminal()) {
                $model->getACDCTerminal()->save();
                $model->acdcterminal()->associate($model->getACDCTerminal());
            };
            if($model->getPhases()) {
                $model->getPhases()->save();
                $model->phases()->associate($model->getPhases());
            };
            if($model->getConnectivityNode()) {
                $model->getConnectivityNode()->save();
                $model->connectivityNode()->associate($model->getConnectivityNode());
            } else {
                $model->connectivityNode()->dissociate();
            }

        });

        static::deleted(function (Terminal $model) {
            $model->getACDCTerminal()->delete();
        });

        static::saving(function (Terminal $model) {
            $model->getACDCTerminal()->save();
            if($model->getPhases()) {
                $model->getPhases()->save();
                $model->phases()->associate($model->getPhases());
            }
            if($model->getConnectivityNode()) {
                $model->getConnectivityNode()->save();
                $model->connectivityNode()->associate($model->getConnectivityNode());
            } else {
                $model->connectivityNode()->dissociate();
            }
        });
    }


    public function phases() : BelongsTo
    {
        return $this->belongsTo(PhaseCode::class);
    }

    public function getPhases(): ?PhaseCode
    {
        if($this->phases) return $this->phases;
        $this->phases = $this->phases()->get()->get(0);
        return $this->phases;
    }

    public function setPhases(PhaseCode $phaseCode): void
    {
        $this->phases = $phaseCode;
    }


    public function getConductingEquipment(): ?ConductingEquipment
    {
        return $this->belongsTo(ConductingEquipment::class, 'conductingequipment_id')->get()->get(0);
    }

    public function connectivityNode() : BelongsTo
    {
        return $this->belongsTo(ConnectivityNode::class, 'connectivitynode_id');
    }

    public function getConnectivityNode(): ?ConnectivityNode
    {
        if($this->connectivityNode) return $this->connectivityNode;
        if($this->connectivityNode()->get()->get(0)) {
            $this->connectivityNode = $this->connectivityNode()->get()->get(0);
        }
        return $this->connectivityNode;
    }

    public function setConnectivityNode(ConnectivityNode $connectivityNode): void
    {
        $this->connectivityNode = $connectivityNode;
    }

    public function removeConnectivityNode() : void
    {
        //$this->connectivitynode_id  = 0;
        $this->connectivityNode = null;
    }


    public function getACDCTerminal() : ACDCTerminal
    {
        if($this->acdcterminal) return $this->acdcterminal;
        $this->acdcterminal = $this->acdcterminal()->get()->get(0);
        if(!$this->acdcterminal) $this->acdcterminal  = new ACDCTerminal();
        return $this->acdcterminal;
    }

    public function acdcterminal() : BelongsTo
    {
        return $this->belongsTo(ACDCTerminal::class, 'a_c_d_c_terminals_id');
    }

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/terminal/' . $this->id;
    }

    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }



    // с секциями шин
    public function busbarsection()
    {
        return $this->belongsTo(Busbarsection::class, 'busbarsection_id')->withDefault();
    }

    // с connectivitycode
    public function connectivitycode()
    {
        return $this->belongsTo(Connectivitycode::class, 'connectivitycode_id')->withDefault();
    }

    public function getConnectedTerminals()
    {
        if($this->connectivitycode()->count() == 0 ) return [];
        $connectedTerminals = [];
        $connected = Terminal::where('connectivitycode_id', $this->connectivitycode()->get()->get(0)->id);
        $connectedTower = Tower::where('connectivitycode_id', $this->connectivitycode()->get()->get(0)->id);
        foreach ($connected->get() as $connectedTerminal) {
            if($connectedTerminal != $this) $connectedTerminals[] = $connectedTerminal;
        }
        foreach ($connectedTower->get() as $connectedTerminal) {
            if($connectedTerminal != $this) $connectedTerminals[] = $connectedTerminal;
        }
        return $connectedTerminals;
    }

    public function getAuxiliaryEquipment(): ?AuxiliaryEquipment
    {
        return $this->hasOne(AuxiliaryEquipment::class)->first();
    }

}
