<?php

namespace App\AdminModels;

use Illuminate\Database\Eloquent\Model;

// модели
use App\Models\User;

// ts новая модель log-действия Пользователя
class AdminLog extends Model
{
    // управляемая таблица
    protected $table = "admin_log";

    // список полей, разрешенных на редактирование
    protected $fillable = [];
    // список полей запрещенных на редактирование
    protected $guarded = [];

    // поля datetime не нужно
    public $timestamps = false;

    // мои атрибуты модели
    const title1 = "Журнал операций Пользователя";
    const title2 = "Журнал операций Пользователя";

    // связи
    // с пользователем
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault(['username' => 'Не определено']);
    }
}
