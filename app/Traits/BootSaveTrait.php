<?php


namespace App\Traits;


use App\Models\Line;
use Illuminate\Database\Eloquent\Model;

trait BootSaveTrait
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model  $model) {
            if($model->bootFields) {
                foreach ($model->bootFields as $bootField) {
                    $getMethod = 'get'. $bootField[0];
                    $saveMethod = $bootField[1];
                    if($model->$getMethod()) {
                        if($bootField[2] == 'belongs') {
                            $model->$getMethod()->save();
                            $model->$saveMethod()->associate($model->$getMethod());
                        } else if($bootField[2] == 'hasone') {
                            $detachMethod = $bootField[4];
                            $objects = $model->$saveMethod();
                            foreach($objects as $object) {
                                $object->$detachMethod()->dissociate();
                            }
                            foreach ($model->$getMethod() as $object) {
                                $object->$detachMethod()->associate($model);
                                $object->save();
                            }

                        }
                    }
                }
            }
        });
        self::created(function (Model $model) {
            if($model->selfIdent && $model->id && !$model->getIdentification()) {
                $model->selfIdentification();
                $model->save();
            }
        });

        static::deleted(function (Model $model) {
            if($model->bootFields) {
                foreach ($model->bootFields as $bootField) {
                    if($bootField[3] == 'delete') {
                        $getMethod = 'get'. $bootField[0];
                        $model->$getMethod()->delete();
                    }
                }
            }

        });

        static::saving(function (Model $model) {
            if($model->bootFields) {
                foreach ($model->bootFields as $bootField) {
                    $getMethod = 'get'. $bootField[0];
                    $saveMethod = $bootField[1];
                    if($model->$getMethod()) {
                        if($bootField[2] == 'belongs') {
                            $model->$getMethod()->save();
                            $model->$saveMethod()->associate($model->$getMethod());
                        } else if($bootField[2] == 'hasone') {
                            $detachMethod = $bootField[4];
                            $objects = $model->$saveMethod();
                            foreach($objects as $object) {
                                $object->$detachMethod()->dissociate();
                            }
                            foreach ($model->$getMethod() as $object) {
                                $object->$detachMethod()->associate($model);
                                $object->save();
                            }

                            //$model->$saveMethod()->saveMany($model->$getMethod());
                        }
                    }
                }
            }
        });
    }
}
