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
          <a :href="`/admin/${modelRoute[getModelName]}`">{{ titleOne }}</a>
        </li>
        <li class="breadcrumb-item active">
          {{
            getModelId
              ? `Редактирование ${
                  modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name &&
                  "- " +
                    modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name
                }`
              : `Создание - ${
                  modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name &&
                  modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name !==
                    ""
                    ? modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name
                    : titleOne
                }`
          }}
        </li>
      </ol>

      <!-- действия на странице -->
      <div class="page-header-actions">
        <!-- кнопка выход -->
        <!-- <a href="/admin/wire_info" class="button"> Сохранить </a>
        <a href="/admin/wire_info" class="button"> Изменить </a> -->
        <button v-if="!editing" @click="editing = true" class="button">
          Редактировать
        </button>
        <a :href="`/admin/${modelRoute[getModelName]}`" class="button">
          Закрыть
        </a>
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
                  v-on:click="funLoadAll"
                />
              </div>

              <div class="example-wrap">
                <div class="nav-tabs-horizontal" data-plugin="tabs">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link active"
                        data-toggle="tab"
                        href="#tabMain"
                        aria-controls="tabMain"
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
                        Технические данные по фазам
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content pt-20">
                    <!-- вкладка Основные технические данные -->
                    <div class="tab-pane active" id="tabMain" role="tabpanel">
                      <div class="row">
                        <div class="col-md-10">
                          <div class="row form-field-2-column">
                            <div class="form-field col-6">
                              <div class="form-input-label">
                                Наименование марки оборудования
                              </div>
                              <input
                                :readonly="!editing"
                                type="text"
                                class="text-field"
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
                                placeholder="Наименование марки оборудования"
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
                            <div
                              v-if="
                                modelData.AssetInfo.CatalogAssetType
                                  .IdentifiedObject.names.length > 0
                              "
                              class="form-field col-6 mt-0"
                            >
                              <div class="form-input-label">
                                Короткое наименование
                              </div>
                              <input
                                :readonly="!editing"
                                type="text"
                                class="text-field"
                                :class="{
                                  'is-invalid-borders':
                                    $v.modelData.AssetInfo.CatalogAssetType
                                      .IdentifiedObject.names.$each[0].name
                                      .$error,
                                }"
                                name="shortModelName"
                                v-model.trim="
                                  $v.modelData.AssetInfo.CatalogAssetType
                                    .IdentifiedObject.names.$each[0].name.$model
                                "
                                placeholder="Короткое наименование"
                              />
                              <div
                                v-if="
                                  $v.modelData.AssetInfo.CatalogAssetType
                                    .IdentifiedObject.names.$each[0].name.$error
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
                      <hr
                        style="
                          background-color: #273661;
                          height: 1px;
                          border: 0;
                        "
                      />
                      <div class="row">
                        <div class="col-md-10">
                          <div class="row form-field-2-column">
                            <!-- номинальное напряжение -->
                            <div
                              class="form-field col-md-6"
                              v-if="modelData.nominalVoltage.display"
                            >
                              <div class="input-group">
                                <div class="form-input-label">
                                  {{ modelData.nominalVoltage.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="nominalVoltage"
                                  v-model.number="modelData.nominalVoltage.value"
                                  :placeholder="
                                    modelData.nominalVoltage.placeholder
                                  "
                                />
                                <div class="input-group-prepend">
                                  <span>kB</span>
                                </div>
                              </div>
                            </div>
                            <!-- Конструктивное исполнение -->
                            <div
                              class="form-field col-md-6"
                              v-if="
                                modelData.cableInfo.construction_kind_id.display
                              "
                            >
                              <div
                                class="form-input-label position-initial mb-3"
                              >
                                {{
                                  modelData.cableInfo.construction_kind_id
                                    .placeholder
                                }}
                              </div>
                              <select-with-search
                                :disabled="!editing"
                                get-label="ru_value"
                                @select="
                                  modelData.cableInfo.construction_kind_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.cableInfo.construction_kind_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="
                                  modelData.cableInfo.construction_kind_id.value
                                "
                                :get-model-name="
                                  getKindModels.constructionKind.modelName
                                "
                                :get-id="
                                  getKindModels.constructionKind.modelName
                                "
                                :get-title="
                                  getKindModels.constructionKind.title
                                "
                                :get-url="
                                  'all_kind/model/' +
                                  getKindModels.constructionKind.modelName
                                "
                              ></select-with-search>
                            </div>
                            <!-- Тип брони -->
                            <div
                              class="form-field col-md-6"
                              v-if="
                                modelData.cableInfo.shield_material_id.display
                              "
                            >
                              <div
                                class="form-input-label position-initial mb-3"
                              >
                                {{
                                  modelData.cableInfo.shield_material_id
                                    .placeholder
                                }}
                              </div>
                              <select-with-search
                                :disabled="!editing"
                                get-label="ru_value"
                                @select="
                                  modelData.cableInfo.shield_material_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.cableInfo.shield_material_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="
                                  modelData.cableInfo.shield_material_id.value
                                "
                                :get-model-name="
                                  getKindModels.shieldMaterial.modelName
                                "
                                :get-id="getKindModels.shieldMaterial.modelName"
                                :get-title="getKindModels.shieldMaterial.title"
                                :get-url="
                                  'all_kind/model/' +
                                  getKindModels.shieldMaterial.modelName
                                "
                              ></select-with-search>
                            </div>
                            <!-- Материал наружной оболочки -->
                            <div
                              class="form-field col-md-6"
                              v-if="
                                modelData.cableInfo.outer_jacket_kind_id.display
                              "
                            >
                              <div class="form-input-label position-initial">
                                {{
                                  modelData.cableInfo.outer_jacket_kind_id
                                    .placeholder
                                }}
                              </div>
                              <select-with-search
                                :disabled="!editing"
                                get-label="ru_value"
                                @select="
                                  modelData.cableInfo.outer_jacket_kind_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.cableInfo.outer_jacket_kind_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="
                                  modelData.cableInfo.outer_jacket_kind_id.value
                                "
                                :get-model-name="
                                  getKindModels.outerJacketKind.modelName
                                "
                                :get-id="
                                  getKindModels.outerJacketKind.modelName
                                "
                                :get-title="getKindModels.outerJacketKind.title"
                                :get-url="
                                  'all_kind/model/' +
                                  getKindModels.outerJacketKind.modelName
                                "
                              ></select-with-search>
                            </div>
                            <!-- материал провода, жилы -->

                            <!-- исполнение по пожароопасности -->
                            <div
                              class="form-field col-md-6"
                              v-if="modelData.cableInfo.fire_safety_id.display"
                            >
                              <div class="form-input-label position-initial">
                                {{
                                  modelData.cableInfo.fire_safety_id.placeholder
                                }}
                              </div>
                              <select-with-search
                                :disabled="!editing"
                                get-label="ru_value"
                                @select="
                                  modelData.cableInfo.fire_safety_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.cableInfo.fire_safety_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-model-name="
                                  getKindModels.fireSafety.modelName
                                "
                                :get-value="
                                  modelData.cableInfo.fire_safety_id.value
                                "
                                :get-id="getKindModels.fireSafety.modelName"
                                :get-title="getKindModels.fireSafety.title"
                                :get-url="
                                  'all_kind/model/' +
                                  getKindModels.fireSafety.modelName
                                "
                              ></select-with-search>
                            </div>
                            <!-- нормативный срок службы -->
                            <div
                              class="form-field col-md-6"
                              v-if="modelData.standardServiceLife.display"
                            >
                              <div class="input-group">
                                <div class="form-input-label">
                                  {{
                                    modelData.standardServiceLife.placeholder
                                  }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="standardServiceLife"
                                  v-model.number="
                                    modelData.standardServiceLife.value.years
                                  "
                                  :placeholder="
                                    modelData.standardServiceLife.placeholder
                                  "
                                />
                                <div class="input-group-prepend">
                                  <span>лет</span>
                                </div>
                              </div>
                            </div>
                            <!---Материал изоляций --->
                            <div class="form-field col-md-6">
                              <div class="form-input-label position-initial">
                                {{
                                  modelData.insulation_material_id.placeholder
                                }}
                              </div>
                              <select-with-search
                                :disabled="!editing"
                                get-label="ru_value"
                                @select="
                                  modelData.insulation_material_id.value =
                                    $event
                                "
                                @remove="
                                  modelData.insulation_material_id.value =
                                    $event
                                "
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-model-name="
                                  getKindModels.insulationMaterial.modelName
                                "
                                :get-value="
                                  modelData.insulation_material_id.value
                                "
                                :get-id="
                                  getKindModels.insulationMaterial.modelName
                                "
                                :get-title="
                                  getKindModels.insulationMaterial.title
                                "
                                :get-url="
                                  'all_kind/model/' +
                                  getKindModels.insulationMaterial.modelName
                                "
                              ></select-with-search>
                            </div>

                            <!--масса провода --->
                            <div class="form-field col-md-6">
                              <div class="input-group">
                                <div class="form-input-label">
                                  {{
                                    modelData.enproWeightPerLength.placeholder
                                  }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="enproWeightPerLength"
                                  v-model.number="modelData.enproWeightPerLength.value"
                                  placeholder=""
                                />
                                <div class="input-group-prepend">
                                  <span class="text-nowrap">кг/км</span>
                                </div>
                              </div>
                            </div>
                            <div
                                class="form-field col-md-6"
                                v-if="getModelName === 'CableInfo'"
                              >
                                <div class="input-group">
                                  <div class="form-input-label">
                                    {{ modelData.cableInfo.diameterOverJacket.placeholder }}
                                  </div>
                                  <input
                                    :readonly="!editing"
                                    type="number"
                                    class="text-field"
                                    name="insulationThickness"
                                    v-model.number="modelData.cableInfo.diameterOverJacket.value"
                                    :placeholder="modelData.cableInfo.diameterOverJacket.placeholder"
                                  />
                                  <div class="input-group-prepend">
                                    <span>мм</span>
                                  </div>
                                </div>
                              </div>
                            <!--ГОСТ --->

                            <div class="form-field col-md-6">
                              <div class="form-input-label position-initial">
                                {{ modelData.gost_id.placeholder }}
                              </div>
                              <select-with-search
                                :disabled="!editing"
                                get-url="gost"
                                get-label="name"
                                @select="modelData.gost_id.value = $event"
                                @remove="modelData.gost_id.value = $event"
                                @loading="loadStatus"
                                @loadingDone="loadStatus"
                                :get-value="modelData.gost_id.value"
                                get-model-name="gost"
                                get-id="gost"
                                get-title="ГОСТ"
                              ></select-with-search>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Технические данные по фазам -->
                    <div class="tab-pane" id="techDesc" role="tabpanel">
                      <div class="row">
                        <div class="col-md-10">
                          <div
                            class=""
                            v-for="(property, index) in modelData.WirePhaseInfo"
                            :key="index"
                          >
                            <div class="border-bottom row form-field-2-column">
                              <div class="col-md-12 mt-15">
                                <button
                                  type="button"
                                  class="
                                    m-0
                                    btn btn-outline-danger btn-sm
                                    d-flex
                                    ml-auto
                                    border-0
                                  "
                                  @click="
                                    editing && deleteWireInfoProperty(index)
                                  "
                                >
                                  <span class="icon icon-close"></span>
                                </button>
                              </div>
                              <!--фаза -->
                              <div class="col-md-6">
                                <div class="form-field">
                                  <div class="form-input-label">
                                    {{ property.phaseInfo.placeholder }}
                                  </div>
                                  <select
                                    :disabled="!editing"
                                    name="phase"
                                    class="text-field"
                                    v-model="
                                      modelData.WirePhaseInfo[index].phaseInfo
                                        .value.id
                                    "
                                  >
                                    <option :value="null">Без фазы</option>
                                    <option
                                      v-for="item in phaseInfoKind"
                                      :key="item.id"
                                      :value="item.id"
                                    >
                                      <span>{{ item.literal }}</span>
                                    </option>
                                  </select>
                                </div>
                              </div>
                              <div
                                class="form-field col-md-6"
                                v-if="getModelName === 'CableInfo'"
                              >
                                <div class="form-input-label position-initial">
                                  {{ property.material_id.placeholder }}
                                </div>
                                <select-with-search
                                  :disabled="!editing"
                                  get-label="ru_value"
                                  @select="
                                    modelData.WirePhaseInfo[
                                      index
                                    ].material_id.value = $event
                                  "
                                  @remove="
                                    modelData.WirePhaseInfo[
                                      index
                                    ].material_id.value = $event
                                  "
                                  @loading="loadStatus"
                                  @loadingDone="loadStatus"
                                  :get-model-name="
                                    getKindModels.material.modelName
                                  "
                                  :get-title="getKindModels.material.title"
                                  :get-url="
                                    'all_kind/model/' +
                                    getKindModels.material.modelName
                                  "
                                  :get-id="
                                    getKindModels.material.modelName + index
                                  "
                                  :get-value="
                                    modelData.WirePhaseInfo[index].material_id
                                      .value
                                  "
                                >
                                </select-with-search>
                              </div>
                              <!-- Изолированный -->
                              <div class="form-field col-md-6">
                                <div class="form-input-label">
                                  {{ property.insulated.placeholder }}
                                </div>
                                <select
                                  :disabled="!editing"
                                  name="insulated"
                                  class="text-field"
                                  v-model="
                                    modelData.WirePhaseInfo[index].insulated
                                      .value
                                  "
                                >
                                  <option
                                    :selected="
                                      getModelName === 'CableInfo'
                                        ? true
                                        : false
                                    "
                                    :value="true"
                                  >
                                    <span> Да</span>
                                  </option>
                                  <template
                                    v-if="getModelName === 'OverheadWireInfo'"
                                  >
                                    <option :value="false">
                                      <span> Нет</span>
                                    </option>
                                  </template>
                                </select>
                              </div>
                              <!--Толщина изоляций -->
                              <div class="form-field col-md-6">
                                <div class="input-group">
                                  <div class="form-input-label">
                                    {{
                                      property.insulationThickness.placeholder
                                    }}
                                  </div>
                                  <input
                                    :readonly="!editing"
                                    type="number"
                                    class="text-field"
                                    name="insulationThickness"
                                    v-model.number="property.insulationThickness.value"
                                    :placeholder="
                                      property.insulationThickness.placeholder
                                    "
                                  />
                                  <div class="input-group-prepend">
                                    <span>мм</span>
                                  </div>
                                </div>
                              </div>
                              <div
                                class="form-field col-md-6"
                                v-if="getModelName === 'OverheadWireInfo'"
                              >
                                <div class="form-input-label position-initial">
                                  {{ property.material_id.placeholder }}
                                </div>
                                <select-with-search
                                  :disabled="!editing"
                                  get-label="ru_value"
                                  @select="property.material_id.value = $event"
                                  @remove="property.material_id.value = $event"
                                  @loading="loadStatus"
                                  @loadingDone="loadStatus"
                                  :get-value="property.material_id.value"
                                  :get-model-name="
                                    getKindModels.material.modelName
                                  "
                                  :get-id="getKindModels.material.modelName"
                                  :get-title="getKindModels.material.title"
                                  :get-url="
                                    'all_kind/model/' +
                                    getKindModels.material.modelName
                                  "
                                >
                                </select-with-search>
                              </div>
                              <!--сечение и для кабеля-->
                              <div class="form-field col-md-6">
                                <div class="form-input-label">
                                  {{ property.sizeDescription.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  class="text-field"
                                  type="text"
                                  name="sizeDescription"
                                  v-model.trim="
                                    modelData.WirePhaseInfo[index]
                                      .sizeDescription.value
                                  "
                                  placeholder="Cечение"
                                />
                              </div>
                              <!-- Количество проволок основного материала и для кабеля-->
                              <div class="form-field col-md-6">
                                <div class="form-input-label">
                                  {{ property.strandCount.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="strandCount"
                                  v-model.number="
                                    modelData.WirePhaseInfo[index].strandCount
                                      .value
                                  "
                                  :placeholder="
                                    property.strandCount.placeholder
                                  "
                                />
                              </div>
                              <!--Количество проволок в стальном сердечнике -->
                              <div
                                class="form-field col-md-6"
                                v-if="property.coreStrandCount.display"
                              >
                                <div class="form-input-label">
                                  {{ property.coreStrandCount.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="coreStrandCount"
                                  v-model.number="
                                    modelData.WirePhaseInfo[index]
                                      .coreStrandCount.value
                                  "
                                  :placeholder="
                                    property.coreStrandCount.placeholder
                                  "
                                />
                              </div>
                              <!-- Диаметр сердечника -->
                              <div
                                class="form-field col-md-6 input-group"
                                v-if="property.coreRadius.display"
                              >
                                <div class="form-input-label">
                                  {{ property.coreRadius.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="coreRadius"
                                  v-model.number="
                                    modelData.WirePhaseInfo[index].coreRadius
                                      .value
                                  "
                                  :placeholder="property.coreRadius.placeholder"
                                />
                                <div class="input-group-prepend">
                                  <span>мм</span>
                                </div>
                              </div>
                              <!-- Диаметр провода и для кабеля-->
                              <div class="form-field col-md-6 input-group">
                                <div class="form-input-label">
                                  {{ property.radius.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="radius"
                                  v-model.number="
                                    modelData.WirePhaseInfo[index].radius.value
                                  "
                                  :placeholder="property.radius.placeholder"
                                />
                                <div class="input-group-prepend">
                                  <span>мм</span>
                                </div>
                              </div>
                              <!-- Длительно допустимый ток и для кабеля-->
                              <div class="form-field col-md-6 input-group">
                                <div class="form-input-label">
                                  {{ property.ratedCurrent.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="ratedCurrent"
                                  v-model.number="
                                    modelData.WirePhaseInfo[index].ratedCurrent
                                      .value
                                  "
                                  :placeholder="
                                    property.ratedCurrent.placeholder
                                  "
                                />
                                <div class="input-group-prepend">
                                  <span>A</span>
                                </div>
                              </div>
                              <!-- Электрическое сопротивление постоянному току при  20°С и для кабеля -->
                              <div class="form-field col-md-6 input-group">
                                <div class="form-input-label">
                                  {{ property.rDC20.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  class="text-field"
                                  name="rDC20"
                                  v-model.number="
                                    modelData.WirePhaseInfo[index].rDC20.value
                                  "
                                  placeholder=""
                                />
                                <div class="input-group-prepend">
                                  <span class="text-nowrap">Ом/км</span>
                                </div>
                              </div>
                              <!-- // разрывное услие и для кабеля -->
                              <div class="form-field col-md-6 input-group">
                                <div class="form-input-label">
                                  {{ property.enproBreakForce.placeholder }}
                                </div>
                                <input
                                  :readonly="!editing"
                                  type="number"
                                  name="enproBreakForce"
                                  class="text-field"
                                  v-model.number="
                                    modelData.WirePhaseInfo[index]
                                      .enproBreakForce.value
                                  "
                                />
                                <div class="input-group-prepend">
                                  <span class="text-nowrap">kH</span>
                                </div>
                              </div>
                              <!-- поля кабелей -->
                              <div class="col-md-12 mb-15">
                                <div class="text-right">
                                  <button
                                    type="button"
                                    class="button bordered mt-15"
                                    @click="createWireProperty(index)"
                                    :disabled="!editing"
                                  >
                                    Копировать
                                  </button>
                                  <button
                                    type="button"
                                    class="button bordered mt-15 ml-15"
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
                        <div class="col-md-2">
                          <div style="text-align: right; margin-bottom: 30px">
                            <button
                              type="button"
                              class="button bordered"
                              @click="createWireProperty(null)"
                              :disabled="!editing"
                            >
                              Добавить
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
import { required } from "vuelidate/lib/validators";
import modelEdit from "../../mixins/modelEdit";
import transformerVue from '../svg_editor/components/elements/transformer.vue';

export default {
  name: "wireinfo_edit",
  mixins: [modelEdit],
  props: {
    getModelId: {
      type: Number,
    }, // id линии,
    titleOne: String,
    getModelName: {
      type: String,
    },
    getKindModels: {
      required: true,
    },
    // fromId: {
    //   type: Number,
    // },
  },
  data() {
    return {
      modelRoute: {
        CableInfo: "cable_info",
        OverheadWireInfo: "overhead_wire_info",
      },
      saveData: {
        AssetInfo: {
          CatalogAssetType: {
            IdentifiedObject: {
              name: null,
            },
          },
        },
        WirePhaseInfo: [],
      },
      loading: false,
      isLoading: false,
      errored: false,
      sq: { model: null, value: null },
      //списки для кабелей

      phaseInfoKind: [],
      id: null,
      modelData: {
        AssetInfo: {
          CatalogAssetType: {
            IdentifiedObject: {
              name: null,
              id: null,
              names: [
                {
                  name: null,
                },
              ],
            },
            id: null,
          },
          id: null,
        },
        // type: { value: null },

        OverheadWireInfo: {
          value: 1,
          display: true,
        },
        WirePhaseInfo: [],
        insulation_material_id: {
          value: { id: null },
          placeholder: "Материал изоляции",
          display: true,
          type: "select",
        },
        gost_id: {
          value: { id: null },
          placeholder: "ГОСТ",
          display: true,
          id: null,
          type: "select",
        },
        enproWeightPerLength: {
          value: null,
          placeholder: "Масса 1 км провода",
          display: true,
          id: null,
        },

        //поля и данные для кабелей
        // тип селект
        nominalVoltage: {
          value: null,
          placeholder: "Номинальное напряжение",
          display: true,
          id: null,
        },
        standardServiceLife: {
          value: { years: null },
          id: null,
          placeholder: "Нормативный срок службы",
          display: false,
        },
        cableInfo: {
          construction_kind_id: {
            id: null,
            value: { id: null },
            placeholder: "Конструктивное исполнение токопроводящих жил",
            display: false,
            type: "select",
          },
          diameterOverJacket: {
            id: null,
            placeholder: "Внешний диаметр кабеля",
            value: null,
            display: true
          },
          shield_material_id: {
            id: null,
            value: { id: null },
            placeholder: "Материал экрана кабеля",
            display: false,
            type: "select",
          },
          outer_jacket_kind_id: {
            id: null,
            value: { id: null },
            placeholder: "Материал наружной оболочки",
            display: false,
            type: "select",
          },
          fire_safety_id: {
            value: { id: null },
            placeholder: "Исполнение по пожароопасности",
            display: false,
            type: "select",
          },
        },
      },
      editing: false,
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
                  required: false,
                },
              },
            },
          },
        },
      },
      // WirePhaseInfo: {
      //   $each: {
      //     phaseInfo: {
      //       value: {
      //         id: {
      //           required,
      //         },
      //       },
      //     },
      //   },
      // },
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
    fromId() {
      let url = new URL(window.location.href);
      return url.searchParams.get("fromId")
        ? url.searchParams.get("fromId")
        : 0;
    },
  },
  mounted() {
    this.filterModelData(this.getModelName);
  },
  created: function () {
    this.funLoadAll();
  },
  methods: {
    loadStatus(val) {
      this.loading = val;
    },
    addWireProperty(
      material = { id: null },
      id = null,
      wpiID = null,
      owId = 0,
      phase = null,
      ins = true,
      sd = null,
      sc = null,
      csc = null,
      cr = {
        id: null,
        value: null,
      },
      r = {
        id: null,
        value: null,
      },
      rc = {
        id: null,
        value: null,
      },
      rdc20 = { id: null, value: null },
      ebf = { id: null, value: null },
      cableInfoId = null,
      insTH = { id: null, value: null }
    ) {
      const overheadModel = {
        material_id: {
          value: material ? material : { id: null },
          placeholder: "Материал",
          display: true,
          type: "select",
        },
        wpiID: {
          value: { id: wpiID },
          display: false,
          type: "select",
        },
        id: {
          value: id,
          display: true,
        },
        OverheadWireInfo: {
          value: { id: owId },
          display: true,
        },
        phaseInfo: {
          value: { id: _.get(phase, ["id"], null) },
          placeholder: "Фаза",
          display: false,
          type: "select",
        },
        insulated: {
          value: ins,
          placeholder: "Изолированный",
          display: true,
        },
        sizeDescription: {
          value: sd,
          placeholder: "Сечение(основное/сердечника), кв.мм",
          display: true,
        },
        strandCount: {
          value: sc,
          placeholder: "Количество проволок основного материала",
          display: true,
        },
        coreStrandCount: {
          value: csc,
          placeholder: "Количество проволок в стальном сердечнике",
          display: true,
        },
        coreRadius: {
          value: _.get(cr, ["value"], null),
          placeholder: "Диаметр сердечника",
          display: true,
          id: _.get(cr, ["id"], null),
        },
        radius: {
          value: _.get(r, "value", null),
          placeholder: "Диаметр провода",
          display: true,
          id: _.get(r, ["id"], null),
        },
        ratedCurrent: {
          value: _.get(rc, ["value"], null),
          placeholder: "Длительно допустимый ток",
          display: true,
          id: _.get(rc, ["id"], null),
        },
        rDC20: {
          value: _.get(rdc20, ["value"], null),
          placeholder: "Электрическое сопротивление постоянному току при  20°С",
          display: true,
          id: _.get(rdc20, ["id"], null),
        },
        enproBreakForce: {
          value: _.get(ebf, ["value"], null),
          placeholder: "Разрывное усилие провода",
          display: true,
          id: _.get(ebf, ["id"], null),
        },
        cableInfoId: {
          id: _.get(cableInfoId, ["id"], null),
          display: false,
        },
        insulationThickness: {
          value: _.get(insTH, ["value"], null),
          placeholder: "Толщина изоляции",
          display: true,
          id: _.get(insTH, ["id"], null),
        },
      };
      if (this.getModelName === "CableInfo") {
        overheadModel.sizeDescription.placeholder = "Сечение жилы, кв.мм";
        overheadModel.strandCount.placeholder = "Количество проволок в жиле";
        overheadModel.radius.placeholder = "Диаметр жилы";
        overheadModel.radius.placeholder = "Диаметр жилы";
        overheadModel.OverheadWireInfo.display = false;
        overheadModel.insulationThickness.placeholder = "Толщина изоляций жилы";
      }

      this.modelData.WirePhaseInfo.push(overheadModel);
      console.log(overheadModel);
    },
    createWireProperty(index = null) {
      if (this.modelData.WirePhaseInfo.length < 6) {
        console.log(index);
        if (index !== null) {
          const data = this.modelData.WirePhaseInfo[index];
          console.log(data);
          this.addWireProperty(
            data.material_id.value,
            null,
            null,
            0,
            null,
            data.insulated.value,
            data.sizeDescription.value,
            data.strandCount.value,
            data.coreStrandCount.value,
            { value: data.coreRadius.value },
            { value: data.radius.value },
            { value: data.ratedCurrent.value },
            { value: data.rDC20.value },
            { value: data.enproBreakForce.value },
            null,
            { value: data.insulationThickness.value }
          );
        } else {
          this.addWireProperty();
        }
      } else {
        toastr.info("Больше не сможете добавить свойств");
      }
    },
    deleteWireInfoProperty(index) {
      if (confirm("Удалить данный запись ?")) {
        this.modelData.WirePhaseInfo.splice(index, 1);
        this.funSave().then((res) => {
          toastr.info("Запись удален");
        });
      }
    },
    funLoadAll() {
      this.loadSinglePhaseKind();
      if (this.getModelId > 0) {
        this.funLoad(this.getModelId);
      } else {
        this.editing = true;
        if (this.fromId > 0) {
          this.funLoad(this.fromId);
        } else {
          this.addWireProperty();
        }
      }
    },

    filterModelData(modelName) {
      if (modelName === "CableInfo") {
        this.modelData.OverheadWireInfo.display = false;
        this.modelData.nominalVoltage.display = true;
        this.modelData.cableInfo.construction_kind_id.display = true;
        this.modelData.cableInfo.shield_material_id.display = true;
        this.modelData.cableInfo.outer_jacket_kind_id.display = true;
        this.modelData.cableInfo.fire_safety_id.display = true;
        this.modelData.standardServiceLife.display = true;
        this.modelData.enproWeightPerLength.placeholder = "Масса 1 км";
      }
    },
    async funSave() {
      this.$v.$touch();

      // stop here if form is invalid
      if (this.$v.$invalid) {
        toastr.error("Ошибка при заполнений формы...");
        return;
      }

      let method = "POST";
      let url = `/api/modelName/${this.getModelName}/wireAssemblyInfo`;
      this.saveData.AssetInfo.CatalogAssetType.IdentifiedObject.name =
        this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name;
      this.saveData.AssetInfo.CatalogAssetType.IdentifiedObject.names =
        this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.names;

      if (this.getModelId > 0) {
        url += `/${this.getModelId}`;
        method = "PUT";
        this.saveData.AssetInfo = this.modelData.AssetInfo;
      } else if (this.id > 0) {
        url += `/${this.id}`;
        method = "PUT";
        this.saveData.AssetInfo = this.modelData.AssetInfo;
      }
      const ctx = this;

      this.saveData.WirePhaseInfo = [];
      if (this.id !== null) {
        this.saveData.id = this.id;
      }
      if (this.modelData.WirePhaseInfo.length > 0) {
        this.modelData.WirePhaseInfo.map(function (item, index) {
          const val = {
            phase_info_id: _.get(item.phaseInfo.value, ["id"], null),
            WireInfo: {
              ...ctx.filterValue(ctx.modelData),
              ...ctx.filterValue(item),
            },
          };
          if (ctx.getModelName === "CableInfo") {
            val.WireInfo.CableInfo = {
              id: item.cableInfoId.id,
              ...ctx.filterValue(ctx.modelData.cableInfo),
            };
          }
          if (item.wpiID.value.id !== null) {
            val.id = item.wpiID.value.id;
          }
          ctx.saveData.WirePhaseInfo.push(val);
        });
      }

      toastr.info("Начался процесс сохранения данных...");
      console.log(
        this.fromId ? this.filterCopiedRecord(this.saveData) : this.saveData
      );
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
        if (data[key].display === true) {
          if (key === "WirePhaseInfo" || key === "DOJ") {
            continue;
          }
          if (key === "standardServiceLife") {
            res[key] = {};
            res[key].value = data[key].value;
            continue;
          }
          if (
            key === "name" ||
            key === "type" ||
            key === "insulated" ||
            key === "sizeDescription" ||
            key === "strandCount" ||
            key === "sizeDescription" ||
            key === "coreStrandCount" ||
            key === "id" ||
            key === "names"
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
              if (data[key].id !== null) {
                res[key].id = data[key].id;
              }
            }
          }
        }
      }
      return res;
    },
    async funLoad(getModelId) {
      this.loading = true;
      this.errored = false;
      await axios
        .get(
          `/api/modelName/${this.getModelName}/wireAssemblyInfo/${getModelId}`
        )
        .then((response) => {
          const DATA = response.data.data;
          this.modelData.AssetInfo = DATA.AssetInfo;
          if (this.fromId === 0) {
            this.id = DATA.id;
          }
          console.log(1111111111);
          const ctx = this;
          if (DATA.WirePhaseInfo.length > 0) {
            DATA.WirePhaseInfo.map((item) => {
              console.log("item", item.WireInfo.CableInfo?.diameterOverJacket);
              ctx.addWireProperty(
                item.WireInfo.material,
                item.WireInfo.id,
                item.id,
                item.WireInfo.OverheadWireInfo,
                item.phaseInfo,

                item.WireInfo.insulated,
                item.WireInfo.sizeDescription,

                item.WireInfo.strandCount,

                item.WireInfo.coreStrandCount,
                item.WireInfo.coreRadius,
                item.WireInfo.radius,
                item.WireInfo.ratedCurrent,
                item.WireInfo.rDC20,
                item.WireInfo.enproBreakForce,
                item.WireInfo.CableInfo,
                item.WireInfo.insulationThickness
              );
              console.log(222222222);
            });

            console.log(1212);
            console.log(111);

            this.modelData.nominalVoltage.value =
              DATA.WirePhaseInfo[0].WireInfo.nominalVoltage.value;

            this.modelData.nominalVoltage.id =
              DATA.WirePhaseInfo[0].WireInfo.nominalVoltage.id;

            this.modelData.insulation_material_id.value = DATA.WirePhaseInfo[0]
              .WireInfo.insulationMaterial
              ? DATA.WirePhaseInfo[0].WireInfo.insulationMaterial
              : { id: null };

            console.log("2");
            this.modelData.enproWeightPerLength.value =
              DATA.WirePhaseInfo[0].WireInfo.enproWeightPerLength.value;

            this.modelData.enproWeightPerLength.id =
              DATA.WirePhaseInfo[0].WireInfo.enproWeightPerLength.id;
            console.log("3");

            this.modelData.gost_id.value = DATA.WirePhaseInfo[0].WireInfo
              .enproGost
              ? DATA.WirePhaseInfo[0].WireInfo.enproGost
              : { id: null };

            if (this.getModelName === "CableInfo") {
              this.modelData.cableInfo.construction_kind_id.value = DATA
                .WirePhaseInfo[0].WireInfo.CableInfo.constructionKind
                ? DATA.WirePhaseInfo[0].WireInfo.CableInfo.constructionKind
                : { id: null };

              this.modelData.cableInfo.shield_material_id.value = DATA
                .WirePhaseInfo[0].WireInfo.CableInfo.shieldMaterial
                ? DATA.WirePhaseInfo[0].WireInfo.CableInfo.shieldMaterial
                : { id: null };

              this.modelData.cableInfo.outer_jacket_kind_id.value = DATA
                .WirePhaseInfo[0].WireInfo.CableInfo.outerJacketKind
                ? DATA.WirePhaseInfo[0].WireInfo.CableInfo.outerJacketKind
                : { id: null };

              this.modelData.cableInfo.fire_safety_id.value = DATA
                .WirePhaseInfo[0].WireInfo.CableInfo.fireSafety
                ? DATA.WirePhaseInfo[0].WireInfo.CableInfo.fireSafety
                : { id: null };

              this.modelData.standardServiceLife.value.years = _.get(
                DATA.WirePhaseInfo[0].WireInfo.standardServiceLife,
                ["value", "years"],
                null
              );
              this.modelData.cableInfo.diameterOverJacket.value =
                DATA.WirePhaseInfo[0].WireInfo.CableInfo.diameterOverJacket?.value;

              this.modelData.cableInfo.diameterOverJacket.id =
                DATA.WirePhaseInfo[0].WireInfo.CableInfo.diameterOverJacket?.id;

              this.modelData.standardServiceLife.id =
                DATA.WirePhaseInfo[0].WireInfo.standardServiceLife.id;
            }
          }

          if (
            DATA.AssetInfo.CatalogAssetType.IdentifiedObject.names.length > 0
          ) {
            this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.names[0] =
              DATA.AssetInfo.CatalogAssetType.IdentifiedObject.names[0];
          } else {
            this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.names[0] =
              {
                name: "",
              };
          }
          if (this.fromId > 0) {
            this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name +=
              "(Копия)";
          }

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

    async loadSinglePhaseKind() {
      this.loading = true;
      this.errored = false;
      let url = "/api/all_enum_kind/model/SinglePhaseKind";

      await axios
        .get(url)
        .then((response) => {
          this.phaseInfoKind = response.data.data;
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          this.errored = true;
          // для отладки
          console.log("Ошибка при загрузке данных WireInfo");
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при загрузке данных...");
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
<style>
.form-field-2-column .form-field {
  margin-top: 18px !important;
}

.form-field .input-group-prepend {
  display: flex;
  align-items: center;
  padding: 15px 15px 0 15px;
  margin-left: -6px;
  background: #202346;
  border-top-right-radius: 6px;
  border-bottom-right-radius: 6px;
}

.form-field .error-label {
  position: absolute;
  bottom: -20px;
}

input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
  background-color: #273661 !important;
}

.position-initial {
  position: initial !important;
}

.border-bottom {
  border-bottom: 2px solid #273661;
}
</style>