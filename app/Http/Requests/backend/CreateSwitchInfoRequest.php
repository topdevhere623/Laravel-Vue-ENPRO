<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class CreateSwitchInfoRequest extends FormRequest
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
        $mainArray = [
            'AssetInfo.CatalogAssetType.IdentifiedObject.name' => 'required|string',
            'AssetInfo.CatalogAssetType.IdentifiedObject.names.*.name' => 'required|string',
        ];

        $childArray = [];

        if ($this->route('modelName') == 'FuseInfo') {
            $childArray = [
                'enpro_breaker_kind_id' => 'required|integer',
                'ratedVoltage.value' => 'present|nullable|numeric',
                'enproMaxVoltage.value' => 'present|nullable|numeric',
                'ratedFrequency.value' => 'present|nullable|numeric',
                'ratedCurrent.value' => 'present|nullable|numeric',
                'breakingCapacity.value' => 'present|nullable|numeric',
                'OldSwitchInfo.poleCount' => 'required|integer',
                'enproInsulationLength.value' => 'present|nullable|numeric',
                'enpro_climatic_mod_placement_id' => 'required|integer',
                'enproTemperatureRange.minTemperature.value' => 'present|nullable|numeric',
                'enpro_gost_id' => 'present|nullable|integer',
                'isSinglePhase' => 'required|boolean',
            ];
        }

        if ($this->route('modelName') == 'RecloserInfo') {
            $childArray = [
                'enpro_breaker_kind_id' => 'required|integer',
                'ratedVoltage.value' => 'present|nullable|numeric',
                'enproMaxVoltage.value' => 'present|nullable|numeric',
                'ratedFrequency.value' => 'present|nullable|numeric',
                'ratedCurrent.value' => 'present|nullable|numeric',
                'OldSwitchInfo.poleCount' => 'required|integer',
                'OldSwitchInfo.withstandCurrent.value' => 'present|nullable|numeric',
                'OldSwitchInfo.makingCapacity.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enproWithstandCurrentDuration.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enproEarthSwitchCurrentDuration.value' => 'present|nullable|numeric',
                'ratedImpulseWithstandVoltage.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enpro_secondary_voltage_kind_id' => 'required|integer',
                'OldSwitchInfo.enproSecondaryVoltage.value' => 'present|nullable|numeric',
                'enproInsulationLength.value' => 'present|nullable|numeric',
                'enpro_climatic_mod_placement_id' => 'required|integer',
                'enproTemperatureRange.minTemperature.value' => 'present|nullable|numeric',
                'enpro_gost_id' => 'present|nullable|integer',
                'OldSwitchInfo.remote' => 'required|boolean',
            ];
        }

        if ($this->route('modelName') == 'LoadBreakSwitchInfo') {
            $childArray = [
                'enpro_breaker_kind_id' => 'required|integer',
                'ratedVoltage.value' => 'present|nullable|numeric',
                'enproMaxVoltage.value' => 'present|nullable|numeric',
                'ratedFrequency.value' => 'present|nullable|numeric',
                'ratedCurrent.value' => 'present|nullable|numeric',
                'OldSwitchInfo.loadBreak' => 'required|boolean',
                'OldSwitchInfo.poleCount' => 'required|integer',
                'OldSwitchInfo.withstandCurrent.value' => 'present|nullable|numeric',
                'OldSwitchInfo.makingCapacity.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enproWithstandCurrentDuration.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enproEarthSwitchCurrentDuration.value' => 'present|nullable|numeric',
                'ratedImpulseWithstandVoltage.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enpro_secondary_voltage_kind_id' => 'required|integer',
                'OldSwitchInfo.enproSecondaryVoltage.value' => 'present|nullable|numeric',
                'enproRatedPressure.value' => 'present|nullable|numeric',
                'enproInsulationLength.value' => 'present|nullable|numeric',
                'enpro_climatic_mod_placement_id' => 'required|integer',
                'enproTemperatureRange.minTemperature.value' => 'present|nullable|numeric',
                'enpro_gost_id' => 'present|nullable|integer',
                'OldSwitchInfo.remote' => 'required|boolean',
            ];
        }

        if ($this->route('modelName') == 'DisconnectorInfo') {
            $childArray = [
                'ratedVoltage.value' => 'present|nullable|numeric',
                'enproMaxVoltage.value' => 'present|nullable|numeric',
                'ratedFrequency.value' => 'present|nullable|numeric',
                'ratedCurrent.value' => 'present|nullable|numeric',
                'OldSwitchInfo.poleCount' => 'required|integer',
                'OldSwitchInfo.withstandCurrent.value' => 'present|nullable|numeric',
                'OldSwitchInfo.makingCapacity.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enproWithstandCurrentDuration.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enproEarthSwitchCurrentDuration.value' => 'present|nullable|numeric',
                'ratedImpulseWithstandVoltage.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enpro_secondary_voltage_kind_id' => 'required|integer',
                'OldSwitchInfo.enproSecondaryVoltage.value' => 'present|nullable|numeric',
                'enproRatedPressure.value' => 'present|nullable|numeric',
                'enproBreakForce.value' => 'present|nullable|numeric',
                'enproInsulationLength.value' => 'present|nullable|numeric',
                'enpro_climatic_mod_placement_id' => 'required|integer',
                'enproTemperatureRange.minTemperature.value' => 'present|nullable|numeric',
                'enpro_gost_id' => 'present|nullable|integer',
                'isSinglePhase' => 'required|boolean',
                'isUnganged' => 'required|boolean',
                'OldSwitchInfo.remote' => 'required|boolean',
            ];
        }

        if ($this->route('modelName') == 'BreakerInfo') {
            $childArray = [
                'enpro_breaker_kind_id' => 'required|integer',
                'enpro_interrupter_position_id' => 'required|integer',
                'ratedVoltage.value' => 'present|nullable|numeric',
                'enproMaxVoltage.value' => 'present|nullable|numeric',
                'ratedFrequency.value' => 'present|nullable|numeric',
                'ratedCurrent.value' => 'present|nullable|numeric',
                'breakingCapacity.value' => 'present|nullable|numeric',
                'ratedInterruptingTime.value' => 'present|nullable|numeric',
                'OldSwitchInfo.poleCount' => 'required|integer',
                'OldSwitchInfo.withstandCurrent.value' => 'present|nullable|numeric',
                'OldSwitchInfo.makingCapacity.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enproWithstandCurrentDuration.value' => 'present|nullable|numeric',
                'ratedImpulseWithstandVoltage.value' => 'present|nullable|numeric',
                'OldSwitchInfo.enpro_secondary_voltage_kind_id' => 'required|integer',
                'OldSwitchInfo.enproSecondaryVoltage.value' => 'present|nullable|numeric',
                'enproRatedPressure.value' => 'present|nullable|numeric',
                'lowPressureAlarm.value' => 'present|nullable|numeric',
                'lowPressureLockOut.value' => 'present|nullable|numeric',
                'enproBreakForce.value' => 'present|nullable|numeric',
                'enproInsulationLength.value' => 'present|nullable|numeric',
                'enpro_climatic_mod_placement_id' => 'required|integer',
                'enproTemperatureRange.minTemperature.value' => 'present|nullable|numeric',
                'enpro_gost_id' => 'present|nullable|integer',
                'isSinglePhase' => 'required|boolean',
                'isUnganged' => 'required|boolean',
                'OldSwitchInfo.remote' => 'required|boolean',
                'gasWeightPerTank.value' => 'present|nullable|numeric',
                'oilVolumePerTank.value' => 'present|nullable|numeric',
            ];
        }

        return array_merge($mainArray, $childArray);
    }


    /**
     * @return array
     */
    public function messages()
    {
        return [
            'AssetInfo.CatalogAssetType.IdentifiedObject.name.required'  => 'Название обязательно для заполнения',
            'AssetInfo.CatalogAssetType.IdentifiedObject.name.string'  => 'Название строка',

            'AssetInfo.CatalogAssetType.IdentifiedObject.names.name.required'  => 'Название обязательно для заполнения',
            'AssetInfo.CatalogAssetType.IdentifiedObject.names.name.string'  => 'Название строка',

            'breakingCapacity.value.numeric' => 'Значение число',

            'enpro_breaker_kind_id.required' => 'Пустое значение - Принцип гашения дуги',
            'enpro_breaker_kind_id.integer' => 'Значение целое число',

            'enpro_climatic_mod_placement_id.required' => 'Климатическое исполнение и категория размещения пуст',

            'enpro_climatic_mod_placement_id.numeric' => 'Значение число',

            'enpro_gost_id.integer' => 'Значение целое число',

            'enpro_interrupter_position_id.required' => 'Пустое значение - Вид размещения ДГУ',
            'enpro_interrupter_position_id.integer' => 'Значение целое число',

            'enproBreakForce.value.numeric' => 'Значение число',
            'enproInsulationLength.value.numeric' => 'Значение число',
            'enproMaxVoltage.value.numeric' => 'Значение число',
            'enproRatedPressure.value.numeric' => 'Значение число',
            'enproTemperatureRange.minTemperature.value.numeric' => 'Значение число',
            'gasWeightPerTank.value.numeric' => 'Значение число',

            'isSinglePhase.required' => 'Пустое значение - Однополюсный',
            'isSinglePhase.boolean' => 'Значение логическое',

            'isUnganged.required' => 'Пустое значение - Управляется пофазно',
            'isUnganged.boolean' => 'Значение логическое',

            'lowPressureAlarm.value.numeric' => 'Значение число',
            'lowPressureLockOut.value.numeric' => 'Значение число',

            'oilVolumePerTank.value.numeric' => 'Значение число',

            'OldSwitchInfo.enpro_secondary_voltage_kind_id.required' => 'Номинальная частота питания вкл. и отк. устройств, вспом. цепей и цепей упр. пуст',
            'OldSwitchInfo.enpro_secondary_voltage_kind_id.integer' => 'Значение целое число',
            'OldSwitchInfo.enproEarthSwitchCurrentDuration.value.numeric' => 'Значение число',
            'OldSwitchInfo.enproSecondaryVoltage.value.numeric' => 'Значение число',
            'OldSwitchInfo.enproWithstandCurrentDuration.value.numeric' => 'Значение число',
            'OldSwitchInfo.loadBreak.required' => 'Пустое значение - Выключатель нагрузки',
            'OldSwitchInfo.loadBreak.boolean' => 'Значение логическое',
            'OldSwitchInfo.makingCapacity.value.numeric' => 'Значение число',
            'OldSwitchInfo.poleCount.required' => 'Пустое значение - Количество полюсов',
            'OldSwitchInfo.poleCount.integer' => 'Значение целое число',
            'OldSwitchInfo.remote.required' => 'Пустое значение - Поддерживает дистанционное управление',
            'OldSwitchInfo.remote.boolean' => 'Значение логическое',
            'OldSwitchInfo.withstandCurrent.value.numeric' => 'Значение число',
            'ratedCurrent.value.numeric' => 'Значение число',
            'ratedFrequency.value.numeric' => 'Значение число',
            'ratedImpulseWithstandVoltage.value.numeric' => 'Значение число',
            'ratedInterruptingTime.value.numeric' => 'Значение число',
            'ratedVoltage.value.numeric' => 'Значение число',
        ];
    }
}
