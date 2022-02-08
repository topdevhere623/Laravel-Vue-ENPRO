<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class BaseModel
 * @package App\Models
 * @version April 28, 2021, 3:41 am UTC
 */
class BaseModel extends Model
{
    public function getTable() : string
    {
        return $this->table;
    }

    /**
     * @param string $modelName
     * @return Asset
     */
    public function getRelativeAsset($modelName) : ? Asset
    {
        $methodName = 'get' . $modelName;
        if(! empty($this->$methodName()->asset()->first())) {
            $this->$methodName()->asset = $this->$methodName()->asset()->first();
            return $this->$methodName()->asset;
        }

        $this->$methodName()->asset = $this->asset;
        if(!$this->$methodName()->asset) $this->$methodName()->asset = new Asset();
        return $this->$methodName()->asset;
    }

    /**
     * @param string $modelName
     * @param Asset $Asset
     */
    public function setRelativeAsset($modelName, Asset $Asset) : void
    {
        $methodName = 'get' . $modelName;
        $this->$methodName()->asset = $Asset;
    }

    function getValueType() {
        if ((! empty($this->casts)) && array_key_exists('value', $this->casts)) return $this->casts['value'];
        return null;
    }
}
