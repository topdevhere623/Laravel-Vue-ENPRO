<?php

namespace App\Models;

use App\Traits\IdentifiedObjectTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;
use App\Traits\ImageTrait;

// модели
use App\Models\Identifiedobject;
use App\Models\Substationinfo;
use App\Models\Substationfunction;
use App\Models\Asset;
use App\Models\Busbarsection;

// модель
class Substation extends Model
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;

    use ImageTrait;

    use IdentifiedObjectTrait;

    // управляемая таблица
    protected $table = "substation";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];
    // скрытые поля
    protected $hidden = [];

    // мои атрибуты модели
    const title1 = "ТП";
    const title2 = "ТП";

    // возвращает папку хранения изображения
    public function folderPath()
    {
        return 'uploads/models/substation/' . $this->id;
    }

    public function getIdentifiedObject()
    {
        $io = $this->identifiedObject ? $this->identifiedObject : $this->identifiedobject()->get()->get(0);
        return $io ? $io : new Identifiedobject();
    }


    // связи
    // с техническими данными IO
    public function identifiedobject()
    {
        return $this->belongsTo(Identifiedobject::class, 'identifiedobject_id')->withDefault();
    }

    // с общими данными Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id')->withDefault();
    }

    // с инфо подстанций
    public function substationinfo()
    {
        return $this->belongsTo(Substationinfo::class, 'substationinfo_id')->withDefault();
    }

    // с функциями подстанций
    public function substationfunction()
    {
        return $this->belongsTo(Substationfunction::class, 'substationfunction_id')->withDefault();
    }

    // с секциями шин
    public function busbarsections()
    {
        return $this->hasMany(Busbarsection::class, 'substation_id');
    }

    public function getScheme()
    {
        if ($this->scheme) return json_decode($this->scheme);
        else return '';
    }

    public function setScheme($scheme = '') : void
    {
        $this->scheme = json_encode($scheme);
    }

    /**
     * @return string
     */
    public function getXsde(): string
    {
        return $this->xsde;
    }

    /**
     * @param string $xsde
     */
    public function setXsde(string $xsde): void
    {
        $this->xsde = $xsde;
    }



    /**
     * @param int $voltageId
     * @return array
     */
    public function getTerminals($voltageId = 0)
    {
        $terminals = [];
        $busBarSections = $this->busbarsections()->get();
        foreach ($busBarSections as $busbarSection) {
            /** @var Busbarsection $busbarSection */
            if($busbarSection->identifiedobject()->get()->get(0)->voltage_id != $voltageId && $voltageId) continue;
            foreach ($busbarSection->terminal()->get() as $addTerminal) {
                $terminals[] = $addTerminal;
            }
        }
        return $terminals;
    }
}
