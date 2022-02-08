<?php

namespace App\Http\Requests\backend;

use App\Models\OldTransformerTankInfo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOldTransformerTankInfoRequest extends FormRequest
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
                'present',
                'nullable',
                'integer',
                'exists:transformer_construction_kind,id'
            ],
            'coreCoilsWeight.value' => 'present|nullable|numeric',
            'core_kind_id' => [
                'present',
                'nullable',
                'integer',
                'exists:transformer_core_kind,id',
            ],
            'function_id' => [
                'present',
                'nullable',
                'integer',
                'exists:transformer_function_kind,id',
            ],
            'cooling_kind_id' => [
                'present',
                'nullable',
                'integer',
                'exists:transformer_cooling_kind,id',
            ],
            'enproFullWeight' => 'required',
            'enproFullWeight.value' => 'present|nullable|numeric',
            'enproOilWeight' => 'required',
            'enproOilWeight.value' => 'present|nullable|numeric',
            'enproTemperatureRange.minTemperature' => 'required',
            'enproTemperatureRange.minTemperature.value' => 'present|nullable|numeric',
            'enproTemperatureRange.maxTemperature' => 'required',
            'enproTemperatureRange.maxTemperature.value' => 'present|nullable|numeric',
            'gost_id' => 'present|nullable|integer',
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
            'construction_kind_id.integer' => 'Значение целое число',
            'coreCoilsWeight.value.numeric' => 'Значение число',
            'core_kind_id.integer' => 'Значение целое число',
            'function_id.integer' => 'Значение целое число',
            'cooling_kind_id.integer' => 'Значение целое число',
            'enproFullWeight' => 'Обязательно для заполнения',
            'enproFullWeight.value.numeric' => 'Значение число',
            'enproOilWeight' => 'Обязательно для заполнения',
            'enproOilWeight.value.numeric' => 'Значение число',
            'enproTemperatureRange.minTemperature' => 'Обязательно для заполнения',
            'enproTemperatureRange.minTemperature.value.numeric' => 'Значение число',
            'enproTemperatureRange.maxTemperature' => 'Обязательно для заполнения',
            'enproTemperatureRange.maxTemperature.value.numeric' => 'Значение число',
            'gost_id.integer' => 'ИД ГОСТа целое число',
        ];
    }
};
