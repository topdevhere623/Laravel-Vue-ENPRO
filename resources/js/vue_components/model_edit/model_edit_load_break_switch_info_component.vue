<template>
  <section>
    <div class="page-header">
      <!-- заголовок -->
      <h2 class="page-title">{{ titleOne }}</h2>

      <!-- хлебные крошки -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="/admin">Главная</a>
        </li>
        <li class="breadcrumb-item">
          <!-- Дефекты - Критичность -->

          <!-- <a :href="`/admin/${modelRoute[getModelName]}`">{{ titleOne }}</a> -->
          <a :href="`/admin/load_break_switch_info`">{{ titleOne }}</a>
        </li>
        <li class="breadcrumb-item active">
          {{
            (getModelId > 0 ? "Редактирование" : "Создание") +
            (modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name &&
            modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name !== ""
              ? " - " +
              modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name
              : "")
          }}
        </li>
      </ol>

      <!-- действия на странице -->
      <div class="page-header-actions">
        <!-- кнопка выход -->
        <!-- <a href="/admin/wire_info" class="button"> Сохранить </a>
        <a href="/admin/wire_info" class="button"> Изменить </a> -->
        <button v-if="!editing" @click="editing = true" class="button">Редактировать</button>
        <a href="/admin/load_break_switch_info" class="button"> Закрыть </a>
      </div>
    </div>

    <!-- содержимое страницы-->
    <div class="page-content main-content">
      <div class="row row-lg">
        <div class="col-lg-12">
          <div class="panel panel-bordered form-icons">
            <div class="panel-body">
              <!-- индикатор загрузки -->
              <div v-if="loading">
                <img
                  src="/public/uploads/loading.gif"
                  style="
                    width: 150px;
                    position: fixed;
                    margin: auto;
                    top: 0;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    z-index: 9999;
                  "
                />
              </div>
              <div v-else-if="errored" class="alert alert-danger" role="alert">
                Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
                <img
                  src="/public/uploads/icons/reload.svg"
                  style="width: 25px; margin-left: 5px"
                  v-on:click="funLoad(this.getModelId)"
                />
              </div>
              <div class="example-wrap">
                <div class="nav-tabs-horizontal" data-plugin="tabs">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link active"
                        data-toggle="tab"
                        href="#techData"
                        aria-controls="techData"
                        role="tab"
                        aria-selected="true"
                      >
                        Основные технические данные
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#techDesc"
                        aria-controls="techDesc"
                        role="tab"
                        aria-selected="true"
                      >
                        Свойства
                      </a>
                    </li>
                  </ul>

                  <div class="tab-content pt-20">
                    <!-- вкладка Основное -->

                    <!-- тех данные--->
                    <div class="tab-pane" id="techData" role="tabpanel">
                      <div class="row">
                        <div class="col-md-10">
                          <div
                            class="row form-field-2-column row-gap-18 row-gap"
                          >
                            <div class="col-md-6">
                              <div class="form-input-label">
                                Наименование марки оборудования
                              </div>
                              <div class="form-field">
                                <input
                                  :readonly="!editing"
                                  type="text"
                                  class="text-field text-field_v_2"
                                  :class="{
                                    'is-invalid-borders':
                                      $v.modelData.AssetInfo.CatalogAssetType
                                        .IdentifiedObject.name.$error,
                                  }"
                                  name="modelName"
                                  v-model.trim="
                                    $v.modelData.AssetInfo.CatalogAssetType
                                      .IdentifiedObject.name.$model
                                  "
                                  label="Наименование марки оборудования"
                                />
                                <div
                                  v-if="
                                    $v.modelData.AssetInfo.CatalogAssetType
                                      .IdentifiedObject.name.$error
                                  "
                                  class="
                                    form-text
                                    text-danger
                                    error-label
                                    text-left
                                  "
                                >
                                  Пожалуйста, заполните это поле
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">Наименование</div>
                              <div class="form-field">
                                <input
                                  :readonly="!editing"
                                  type="text"
                                  class="text-field text-field_v_2"
                                  :class="{
                                    'is-invalid-borders':
                                      $v.modelData.AssetInfo.CatalogAssetType
                                        .IdentifiedObject.names.$each[0].name
                                        .$error,
                                  }"
                                  name="modelName"
                                  v-model.trim="
                                    $v.modelData.AssetInfo.CatalogAssetType
                                      .IdentifiedObject.names.$each[0].name
                                      .$model
                                  "
                                  label="Наименование марки оборудования"
                                />
                                <div
                                  v-if="
                                    $v.modelData.AssetInfo.CatalogAssetType
                                      .IdentifiedObject.names.$each[0].name
                                      .$error
                                  "
                                  class="
                                    form-text
                                    text-danger
                                    error-label
                                    text-left
                                  "
                                >
                                  Пожалуйста, заполните это поле
                                </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <hr class="mt-0"/>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label position-initial">
                                {{
                                  modelData.SwitchInfo.enpro_breaker_kind_id
                                    .label
                                }}
                              </div>
                              <select-with-search
                                get-label="value"
                                @select="
                                  modelData.SwitchInfo.enpro_breaker_kind_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.SwitchInfo.enpro_breaker_kind_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="
                                  modelData.SwitchInfo.enpro_breaker_kind_id
                                    .value
                                "
                                get-model-name="BreakerConstructionKind"
                                get-id="BreakerConstructionKind"
                                get-title="BreakerConstructionKind"
                                get-url="all_kind/model/BreakerConstructionKind"
                                :disabled="!editing"
                              ></select-with-search>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{ modelData.SwitchInfo.ratedVoltage.label }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="ratedVoltage"
                                  v-model="
                                    modelData.SwitchInfo.ratedVoltage.value
                                  "
                                  :label="
                                    modelData.SwitchInfo.ratedVoltage.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>кB</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{ modelData.SwitchInfo.enproMaxVoltage.label }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="enproMaxVoltage"
                                  v-model="
                                    modelData.SwitchInfo.enproMaxVoltage.value
                                  "
                                  :label="
                                    modelData.SwitchInfo.enproMaxVoltage.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>кB</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{ modelData.SwitchInfo.ratedFrequency.label }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="ratedFrequency"
                                  v-model="
                                    modelData.SwitchInfo.ratedFrequency.value
                                  "
                                  :label="
                                    modelData.SwitchInfo.ratedFrequency.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>Гц</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{ modelData.SwitchInfo.ratedCurrent.label }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="ratedCurrent"
                                  v-model="
                                    modelData.SwitchInfo.ratedCurrent.value
                                  "
                                  :label="
                                    modelData.SwitchInfo.ratedCurrent.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>А</span>
                                </div>
                              </div>
                            </div>
                            <div class="form-field col-md-6">
                              <div
                                class="form-input-label position-initial mb-3"
                              >
                                {{
                                  modelData.SwitchInfo
                                    .enpro_climatic_mod_placement_id.label
                                }}
                              </div>
                              <select-with-search
                                get-label="value"
                                @select="
                                  modelData.SwitchInfo.enpro_climatic_mod_placement_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.SwitchInfo.enpro_climatic_mod_placement_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="
                                  modelData.SwitchInfo
                                    .enpro_climatic_mod_placement_id.value
                                "
                                get-model-name="GostClimaticModPlacementKind"
                                get-id="GostClimaticModPlacementKind"
                                get-title="GostClimaticModPlacementKind"
                                get-url="all_kind/model/GostClimaticModPlacementKind"
                                :disabled="!editing"
                              ></select-with-search>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label mb-3">
                                {{
                                  modelData.SwitchInfo.enproTemperatureRange
                                    .label
                                }}
                              </div>

                              <div class="row">
                                <div class="col-md-6 col-lg-6">
                                  <div class="input-group form-field">
                                    <input
                                      :readonly="!editing"
                                      type="number"
                                      class="text-field text-field_v_2"
                                      name="enproTemperatureRangeMinTemperature"
                                      v-model.number="
                                        modelData.SwitchInfo
                                          .enproTemperatureRange.minTemperature
                                          .value
                                      "
                                      placeholder="Мин. температура"
                                      :disabled="isLoading || isSaving"
                                    />
                                    <span class="input-group-prepend pt-0"
                                    >°C</span
                                    >
                                  </div>
                                </div>

                                <div class="col-md-6 col-lg-6">
                                  <div class="input-group form-field">
                                    <input
                                      :readonly="!editing"
                                      type="number"
                                      class="text-field text-field_v_2"
                                      name="enproTemperatureRangeMaxTemperature"
                                      v-model.number="
                                        modelData.SwitchInfo
                                          .enproTemperatureRange.maxTemperature
                                          .value
                                      "
                                      placeholder="Макс. температура"
                                      :disabled="isLoading || isSaving"
                                    />
                                    <span class="input-group-prepend pt-0"
                                    >°C</span
                                    >
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6 form-label-space">
                              <div
                                class="form-input-label position-initial mb-3"
                              >
                                {{ modelData.SwitchInfo.enpro_gost_id.label }}
                              </div>
                              <select-with-search
                                get-label="name"
                                @select="
                                  modelData.SwitchInfo.enpro_gost_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.SwitchInfo.enpro_gost_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="
                                  modelData.SwitchInfo.enpro_gost_id.value
                                "
                                get-model-name="gost"
                                get-id="gost"
                                get-title="gost"
                                get-url="gost"
                                :disabled="!editing"
                              ></select-with-search>
                            </div>

                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{ modelData.OldSwitchInfo.remote.label }}
                              </div>
                              <div class="input-group form-field">
                                <div class="checkbox mt-15">
                                  <label>
                                    <input
                                      @click="(e) => !editing ? e.preventDefault() : true"
                                      type="checkbox"
                                      v-model="
                                        modelData.OldSwitchInfo.remote.value
                                      "
                                    />
                                    <span class="box"></span>
                                    <span
                                      v-if="
                                        modelData.OldSwitchInfo.remote.value
                                      "
                                    >Да</span
                                    >
                                    <span v-else>Нет</span>
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div style="text-align: right; margin-bottom: 30px">
                            <button
                              type="button"
                              class="button bordered"
                              @click="funSave()"
                              :disabled="!editing"
                            >
                              Сохранить
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="techDesc" role="tabpanel">
                      <div class="row">
                        <div class="col-md-10">
                          <div
                            class="row form-field-2-column row-gap-18 row-gap"
                          >
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{ modelData.OldSwitchInfo.loadBreak.label }}
                              </div>
                              <div class="input-group form-field">
                                <div class="checkbox mt-15">
                                  <label>
                                    <input
                                      @click="(e) => !editing ? e.preventDefault() : true"
                                      type="checkbox"
                                      disabled
                                      v-model="
                                        modelData.OldSwitchInfo.loadBreak.value
                                      "
                                    />
                                    <span class="box"></span>
                                    <span>Да</span>
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6 form-label-space">
                              <div class="form-input-label">
                                {{ modelData.OldSwitchInfo.poleCount.label }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="poleCount"
                                  v-model="
                                    modelData.OldSwitchInfo.poleCount.value
                                  "
                                  :label="
                                    modelData.OldSwitchInfo.poleCount.label
                                  "
                                />
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{
                                  modelData.OldSwitchInfo.withstandCurrent.label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="withstandCurrent"
                                  v-model="
                                    modelData.OldSwitchInfo.withstandCurrent
                                      .value
                                  "
                                  :label="
                                    modelData.OldSwitchInfo.withstandCurrent
                                      .label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>кА</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{
                                  modelData.OldSwitchInfo.makingCapacity.label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="withstandCurrent"
                                  v-model="
                                    modelData.OldSwitchInfo.makingCapacity.value
                                  "
                                  :label="
                                    modelData.OldSwitchInfo.makingCapacity.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>кА</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{
                                  modelData.OldSwitchInfo
                                    .enproWithstandCurrentDuration.label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="enproWithstandCurrentDuration"
                                  v-model="
                                    modelData.OldSwitchInfo
                                      .enproWithstandCurrentDuration.value
                                  "
                                  :label="
                                    modelData.OldSwitchInfo
                                      .enproWithstandCurrentDuration.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>с</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{
                                  modelData.OldSwitchInfo
                                    .enproEarthSwitchCurrentDuration.label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="enproEarthSwitchCurrentDuration"
                                  v-model="
                                    modelData.OldSwitchInfo
                                      .enproEarthSwitchCurrentDuration.value
                                  "
                                  :label="
                                    modelData.OldSwitchInfo
                                      .enproEarthSwitchCurrentDuration.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>с</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{
                                  modelData.SwitchInfo
                                    .ratedImpulseWithstandVoltage.label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="ratedImpulseWithstandVoltage"
                                  v-model="
                                    modelData.SwitchInfo
                                      .ratedImpulseWithstandVoltage.value
                                  "
                                  :label="
                                    modelData.SwitchInfo
                                      .ratedImpulseWithstandVoltage.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>кВ</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6 form-label-space">
                              <div class="form-input-label position-initial">
                                {{
                                  modelData.OldSwitchInfo
                                    .enpro_secondary_voltage_kind_id.label
                                }}
                              </div>
                              <select-with-search
                                get-label="value"
                                @select="
                                  modelData.OldSwitchInfo.enpro_secondary_voltage_kind_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.OldSwitchInfo.enpro_secondary_voltage_kind_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="
                                  modelData.OldSwitchInfo
                                    .enpro_secondary_voltage_kind_id.value
                                "
                                get-model-name="SecondaryCircuitsVoltageKind"
                                get-id="SecondaryCircuitsVoltageKind"
                                get-title="SecondaryCircuitsVoltageKind"
                                get-url="all_kind/model/SecondaryCircuitsVoltageKind"
                                :disabled="!editing"
                              ></select-with-search>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{
                                  modelData.OldSwitchInfo.enproSecondaryVoltage
                                    .label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="enproSecondaryVoltage"
                                  v-model="
                                    modelData.OldSwitchInfo
                                      .enproSecondaryVoltage.value
                                  "
                                  :label="
                                    modelData.OldSwitchInfo
                                      .enproSecondaryVoltage.label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>В</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-input-label">
                                {{
                                  modelData.SwitchInfo.enproRatedPressure.label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="enproRatedPressure"
                                  v-model="
                                    modelData.SwitchInfo.enproRatedPressure
                                      .value
                                  "
                                  :label="
                                    modelData.SwitchInfo.enproRatedPressure
                                      .label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>Па</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6 form-label-space">
                              <div class="form-input-label">
                                {{
                                  modelData.SwitchInfo.enproInsulationLength
                                    .label
                                }}
                              </div>
                              <div class="input-group form-field">
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field text-field_v_2"
                                  name="enproInsulationLength"
                                  v-model="
                                    modelData.SwitchInfo.enproInsulationLength
                                      .value
                                  "
                                  :label="
                                    modelData.SwitchInfo.enproInsulationLength
                                      .label
                                  "
                                />
                                <div class="input-group-prepend pt-0">
                                  <span>См</span>
                                </div>
                              </div>
                            </div>


                            <!-- OldSwitchInfo -->
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div style="text-align: right; margin-bottom: 30px">
                            <button
                              type="button"
                              class="button bordered"
                              @click="funSave()"
                              :disabled="!editing"
                            >
                              Сохранить
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
  import {required} from "vuelidate/lib/validators";
  import modelEdit from "../../mixins/modelEdit";

  export default {
    name: "LoadBereakSwitchInfo_edit",
    mixins: [modelEdit],
    props: {
      getModelId: {
        type: Number,
      }, // id линии,
      titleOne: String,
      getModelName: {
        type: String,
      },
      // fromId: {
      //   type: Number,
      // },
    },
    data() {
      return {
        loading: false,
        isLoading: false,
        isSaving: false,
        errored: false,
        id: null,
        saveData: {
          AssetInfo: {
            CatalogAssetType: {
              IdentifiedObject: {
                name: null,
                names: [{name: null}],
              },
            },
          },
        },
        modelData: {
          AssetInfo: {
            CatalogAssetType: {
              IdentifiedObject: {
                name: null,
                id: null,
                names: [{name: null, id: null}],
              },
              id: null,
            },
            id: null,
          },
          SwitchInfo: {
            id: {
              value: null,
            },
            enpro_breaker_kind_id: {
              value: {id: null},
              label: "Принцип гашения дуги",
              type: "select",
            },
            ratedVoltage: {
              value: null,
              id: null,
              label: "Номинальное напряжение",
            },
            enproMaxVoltage: {
              value: null,
              id: null,
              label: "Наибольшее рабочее напряжение",
            },
            ratedFrequency: {
              value: null,
              id: null,
              label: "Номинальная частота",
            },
            ratedCurrent: {
              value: null,
              id: null,
              label: "Номинальный ток выключателя",
            },

            ratedImpulseWithstandVoltage: {
              value: null,
              id: null,
              label:
                "Номинальное импульсное напряжение полного грозового импульса относительно земли",
            },
            enproRatedPressure: {
              value: null,
              id: null,
              label: "Номинальное давление сжатого воздуха",
            },
            enproInsulationLength: {
              value: null,
              id: null,
              label: "Длина пути утечки внешней изоляции",
            },
            enpro_climatic_mod_placement_id: {
              value: {id: null},
              label: "Климатическое исполнение и категория размещения",
              type: "select",
            },
            enproTemperatureRange: {
              label: "Диапазон рабочих температур",
              type: "range",
              id: null,
              minTemperature: {
                value: null,
              },
              maxTemperature: {
                value: null,
              },
            },
            enpro_gost_id: {
              type: "select",
              label: "ГОСТ",
              value: {id: null},
            },
          },
          //Oldswitch
          OldSwitchInfo: {
            id: {
              value: null,
            },
            LoadBreakSwitchInfo: {
              value: 1,
              id: null,
            },
            poleCount: {
              value: null,
              label: "Количество полюсов",
            },

            withstandCurrent: {
              value: null,
              id: null,
              label: "Номинальный ток термической стойкости по ГОСТ 6827",
            },
            loadBreak: {
              value: true,
              label: "Выключатель нагрузки",
            },
            makingCapacity: {
              value: null,
              id: null,
              label: "Ток электродинамической стойкости",
            },
            enproWithstandCurrentDuration: {
              value: null,
              id: null,
              label: "Время протекания тока КЗ главных ножей",
            },
            enproEarthSwitchCurrentDuration: {
              value: null,
              id: null,
              label: "Время протекания тока КЗ заземлителей",
            },
            enpro_secondary_voltage_kind_id: {
              value: {id: null},
              label:
                "Номинальная частота питания вкл. и отк. устройств, вспом. цепей и цепей упр.",
              type: "select",
            },
            enproSecondaryVoltage: {
              value: null,
              id: null,
              label:
                "Номинальное напряжение цепей управления и вспомогательных цепей привода",
            },
            remote: {
              value: true,
              label: "Поддерживает дистанционное управление",
            },
          },
        },
        editing: false
      };
    },
    validations: {
      modelData: {
        AssetInfo: {
          CatalogAssetType: {
            IdentifiedObject: {
              name: {
                required,
              },
              names: {
                $each: {
                  name: {
                    required,
                  },
                },
              },
            },
          },
        },
      },
    },
    computed: {
      recordTitle() {
        let recordName =
          this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name;
        if (this.getModelId > 0) {
          return recordName ? " - " + recordName : "Редактирование";
        } else if (this.fromId > 0) {
          return recordName
            ? "Редактирование - " + recordName + "(Копия)"
            : "Редактирование (Копия)";
        } else {
          return recordName ? "Создание - " + recordName : "Создание";
        }
      },
      pagination() {
        let result = localStorage.getItem('table_pagination')
        return result ? result : ''
      },
      fromId() {
        let url = new URL(window.location.href);
        return url.searchParams.get("fromId") ? url.searchParams.get("fromId") : 0
      }
    },
    methods: {
      loadStatus(val) {
        this.loading = val;
      },
      async funLoad(getModelId) {
        this.loading = true;
        this.errored = false;
        await axios
          .get(`/api/modelName/${this.getModelName}/switchInfo/${getModelId}`)
          .then((response) => {
            const DATA = response.data.data;
            this.modelData.AssetInfo = DATA.AssetInfo;
            console.log(DATA);
            if (this.fromId > 0) {
              this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name +=
                " (Копия)";
            }
            this.modelData.OldSwitchInfo.LoadBreakSwitchInfo.id =
              DATA.OldSwitchInfo.LoadBreakSwitchInfo.id;
            this.modelData.SwitchInfo.enpro_breaker_kind_id.value = _.get(
              DATA.enproBreakerKind,
              ["id"],
              null
            )
              ? DATA.enproBreakerKind
              : {id: null};
            this.modelData.OldSwitchInfo.poleCount.value =
              DATA.OldSwitchInfo.poleCount;

            this.modelData.OldSwitchInfo.withstandCurrent.id = _.get(
              DATA.OldSwitchInfo.withstandCurrent,
              ["id"],
              null
            );
            this.modelData.SwitchInfo.ratedCurrent.value = _.get(
              DATA.ratedCurrent,
              ["value"],
              null
            );
            this.modelData.SwitchInfo.ratedCurrent.id = _.get(
              DATA.ratedCurrent,
              ["id"],
              null
            );
            this.modelData.OldSwitchInfo.withstandCurrent.value = _.get(
              DATA.OldSwitchInfo.withstandCurrent,
              ["value"],
              null
            );

            this.modelData.OldSwitchInfo.makingCapacity.value = _.get(
              DATA.OldSwitchInfo.makingCapacity,
              ["value"],
              null
            );
            this.modelData.OldSwitchInfo.makingCapacity.id = _.get(
              DATA.OldSwitchInfo.makingCapacity,
              ["id"],
              null
            );

            this.modelData.OldSwitchInfo.enproWithstandCurrentDuration.value =
              _.get(
                DATA.OldSwitchInfo.enproWithstandCurrentDuration,
                ["value"],
                null
              );
            this.modelData.OldSwitchInfo.enproWithstandCurrentDuration.id = _.get(
              DATA.OldSwitchInfo.enproWithstandCurrentDuration,
              ["id"],
              null
            );

            this.modelData.OldSwitchInfo.enproEarthSwitchCurrentDuration.value =
              _.get(
                DATA.OldSwitchInfo.enproEarthSwitchCurrentDuration,
                ["value"],
                null
              );
            this.modelData.OldSwitchInfo.enproEarthSwitchCurrentDuration.id =
              _.get(
                DATA.OldSwitchInfo.enproEarthSwitchCurrentDuration,
                ["id"],
                null
              );

            this.modelData.OldSwitchInfo.enpro_secondary_voltage_kind_id.value =
              _.get(DATA.OldSwitchInfo.enproSecondaryVoltageKind, ["id"], null)
                ? DATA.OldSwitchInfo.enproSecondaryVoltageKind
                : {id: null};

            this.modelData.OldSwitchInfo.enproSecondaryVoltage.value = _.get(
              DATA.OldSwitchInfo.enproSecondaryVoltage,
              ["value"],
              null
            );
            this.modelData.OldSwitchInfo.enproSecondaryVoltage.id = _.get(
              DATA.OldSwitchInfo.enproSecondaryVoltage,
              ["id"],
              null
            );

            this.modelData.OldSwitchInfo.remote.value = DATA.OldSwitchInfo.remote;

            this.modelData.OldSwitchInfo.id.value = DATA.OldSwitchInfo.id;
            console.log(1);

            this.modelData.SwitchInfo.enpro_climatic_mod_placement_id.value =
              _.get(DATA.enproClimaticModPlacement, ["id"], null)
                ? DATA.enproClimaticModPlacement
                : {id: null};

            console.log(2);
            this.modelData.SwitchInfo.enpro_gost_id.value = _.get(
              DATA.enproGost,
              ["id"],
              null
            )
              ? DATA.enproGost
              : {id: null};

            this.modelData.SwitchInfo.enproInsulationLength.value = _.get(
              DATA.enproInsulationLength,
              ["value"],
              null
            );
            console.log(3);
            this.modelData.SwitchInfo.enproInsulationLength.id = _.get(
              DATA.enproInsulationLength,
              ["id"],
              null
            );

            this.modelData.SwitchInfo.enproRatedPressure.value = _.get(
              DATA.enproRatedPressure,
              ["value"],
              null
            );
            this.modelData.SwitchInfo.enproRatedPressure.id = _.get(
              DATA.enproRatedPressure,
              ["id"],
              null
            );

            this.modelData.SwitchInfo.enproTemperatureRange.id = _.get(
              DATA.enproTemperatureRange,
              ["id"],
              null
            );
            console.log(4);
            this.modelData.SwitchInfo.enproTemperatureRange.minTemperature =
              _.get(DATA.enproTemperatureRange.minTemperature, ["id"], null)
                ? DATA.enproTemperatureRange.minTemperature
                : {value: null};

            this.modelData.SwitchInfo.enproTemperatureRange.maxTemperature =
              _.get(DATA.enproTemperatureRange.maxTemperature, ["id"], null)
                ? DATA.enproTemperatureRange.maxTemperature
                : {value: null};

            this.modelData.SwitchInfo.id.value = DATA.id;

            console.log(5);

            this.modelData.SwitchInfo.ratedFrequency.value = _.get(
              DATA.ratedFrequency,
              ["value"],
              null
            );
            this.modelData.SwitchInfo.ratedFrequency.id = _.get(
              DATA.ratedFrequency,
              ["id"],
              null
            );
            console.log(6);

            this.modelData.SwitchInfo.ratedVoltage.value = _.get(
              DATA.ratedVoltage,
              ["value"],
              null
            );
            this.modelData.SwitchInfo.ratedVoltage.id = _.get(
              DATA.ratedVoltage,
              ["id"],
              null
            );
            this.modelData.SwitchInfo.enproMaxVoltage.value = _.get(
              DATA.enproMaxVoltage,
              ["value"],
              null
            );
            this.modelData.SwitchInfo.enproMaxVoltage.id = _.get(
              DATA.enproMaxVoltage,
              ["id"],
              null
            );

            this.modelData.SwitchInfo.ratedImpulseWithstandVoltage.value = _.get(
              DATA.ratedImpulseWithstandVoltage,
              ["value"],
              null
            );
            this.modelData.SwitchInfo.ratedImpulseWithstandVoltage.id = _.get(
              DATA.ratedImpulseWithstandVoltage,
              ["id"],
              null
            );

            // сообщение пользователю
            toastr.success("Данные успешно загружены...");
          })
          .catch((error) => {
            this.errored = true;
            // для отладки
            console.log("Ошибка при загрузке данных WireInfo");

            // сообщение пользователю
            toastr.error("Ошибка при загрузке данных...");
          })
          .finally(() => {
            // финальная обработка
            // признаки
            this.loading = false;
          });
      },
      async funSave() {
        this.$v.$touch();

        // stop here if form is invalid
        if (this.$v.$invalid) {
          toastr.error("Ошибка при заполнений формы...");
          return;
        }
        let method = "POST";
        let url = `/api/modelName/${this.getModelName}/switchInfo`;
        this.saveData = {
          ...this.filterValue(this.modelData.SwitchInfo),
        };
        _.set(
          this.saveData,
          ["AssetInfo", "CatalogAssetType", "IdentifiedObject", "name"],
          this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name
        );
        _.set(
          this.saveData,
          [
            "AssetInfo",
            "CatalogAssetType",
            "IdentifiedObject",
            "names",
            "0",
            "name",
          ],
          this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.names[0].name
        );
        this.saveData.OldSwitchInfo = {
          ...this.filterValue(this.modelData.OldSwitchInfo),
        };
        if (this.getModelId > 0) {
          url += `/${this.getModelId}`;
          method = "PUT";
          this.saveData.AssetInfo = this.modelData.AssetInfo;
        } else if (this.id > 0) {
          url += `/${this.id}`;
          method = "PUT";
          this.saveData.AssetInfo = this.modelData.AssetInfo;
        }
        if (this.id !== null) {
          this.saveData.id = this.id;
        }

        toastr.info("Начался процесс сохранения данных...");
        console.log(this.saveData);
        await axios({
          method: method,
          url: url,
          data: this.fromId
            ? this.filterCopiedRecord(this.saveData)
            : this.saveData,
        })
          .then((response) => {
            this.id = response.data.data.id;
            console.log(response);
            toastr.success("Данные успешно сохранены...");
          })
          .catch((error) => {
            if (error.response) {
              if (error.response.status === 422) {
                let t = toastr.options.timeOut;
                this.$v.$touch();
                for (const ERRORKEY in error.response.data.errors) {
                  error.response.data.errors[ERRORKEY].map((item) => {
                    toastr.options.timeOut = 5000;
                    toastr.error(item);
                  });
                }
                toastr.options.timeOut = t;
              }
            } else {
              this.errored = true;
              toastr.error("Ошибка при сохранении данных...");
            }
          })
          .finally(() => {
            this.loading = false;
          });
      },
      filterValue(data) {
        const res = {};
        for (const key in data) {
          if (key === "enproTemperatureRange") {
            res[key] = {};

            if (_.get(data[key], ["id"], null) !== null) {
              res[key].id = data[key].id;
            }
            res[key].minTemperature = {};
            res[key].maxTemperature = {};
            if (_.get(data[key].minTemperature, ["id"], null) !== null) {
              res[key].minTemperature.id = data[key].minTemperature.id;
            }
            res[key].minTemperature.value = _.get(
              data[key].minTemperature,
              ["value"],
              null
            );

            if (_.get(data[key].maxTemperature, ["id"], null) !== null) {
              res[key].maxTemperature.id = data[key].maxTemperature.id;
            }
            res[key].maxTemperature.value = _.get(
              data[key].maxTemperature,
              ["value"],
              null
            );
            continue;
          }

          if (
            key === "poleCount" ||
            key === "remote" ||
            key === "id" ||
            key === "loadBreak" ||
            key === "isUnganged" ||
            key === "isSinglePhase"
          ) {
            console.log(key);
            if (data[key].value !== null) {
              res[key] = data[key].value;
            } else {
              res[key] = null;
            }
          } else {
            res[key] = {};
            if (data[key].type === "select") {
              console.log("select");
              console.log(key);
              res[key] = data[key].value.id ? data[key].value.id : null;
            } else {
              if (key) {
                res[key].value = data[key].value;
              }
              console.log("idd");
              if (data[key].id !== null) {
                res[key].id = data[key].id;
              }
            }
          }
        }
        return res;
      },
    },
    mounted() {
      if (this.getModelId > 0) {
        // id есть - это не новая модель
        // функция загрузки
        this.funLoad(this.getModelId);
      } else {
        this.editing = true
        if (this.fromId > 0) {
          this.funLoad(this.fromId);
        }
      }
    },
  };
</script>
<style>
.text-field_v_2 {
  height: auto !important;
  padding: 13px 20px !important;
}

.row-gap-18 {
  row-gap: 18px;
}

.form-field-2-column.row-gap .form-field {
  margin-top: 0px !important;
}

.form-input-label {
  font-weight: 300;
  font-size: 12px;
  line-height: 16px;
  z-index: 10;
  color: #f1f1f1;
  transition: all 0.3s;
  pointer-events: none;
}

.form-label-space {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
</style>