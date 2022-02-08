<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOldTransformerTankInfoRequest extends FormRequest
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
        return [
            'TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name' => 'required|string',
            'construction_kind_id' => [
                'required',
                'integer',
                'exists:transformer_construction_kind,id'
            ],
            'coreCoilsWeight.id' => 'required|integer',
            'coreCoilsWeight.value' => 'present|numeric',
            'core_kind_id' => [
                'required',
                'integer',
                'exists:transformer_core_kind,id',
            ],
            'function_id' => [
                'required',
                'integer',
                'exists:transformer_function_kind,id',
            ],

            'cooling_kind_id' => [
                'required',
                'integer',
                'exists:wire_insulation_kind,id',
            ],
            'enproFullWeight' => 'required',
            'enproFullWeight.id' => 'required|integer',
            'enproFullWeight.value' => 'present|numeric',
            'enproOilWeight' => 'required',
            'enproOilWeight.id' => 'required|integer',
            'enproOilWeight.value' => 'present|numeric',
            'enproTemperatureRange.minTemperature' => 'required',
            'enproTemperatureRange.minTemperature.id' => 'required|integer',
            'enproTemperatureRange.minTemperature.value' => 'present|numeric',
            'enproTemperatureRange.maxTemperature' => 'required',
            'enproTemperatureRange.maxTemperature.id' => 'required|integer',
            'enproTemperatureRange.maxTemperature.value' => 'present|numeric',
            'enpro_gost_id' => 'required|integer',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name.required'  => 'Название обязательно для заполнения',
            'TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name.string'  => 'Название строка',
            'construction_kind_id.required' => 'Обязательно для заполнения',
            'construction_kind_id.integer' => 'Значение целое число',
            'coreCoilsWeight.value.numeric' => 'Значение число',
            'core_kind_id.required' => 'Обязательно для заполнения',
            'core_kind_id.integer' => 'Значение целое число',
            'function_id.required' => 'Обязательно для заполнения',
            'function_id.integer' => 'Значение целое число',
            'cooling_kind_id.required' => 'Обязательно для заполнения',
            'cooling_kind_id.integer' => 'Значение целое число',
            'enproFullWeight' => 'Обязательно для заполнения',
            'enproFullWeight.id.required' => 'Обязательно для заполнения',
            'enproFullWeight.value.numeric' => 'Значение число',
            'enproOilWeight' => 'Обязательно для заполнения',
            'enproOilWeight.id.required' => 'Обязательно для заполнения',
            'enproOilWeight.value.numeric' => 'Значение число',
            'enproTemperatureRange.minTemperature' => 'Обязательно для заполнения',
            'enproTemperatureRange.minTemperature.id.required' => 'Обязательно для заполнения',
            'enproTemperatureRange.minTemperature.value.numeric' => 'Значение число',
            'enproTemperatureRange.maxTemperature' => 'Обязательно для заполнения',
            'enproTemperatureRange.maxTemperature.id.required' => 'Обязательно для заполнения',
            'enproTemperatureRange.maxTemperature.value.numeric' => 'Значение число',
            'enpro_gost_id.required' => 'Обязательно для заполнения',
            'enpro_gost_id.integer' => 'ИД ГОСТа целое число',
        ];
    }
}
