<?php


namespace App\Traits;


use App\Models\UnitMultiplier;
use App\Models\UnitSymbol;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait DataTypeTrait
{

    public function unit() : BelongsTo
    {
        return $this->belongsTo(UnitSymbol::class);
    }

    /**
     * @return UnitSymbol
     */
    public function getUnit(): UnitSymbol
    {
        return $this->unit()->get()->get(0);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function multiplier() : BelongsTo
    {
        return $this->belongsTo(UnitMultiplier::class);
    }

    /**
     * @return UnitMultiplier
     */
    public function getMultiplier(): UnitMultiplier
    {
        return $this->multiplier()->get()->get(0);
    }

    /**
     * @param UnitSymbol $literal
     */
    public function setUnit(UnitSymbol $literal): void
    {
        $this->unit()->associate($literal);
    }

    /**
     * @param $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @param UnitMultiplier $multiplier
     */
    public function setMultiplier(UnitMultiplier $multiplier): void
    {
        $this->multiplier()->associate($multiplier);
    }
}
