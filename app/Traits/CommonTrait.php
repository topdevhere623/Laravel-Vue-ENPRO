<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

// общее
trait CommonTrait
{
    // возвращает имя управлемой таблицы
    public function getTable()
    {
        return $this->table;
    }
}