<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

// трайты
use App\Traits\CommonTrait;

// модели
use App\Models\Device;
use App\AdminModels\AdminUserRole;
use App\AdminModels\AdminUserRolePivot;

// модель
class User extends Authenticatable
{
    // подключение трайтов
    use CommonTrait;
    // использование мягкого удаления
    use SoftDeletes;
    use Notifiable;
    //use HasFactory;

    // управляемая таблица
    protected $table = "user"; // такое имя было в схеме Firebird

    // список полей, разрешенных на редактирование
    protected $fillable = ['username', 'email', 'password', 'device_id'];
    // скрытые поля
    protected $hidden = ['password', 'remember_token',];
    protected $casts = ['email_verified_at' => 'datetime',];

    // мои атрибуты модели
    const title1 = "Пользователь";
    const title2 = "Пользователи";

    // связи
    // с устройством планшетом
    public function devices()
    {
        return $this->hasMany(Device::class, 'user_id');
    }

    // с ролью данного Пользователя через промежуточную таблицу
    public function role()
    {
        return $this->belongsToMany(AdminUserRole::class, 'admin_user_role_pivots', 'user_id','user_role_id');
    }

    // проверка, является ли Пользователь Разработчиком
    public function isVendor()
    {
        $user = $this->role()->where('name', 'vendor')->exists();
        if ($user) return $user;
    }

    // проверка, является ли Пользователь Администратором
    public function isAdmin()
    {
        $user = $this->role()->where('name', 'admin')->exists();
        if ($user) return $user;
    }

    // проверка, является ли Пользователь Манеджером
    public function isManager()
    {
        $user = $this->role()->where('name', 'manager')->exists();
        if ($user) return $user;
    }

    // проверка, является ли Пользователь Диспетчером
    public function isDispatcher()
    {
        $user = $this->role()->where('name', 'dispatcher')->exists();
        if ($user) return $user;
    }

    // проверка, является ли Пользователь Оператором
    public function isOperator()
    {
        $user = $this->role()->where('name', 'operator')->exists();
        if ($user) return $user;
    }

    // проверка, является ли Пользователь Мастером
    public function isMaster()
    {
        $user = $this->role()->where('name', 'master')->exists();
        if ($user) return $user;
    }

    // проверка, является ли Пользователь Рабочим
    public function isWorking()
    {
        $user = $this->role()->where('name', 'working')->exists();
        if ($user) return $user;
    }

    // проверка, является ли Пользователь Заблокированным
    public function isDisabled()
    {
        $user = $this->role()->where('name', 'disabled')->exists();
        if ($user) return $user;
    }
}
