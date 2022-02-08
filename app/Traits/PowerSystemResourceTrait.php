<?php


namespace App\Traits;


use App\Models\Asset;
use App\Models\Identifiedobject;
use App\Models\PSRType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait PowerSystemResourceTrait
{
    use IdentifiedObjectTrait;



    /**
     * @return Identifiedobject
     */
    public function getIdentifiedObject()
    {
        if($this->getPowerSystemResource()->io) return $this->getPowerSystemResource()->io;
        $this->getPowerSystemResource()->io = $this->identifiedobjectBelong()->get()->get(0);
        if(!$this->getPowerSystemResource()->io) $this->getPowerSystemResource()->io = new Identifiedobject();
        return $this->getPowerSystemResource()->io;
    }

    /**
     * @return BelongsTo
     */
    public function identifiedobjectBelong() : BelongsTo
    {
        return $this->getPowerSystemResource()->belongsTo(Identifiedobject::class, 'identifiedobject_id');
    }

    /**
     * @param Identifiedobject $identifiedobject
     */
    public function setIdentifiedObject(Identifiedobject $identifiedobject)
    {
        $this->getPowerSystemResource()->io = $identifiedobject;
        //$this->identifiedobjectBelong()->associate($identifiedobject);
    }

    /**
     * @return BelongsTo
     */
    public function psrtype() : BelongsTo
    {
        return $this->getPowerSystemResource()->belongsTo(PSRType::class);
    }

    /**
     * @return PSRType
     */
    public function getPSRType(): ?PSRType
    {
        if($this->getPowerSystemResource()->psrtype) return $this->getPowerSystemResource()->psrtype;
        if($this->psrtype()->get()->get(0)) $this->getPowerSystemResource()->psrtype = $this->psrtype()->get()->get(0);
        return $this->getPowerSystemResource()->psrtype;
    }

    /**
     * @param PSRType $type
     */
    public function setPSRType(PSRType $type) : void
    {
        $this->getPowerSystemResource()->psrtype = $type;
       // $this->psrtype()->associate($type);
    }

    public function assets() : HasMany
    {
        return $this->getPowerSystemResource()->hasMany(Asset::class);
    }

    public function getAssets() : HasMany
    {
        return $this->assets();
    }

    public function addAsset(Asset $asset) : void
    {
        $this->assets()->save($asset);
    }

    public function removeAsset(Asset $asset) : void
    {
        $asset->delete();
    }
}
