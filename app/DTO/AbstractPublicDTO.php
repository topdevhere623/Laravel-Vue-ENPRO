<?php


namespace App\DTO;

use App\Models as Model;

abstract class AbstractPublicDTO
{
    /**
     * @return static
     */
    public static function instance()
    {
        return new static;
    }

    abstract public function load($model);

}
