<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class myLat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($value >= -90 and $value <= 90);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ошибка! Значение долготы должно быть в диапазоне от -90 до 90.';
    }
}
