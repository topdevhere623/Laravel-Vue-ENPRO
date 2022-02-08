<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnproDefectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "code" => "reqired",
            "title" => "reqired",
            "critical" => "reqired",
            "class_id" => "reqired",
            "group_id" => "reqired"
        ];
    }
}
