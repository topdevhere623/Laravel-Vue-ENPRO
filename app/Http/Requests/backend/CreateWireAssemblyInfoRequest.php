<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class CreateWireAssemblyInfoRequest extends FormRequest
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
        $createArray = [
            'AssetInfo.CatalogAssetType.IdentifiedObject.name' => 'required|string',
            'WirePhaseInfo' => 'required|array',
            'WirePhaseInfo.*.phase_info_id' => 'nullable|integer',
            'WirePhaseInfo.*.WireInfo.insulated' => 'present|nullable|boolean',
            'WirePhaseInfo.*.WireInfo.sizeDescription' => 'present|nullable|string',
            'WirePhaseInfo.*.WireInfo.strandCount' => 'present|nullable|integer',
            'WirePhaseInfo.*.WireInfo.coreStrandCount' => 'present|nullable|integer',
            'WirePhaseInfo.*.WireInfo.material_id' => [
                'present',
                'nullable',
                'integer',
                'exists:wire_material_kind,id',
            ],
            'WirePhaseInfo.*.WireInfo.coreRadius.value' => 'present|nullable|numeric',
            'WirePhaseInfo.*.WireInfo.radius.value' => 'present|nullable|numeric',
            'WirePhaseInfo.*.WireInfo.insulation_material_id' => [
                'present',
                'nullable',
                'integer',
                'exists:transformer_cooling_kind,id',
            ],
            'WirePhaseInfo.*.WireInfo.insulationThickness.value' => 'present|nullable|numeric',
            'WirePhaseInfo.*.WireInfo.ratedCurrent.value' => 'present|nullable|numeric',
            'WirePhaseInfo.*.WireInfo.rDC20.value' => 'present|nullable|numeric',
            'WirePhaseInfo.*.WireInfo.enproBreakForce.value' => 'present|nullable|numeric',
            'WirePhaseInfo.*.WireInfo.enproWeightPerLength.value' => 'present|nullable|numeric',
            'WirePhaseInfo.*.WireInfo.gost_id' => 'present|nullable|integer',
            //'WirePhaseInfo.*.WireInfo.OverheadWireInfo.value' => 'present|string',

        ];

        $createArrayAddon = [];
        if ($this->route('modelName') == 'CableInfo') {
            $createArrayAddon = [
                'WirePhaseInfo.*.WireInfo.nominalVoltage.value' => 'present|nullable|numeric',
                'WirePhaseInfo.*.WireInfo.standardServiceLife.value.years' => 'present|nullable|integer',
                'WirePhaseInfo.*.WireInfo.CableInfo' => [
                    'required'
                ],
                'WirePhaseInfo.*.WireInfo.CableInfo.construction_kind_id' => [
                    'present',
                    'nullable',
                    'integer',
                    'exists:cable_construction_kind,id',
                ],
                'WirePhaseInfo.*.WireInfo.CableInfo.fire_safety_id' => [
                    'present',
                    'nullable',
                    'integer',
                    'exists:enpro_fire_safety_kind,id',
                ],
                'WirePhaseInfo.*.WireInfo.CableInfo.shield_material_id' => [
                    'present',
                    'nullable',
                    'integer',
                    'exists:cable_shield_material_kind,id',
                ],
                'WirePhaseInfo.*.WireInfo.CableInfo.outer_jacket_kind_id' => [
                    'present',
                    'nullable',
                    'integer',
                    'exists:cable_outer_jacket_kind,id',
                ],
            ];
        }

        if ($this->route('modelName') == 'OverheadWireInfo') {
            $createArrayAddon = [
                'WirePhaseInfo.*.WireInfo.OverheadWireInfo.value' => 'present',
            ];
        }

        return array_merge($createArray, $createArrayAddon);
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'AssetInfo.CatalogAssetType.IdentifiedObject.name.required'  => 'Название обязательно для заполнения',
            'AssetInfo.CatalogAssetType.IdentifiedObject.name.string'  => 'Название строка',
            'WirePhaseInfo.required' => 'Не заданы технические характеристики и свойства',
            'WirePhaseInfo.*.phaseInfo.id.required' => 'Фазы проводника обязательно для заполнения',
            'WirePhaseInfo.*.phaseInfo.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.insulated.boolean' => 'Изолированный провод? Значение true или false',
            'WirePhaseInfo.*.WireInfo.sizeDescription.string' => 'Сечение основное/сердечника, мм, строка',
            'WirePhaseInfo.*.WireInfo.strandCount' => 'Количество проволок основного материала целое число',
            'WirePhaseInfo.*.WireInfo.coreStrandCount' => 'Количество проволок в стальном сердечнике целое число',
            'WirePhaseInfo.*.WireInfo.material_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.coreRadius.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.radius.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.insulation_material_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.insulationThickness.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.ratedCurrent.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.rDC20.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.enproBreakForce.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.enproWeightPerLength.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.nominalVoltage.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.standardServiceLife.value.years.integer' => 'Количество лет целое число',
            'WirePhaseInfo.*.WireInfo.gost_id.integer' => 'ИД ГОСТа целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.required' => 'Не заданы технические характеристики или свойства',
            'WirePhaseInfo.*.WireInfo.CableInfo.construction_kind_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.fire_safety_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.shield_material_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.outer_jacket_kind_id.integer' => 'Значение целое число',
        ];
    }
}
