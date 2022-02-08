<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class CreateWireInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name' => 'required|string',
            'insulated' => 'nullable|boolean',
            'material.id' => 'nullable|integer',
            'sizeDescription' => 'nullable|string',
            'strandCount' => 'nullable|integer',
            'coreStrandCount' => 'nullable|integer',
            'coreRadius.value' => 'nullable|numeric',
            'radius.value' => 'nullable|numeric',
            'gost.id' => 'nullable|integer',
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'  => 'Название обязательно для заполнения',
            'strandCount.integer'  => 'Количество проволок основного материала целое число',
            'coreStrandCount.integer'  => 'Количество проволок в стальном сердечнике целое число',
            'coreRadius.value.numeric'  => 'Диаметр сердечника число',
            'radius.value.numeric'  => 'Диаметр провода число',
        ];
    }

}
