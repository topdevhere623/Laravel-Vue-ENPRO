<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWireAssemblyInfoRequest extends FormRequest
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
            'AssetInfo.CatalogAssetType.IdentifiedObject.name' => 'required|string',
            'WirePhaseInfo.*.phase_info_id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.insulated' => 'present|boolean',
            'WirePhaseInfo.*.WireInfo.sizeDescription' => 'present|nullable|string',
            'WirePhaseInfo.*.WireInfo.name' => 'present|nullable|string',
            'WirePhaseInfo.*.WireInfo.strandCount' => 'present|integer',
            'WirePhaseInfo.*.WireInfo.coreStrandCount' => 'present|nullable|integer',
            'WirePhaseInfo.*.WireInfo.material_id' => [
                'required',
                'integer',
                'exists:wire_material_kind,id',
            ],
            'WirePhaseInfo.*.WireInfo.coreRadius.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.coreRadius.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.radius.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.radius.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.insulation_material_id' => [
                'required',
                'integer',
                'exists:transformer_cooling_kind,id',
            ],
            'WirePhaseInfo.*.WireInfo.insulationThickness.id' => 'present|integer',
            'WirePhaseInfo.*.WireInfo.insulationThickness.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.ratedCurrent.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.ratedCurrent.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.rDC20.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.rDC20.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.enproBreakForce.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.enproBreakForce.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.enproWeightPerLength.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.enproWeightPerLength.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.nominalVoltage.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.nominalVoltage.value' => 'present|nullable|number',
            'WirePhaseInfo.*.WireInfo.standardServiceLife.id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.standardServiceLife.value.years' => 'present|integer',
            'WirePhaseInfo.*.WireInfo.enpro_gost_id' => 'required|integer',
            'WirePhaseInfo.*.WireInfo.OverheadWireInfo.id' => 'present|integer',
            'WirePhaseInfo.*.WireInfo.OverheadWireInfo.value' => 'present|string',
            'WirePhaseInfo.*.WireInfo.CableInfo.construction_kind_id' => [
                'required',
                'integer',
                'exists:cable_construction_kind,id',
            ],
            'WirePhaseInfo.*.WireInfo.CableInfo.fire_safety_id' => [
                'required',
                'integer',
                'exists:enpro_fire_safety_kind,id',
            ],
            'WirePhaseInfo.*.WireInfo.CableInfo.shield_material_id' => [
                'required',
                'integer',
                'exists:cable_shield_material_kind,id',
            ],
            'WirePhaseInfo.*.WireInfo.CableInfo.outer_jacket_kind_id' => [
                'required',
                'integer',
                'exists:cable_outer_jacket_kind,id',
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'AssetInfo.CatalogAssetType.IdentifiedObject.name.required'  => 'Название обязательно для заполнения',
            'AssetInfo.CatalogAssetType.IdentifiedObject.name.string'  => 'Название строка',
            'WirePhaseInfo.*.WireInfo.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.phaseInfo.id.required' => 'Фазы проводника обязательно для заполнения',
            'WirePhaseInfo.*.phaseInfo.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.insulated.boolean' => 'Изолированный провод? Значение true или false',
            'WirePhaseInfo.*.WireInfo.sizeDescription.string' => 'Сечение основное/сердечника, мм, строка',
            'WirePhaseInfo.*.WireInfo.strandCount' => 'Количество проволок основного материала целое число',
            'WirePhaseInfo.*.WireInfo.coreStrandCount' => 'Количество проволок в стальном сердечнике целое число',
            'WirePhaseInfo.*.WireInfo.material_id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.material_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.coreRadius.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.coreRadius.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.coreRadius.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.radius.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.radius.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.radius.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.insulation_material_id.required' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.insulation_material_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.insulationThickness.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.insulationThickness.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.ratedCurrent.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.ratedCurrent.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.ratedCurrent.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.rDC20.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.rDC20.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.rDC20.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.enproBreakForce.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.enproBreakForce.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.enproBreakForce.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.enproWeightPerLength.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.enproWeightPerLength.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.enproWeightPerLength.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.nominalVoltage.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.nominalVoltage.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.nominalVoltage.value.numeric' => 'Значение число',
            'WirePhaseInfo.*.WireInfo.standardServiceLife.id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.standardServiceLife.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.standardServiceLife.value.years.integer' => 'Количество лет целое число',
            'WirePhaseInfo.*.WireInfo.enpro_gost_id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.enpro_gost_id.integer' => 'ИД ГОСТа целое число',
            'WirePhaseInfo.*.WireInfo.OverheadWireInfo.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.OverheadWireInfo.value.string' => 'Значение строка',
            'WirePhaseInfo.*.WireInfo.CableInfo.construction_kind_id.id.required' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.construction_kind_id.id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.fire_safety_id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.CableInfo.fire_safety_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.shield_material_id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.CableInfo.shield_material_id.integer' => 'Значение целое число',
            'WirePhaseInfo.*.WireInfo.CableInfo.outer_jacket_kind_id.required' => 'Обязательно для заполнения',
            'WirePhaseInfo.*.WireInfo.CableInfo.outer_jacket_kind_id.integer' => 'Значение целое число',
        ];
    }
}
