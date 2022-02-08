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
          <a href="/admin/old_transformer_tank_info">{{ titleOne }}</a>
        </li>
        <li class="breadcrumb-item active">
          {{
            (getModelId > 0 ? "Редактирование" : "Создание") +
            (modelData.TransformerTankInfo.AssetInfo.CatalogAssetType
              .IdentifiedObject.name &&
            modelData.TransformerTankInfo.AssetInfo.CatalogAssetType
              .IdentifiedObject.name !== ""
              ? " - " +
                modelData.TransformerTankInfo.AssetInfo.CatalogAssetType
                  .IdentifiedObject.name
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
        <a href="/admin/old_transformer_tank_info" class="button"> Закрыть </a>
      </div>
    </div>

    <!-- содержимое страницы-->
    <div class="page-content main-content">
      <div class="row row-lg">
        <div class="col-lg-12">
          <div class="panel panel-bordered form-icons">
            <div class="panel-body">
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
                        Основное
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabWinding"
                        aria-controls="tabWinding"
                        role="tab"
                        aria-selected="true"
                      >
                        Данные обмоток
                      </a>
                    </li>
                    <li
                      class="nav-item"
                      role="presentation"
                      v-show="
                        (getModelId > 0 || saveId>0) && EnergisedEnd.length > 0
                      "
                    >
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabTest"
                        aria-controls="tabTest"
                        role="tab"
                        aria-selected="true"
                      >
                        Данные опыта КЗ
                      </a>
                    </li>
                    <li
                      class="nav-item"
                      role="presentation"
                      v-show="
                        (getModelId > 0 || saveId>0) && EnergisedEnd.length > 0
                      "
                    >
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabNoTest"
                        aria-controls="tabNoTest"
                        role="tab"
                        aria-selected="true"
                      >
                        Данные опыта ХХ
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content pt-20">
                    <div class="tab-pane active" id="tabMain" role="tabpanel">
                      <div class="row">
                        <div class="col-6 mb-10">
                          <div class="form-input-label mb-3">Наименование</div>
                          <div class="form-field">
                            <input
                              type="text"
                              class="
                                text-field
                                app-old-transformer-tank-info__input
                              "
                              name="name"
                              v-model="
                                modelData.TransformerTankInfo.AssetInfo
                                  .CatalogAssetType.IdentifiedObject.name
                              "
                              placeholder="Введите наименование"
                              :readonly="isLoading || isSaving || !editing"
                            />
                          </div>
                          <div
                            v-if="
                              requestErrors.TransformerTankInfo.AssetInfo
                                .CatalogAssetType.IdentifiedObject.name !== ''
                            "
                            class="text-danger px-1"
                          >
                            {{
                              requestErrors.TransformerTankInfo.AssetInfo
                                .CatalogAssetType.IdentifiedObject.name
                            }}
                          </div>
                        </div>
                        <div class="col-6 mb-10">
                          <div class="form-input-label mb-3">
                            Краткое наименование
                          </div>
                          <div class="form-field">
                            <input
                              type="text"
                              class="
                                text-field
                                app-old-transformer-tank-info__input
                              "
                              name="shortname"
                              v-model="
                                modelData.TransformerTankInfo.AssetInfo
                                  .CatalogAssetType.IdentifiedObject.names[0]
                                  .name
                              "
                              placeholder="Краткое наименование"
                              :readonly="isLoading || isSaving || !editing"
                            />
                          </div>
                        </div>
                        <div class="col-md-12 mb-4"><hr /></div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label position-initial mb-3">
                            Тип конструкции
                          </div>
                          <div>
                            <v-select
                              v-model="modelData.constructionKind.id"
                              class="app-vue-select"
                              label="ru_value"
                              :filterable="false"
                              :loading="isLoading || isLoadingConstructionKind"
                              :options="constructionKindOptions"
                              :reduce="(optionProp) => optionProp.id"
                              :disabled="isLoading || isSaving || !editing"
                              @search="searchConstructionKind"
                            >
                              <template slot="no-options">
                                Введите для поиска
                              </template>
                              <template slot="option" slot-scope="option">
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                              <template
                                slot="selected-option"
                                slot-scope="option"
                              >
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                            </v-select>
                          </div>
                          <div class="text-right pr-1 pt-1">
                            <a
                              href="#"
                              class="app-text-color-accent-action text-sm"
                              @click.prevent="editing &&
                                showAddAnotherModalReference('constructionKind')
                              "
                              >Добавить</a
                            >
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label mb-3">
                            Вес активной части
                          </div>
                          <div class="input-group">
                            <input
                              type="number"
                              class="
                              form-control
                                text-field
                                app-old-transformer-tank-info__input
                              "
                              name="coreCoilsWeight"
                              v-model.number="modelData.coreCoilsWeight.value"
                              placeholder="Вес активной части"
                              :readonly="isLoading || isSaving || !editing"
                            />
                              <span class="
                                input-group-text 
                                app-form-input-temperature-degree-append"
                              >
                                кг
                              </span>
                          </div>
                          <div
                            v-if="requestErrors.coreCoilsWeight !== ''"
                            class="text-danger px-1"
                          >
                            {{ requestErrors.coreCoilsWeight }}
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label position-initial mb-3">
                            Тип сердечника
                          </div>
                          <div>
                            <v-select
                              v-model="modelData.coreKind.id"
                              class="app-vue-select"
                              label="ru_value"
                              :filterable="false"
                              :loading="isLoading || isLoadingCoreKind"
                              :options="coreKindOptions"
                              :reduce="(optionProp) => optionProp.id"
                              :disabled="isLoading || isSaving || !editing"
                              @search="searchCoreKind"
                            >
                              <template slot="no-options">
                                Введите для поиска
                              </template>
                              <template slot="option" slot-scope="option">
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                              <template
                                slot="selected-option"
                                slot-scope="option"
                              >
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                            </v-select>
                          </div>
                          <div class="text-right pr-1 pt-1">
                            <a
                              href="#"
                              class="app-text-color-accent-action text-sm"
                              @click.prevent="editing &&
                                showAddAnotherModalReference('coreKind')
                              "
                              :disabled="!editing"
                              >Добавить</a
                            >
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label mb-3">
                            Функциональное назначение
                          </div>
                          <div>
                            <v-select
                              v-model="modelData.function.id"
                              class="app-vue-select"
                              label="ru_value"
                              :filterable="false"
                              :loading="isLoading || isLoadingFunction"
                              :options="functionOptions"
                              :reduce="(optionProp) => optionProp.id"
                              :disabled="isLoading || isSaving || !editing"
                              @search="searchFunctionKind"
                            >
                              <template slot="no-options">
                                Введите для поиска
                              </template>
                              <template slot="option" slot-scope="option">
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                              <template
                                slot="selected-option"
                                slot-scope="option"
                              >
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                            </v-select>
                          </div>
                          <div class="text-right pr-1 pt-1">
                            <a
                              href="#"
                              class="app-text-color-accent-action text-sm"
                              @click.prevent="editing &&
                                showAddAnotherModalReference('function')
                              "
                              >Добавить</a
                            >
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label position-initial mb-3">
                            Вид охлаждения
                          </div>
                          <div>
                            <v-select
                              v-model="modelData.coolingKind.id"
                              class="app-vue-select"
                              label="ru_value"
                              :filterable="false"
                              :loading="isLoading || isLoadingCoolingKind"
                              :options="coolingKindOptions"
                              :reduce="(optionProp) => optionProp.id"
                              :disabled="isLoading || isSaving || !editing"
                              @search="searchCoolingKind"
                            >
                              <template slot="no-options">
                                Введите для поиска
                              </template>
                              <template slot="option" slot-scope="option">
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                              <template
                                slot="selected-option"
                                slot-scope="option"
                              >
                                <div>
                                  {{ option.ru_value }}
                                </div>
                              </template>
                            </v-select>
                          </div>
                          <div class="text-right pr-1 pt-1">
                            <a
                              href="#"
                              class="app-text-color-accent-action text-sm"
                              @click.prevent="editing &&
                                showAddAnotherModalReference('coolingKind')
                              "
                              :disabled="!editing"
                              >Добавить</a
                            >
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label mb-3">Масса полная</div>
                          <div class="form-field">
                            <input
                              type="number"
                              class="
                                text-field
                                app-old-transformer-tank-info__input
                              "
                              name="epFullWeight"
                              v-model.number="modelData.enproFullWeight.value"
                              placeholder="Масса полная"
                              :readonly="isLoading || isSaving || !editing"
                            />
                          </div>
                          <div
                            v-if="requestErrors.enproFullWeight !== ''"
                            class="text-danger px-1"
                          >
                            {{ requestErrors.enproFullWeight }}
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label mb-3">Масса масла</div>
                          <div class="form-field">
                            <input
                              type="number"
                              class="
                                text-field
                                app-old-transformer-tank-info__input
                              "
                              name="epOilWeight"
                              v-model.number="modelData.enproOilWeight.value"
                              placeholder="Масса масла"
                              :readonly="isLoading || isSaving || !editing"
                            />
                          </div>
                          <div
                            v-if="requestErrors.enproOilWeight !== ''"
                            class="text-danger px-1"
                          >
                            {{ requestErrors.enproOilWeight }}
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label mb-3">
                            Диапазон рабочих температур
                          </div>

                          <div class="row">
                            <div class="col-md-6 col-lg-6 mb-10">
                              <div class="input-group">
                                <input
                                  type="number"
                                  class="
                                    form-control
                                    text-field
                                    app-old-transformer-tank-info__input
                                  "
                                  name="enproTemperatureRangeMinTemperature"
                                  v-model.number="
                                    modelData.enproTemperatureRange
                                      .minTemperature.value
                                  "
                                  placeholder="Мин. температура"
                                  :readonly="isLoading || isSaving || !editing"
                                />
                                <span
                                  class="
                                    input-group-text
                                    app-form-input-temperature-degree-append
                                  "
                                  >°C</span
                                >
                              </div>
                            </div>

                            <div class="col-md-6 col-lg-6 mb-20">
                              <div class="input-group">
                                <input
                                  type="number"
                                  class="
                                    form-control
                                    text-field
                                    app-old-transformer-tank-info__input
                                  "
                                  name="enproTemperatureRangeMaxTemperature"
                                  v-model.number="
                                    modelData.enproTemperatureRange
                                      .maxTemperature.value
                                  "
                                  placeholder="Макс. температура"
                                  :readonly="isLoading || isSaving || !editing"
                                />
                                <span
                                  class="
                                    input-group-text
                                    app-form-input-temperature-degree-append
                                  "
                                  >°C</span
                                >
                              </div>
                            </div>
                          </div>

                          <!-- <div class="form-field pt-20">
                            <vue-slider-component
                              v-model="sliderEpTempRange"
                              :min="-100.00"
                              :max="100.00"
                              :step="-1"
                            >
                            </vue-slider-component>
                          </div> -->
                          <div
                            v-if="
                              requestErrors.enproTemperatureRange
                                .minTemperature !== '' ||
                              requestErrors.enproTemperatureRange
                                .maxTemperature !== ''
                            "
                            class="text-danger px-1 pt-5"
                          >
                            <span
                              v-if="
                                requestErrors.enproTemperatureRange
                                  .minTemperature !== ''
                              "
                            >
                              Мин:
                              {{
                                requestErrors.enproTemperatureRange
                                  .minTemperature
                              }}
                            </span>
                            <span v-else>
                              Макс:
                              {{
                                requestErrors.enproTemperatureRange
                                  .maxTemperature
                              }}
                            </span>
                          </div>
                        </div>

                        <div class="col-md-6 mb-10">
                          <div class="form-input-label position-initial mb-3">
                            ГОСТ
                          </div>
                          <div>
                            <v-select
                              v-model="modelData.enproGost.id"
                              class="app-vue-select"
                              label="name"
                              :filterable="false"
                              :loading="isLoading || isLoadingEpGOST"
                              :options="epGOSTOptions"
                              :reduce="(optionProp) => optionProp.id"
                              :disabled="isLoading || isSaving || !editing"
                              @search="searchEpGOST"
                            >
                              <template slot="no-options">
                                Введите для поиска
                              </template>
                              <template slot="option" slot-scope="option">
                                <div>
                                  {{ option.name }}
                                </div>
                              </template>
                              <template
                                slot="selected-option"
                                slot-scope="option"
                              >
                                <div>
                                  {{ option.name }}
                                </div>
                              </template>
                            </v-select>
                          </div>
                          <div class="text-right pr-1 pt-1">
                            <a
                              href="#"
                              class="app-text-color-accent-action text-sm"
                              @click.prevent="editing &&
                                showAddAnotherModalReference('epGOST')
                              "
                              >Добавить</a
                            >
                          </div>
                        </div>

                        <div class="col-md-12 mb-10">
                          <div
                            class="
                              d-flex
                              align-items-center
                              justify-content-end
                            "
                          >
                            <button
                              type="button"
                              class="
                                button
                                bordered
                                d-flex
                                align-items-center
                                justify-content-center
                                text-center
                              "
                              style="min-width: 140px"
                              :disabled="!editing"
                              @click="isSaving ? null : saveModelData()"
                            >
                              <button-loading-spinner v-if="isSaving" />
                              <span v-else>Сохранить</span>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="tabWinding" role="tabpanel">
                      <div class="row">
                        <div class="col-md-10">
                          <div
                            class=""
                            v-for="(property, index) in modelData
                              .TransformerTankInfo.TransformerEndInfo"
                            :key="index + '-winding'"
                          >
                            <div class="border-bottom row form-field-2-column">
                              <div class="col-md-12">
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
                                    deleteTransformerWindingProperty(index)
                                  "
                                >
                                  <span class="icon icon-close"></span>
                                </button>
                              </div>
                              <div class="col-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Наименование обмотки
                                </div>
                                <div class="form-field">
                                  <input
                                    type="text"
                                    class="
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    :class="{
                                      'is-invalid-borders':
                                        $v.modelData.TransformerTankInfo
                                          .TransformerEndInfo.$each[index]
                                          .AssetInfo.IdentifiedObject.name
                                          .$error,
                                    }"
                                    name="name"
                                    v-model.trim="
                                      $v.modelData.TransformerTankInfo
                                        .TransformerEndInfo.$each[index]
                                        .AssetInfo.IdentifiedObject.name.$model
                                    "
                                    placeholder="Введите наименование"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <div
                                    v-if="
                                      $v.modelData.TransformerTankInfo
                                        .TransformerEndInfo.$each[index]
                                        .AssetInfo.IdentifiedObject.name.$error
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
                              <div class="col-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Краткое наименование
                                </div>
                                <div class="form-field">
                                  <input
                                    type="text"
                                    class="
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="shortname"
                                    :class="{
                                      'is-invalid-borders':
                                        $v.modelData.TransformerTankInfo
                                          .TransformerEndInfo.$each[index]
                                          .AssetInfo.IdentifiedObject.names
                                          .$each[0].name.$error,
                                    }"
                                    v-model.trim="
                                      $v.modelData.TransformerTankInfo
                                        .TransformerEndInfo.$each[index]
                                        .AssetInfo.IdentifiedObject.names
                                        .$each[0].name.$model
                                    "
                                    placeholder="Краткое наименование"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <div
                                    v-if="
                                      $v.modelData.TransformerTankInfo
                                        .TransformerEndInfo.$each[index]
                                        .AssetInfo.IdentifiedObject.names
                                        .$each[0].name.$error
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
                              <div class="col-md-12 mb-4"><hr /></div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Номер обмотки
                                </div>
                                <div class="">
                                  <input
                                    type="number"
                                    class="
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="endNumber"
                                    v-model.number="
                                      modelData.TransformerTankInfo
                                        .TransformerEndInfo[index].endNumber
                                        .value
                                    "
                                    placeholder="Номер обмотки"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                </div>
                              </div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Полная номинальная мощность
                                </div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="ratedS"
                                    v-model.number="
                                      modelData.TransformerTankInfo
                                        .TransformerEndInfo[index].ratedS.value
                                    "
                                    placeholder="Полная номинальная мощность"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <span
                                    class="
                                      input-group-text
                                      app-form-input-temperature-degree-append
                                    "
                                    >кВА</span>
                                </div>
                              </div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Номинальное напряжение
                                </div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="ratedU"
                                    v-model.number="
                                      modelData.TransformerTankInfo
                                        .TransformerEndInfo[index].ratedU.value
                                    "
                                    placeholder="Номинальное напряжение"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <span
                                    class="
                                      input-group-text
                                      app-form-input-temperature-degree-append
                                    "
                                    >кВ</span
                                  >
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-input-label position-initial">
                                  Схема соединения
                                </div>
                                <select-with-search
                                  get-label="literal"
                                  @select="
                                    modelData.TransformerTankInfo.TransformerEndInfo[
                                      index
                                    ].connection_kind_id.value = $event
                                  "
                                  @remove="
                                    modelData.TransformerTankInfo.TransformerEndInfo[
                                      index
                                    ].connection_kind_id.value = $event
                                  "
                                  @loading="loadStatus"
                                  @loadingDone="loadStatus"
                                  get-model-name="WindingConnection"
                                  :get-value="
                                    modelData.TransformerTankInfo
                                      .TransformerEndInfo[index]
                                      .connection_kind_id.value
                                  "
                                  :get-id="'connection_kind_id-' + index"
                                  get-title="Схема соединения"
                                  get-url="all_enum_kind/model/WindingConnection"
                                ></select-with-search>
                              </div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Группа соединения
                                </div>
                                <div class="">
                                  <input
                                    type="number"
                                    class="
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="phaseAngleClock"
                                    v-model.number="
                                      modelData.TransformerTankInfo
                                        .TransformerEndInfo[index]
                                        .phaseAngleClock.value
                                    "
                                    placeholder="Группа соединения"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                </div>
                              </div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Сопротивление постоянному току
                                </div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="r"
                                    v-model.number="
                                      modelData.TransformerTankInfo
                                        .TransformerEndInfo[index].r.value
                                    "
                                    placeholder="Сопротивление постоянному току"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <span
                                    class="
                                      input-group-text
                                      app-form-input-temperature-degree-append
                                    "
                                    >Ом</span
                                  >
                                </div>
                              </div>
                              <div class="form-field col-md-6">
                                <div class="form-input-label position-initial">
                                  Тип изоляции
                                </div>
                                <select-with-search
                                  get-label="ru_value"
                                  @select="
                                    modelData.TransformerTankInfo.TransformerEndInfo[
                                      index
                                    ].OldTransformerEndInfo.winding_insulation_kind_id.value =
                                      $event
                                  "
                                  @remove="
                                    modelData.TransformerTankInfo.TransformerEndInfo[
                                      index
                                    ].OldTransformerEndInfo.winding_insulation_kind_id.value =
                                      $event
                                  "
                                  @loading="loadStatus"
                                  @loadingDone="loadStatus"
                                  get-model-name="WindingInsulationKind"
                                  :get-value="
                                    modelData.TransformerTankInfo
                                      .TransformerEndInfo[index]
                                      .OldTransformerEndInfo
                                      .winding_insulation_kind_id.value
                                  "
                                  :get-id="
                                    'winding_insulation_kind_id-' + index
                                  "
                                  get-title="Тип изоляции"
                                  get-url="all_kind/model/WindingInsulationKind"
                                  :disabled="isLoading || isSaving || !editing"
                                ></select-with-search>
                              </div>
                              <div class="col-md-12 mb-15">
                                <div
                                  class="
                                    text-right
                                    d-flex
                                    align-items-center
                                    justify-content-end
                                  "
                                >
                                  <button
                                    type="button"
                                    class="button bordered mr-2"
                                    @click="copyWireProperty(index)"
                                    :disabled="!editing"
                                  >
                                    Копировать
                                  </button>
                                  <button
                                    type="button"
                                    class="button bordered"
                                    style="min-width: 140px"
                                    @click="isSaving ? null : saveModelData()"
                                    :disabled="!editing"
                                  >
                                    <div
                                      class="
                                        d-flex
                                        align-items-center
                                        justify-content-center
                                        text-center
                                      "
                                    >
                                      <button-loading-spinner v-if="isSaving" />
                                      <span v-else>Сохранить</span>
                                    </div>
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
                              @click="createWindingProperty"
                              :disabled="!editing"
                            >
                              Добавить
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane" id="tabTest" role="tabpanel">
                      <div
                        class="row"
                        v-if="
                          (getModelId > 0 || saveId>0) && EnergisedEnd.length > 0
                        "
                      >
                        <div class="col-md-10">
                          <div
                            class=""
                            v-for="(property, index) in ShortCircuitTests"
                            :key="index + '-test'"
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
                                  @click="deleteTestProperty(index)"
                                  :disabled="!editing"
                                >
                                  <span class="icon icon-close"></span>
                                </button>
                              </div>
                              <!-- тесты -->
                              <div class="col-md-6 mb-10">
                                <div
                                  class="form-input-label position-initial mb-3"
                                >
                                  Обмотка, на которую подано напряжение
                                </div>
                                <div
                                  class="rounded-5"
                                  :class="{
                                    'is-invalid-borders':
                                      $v.ShortCircuitTests.$each[index]
                                        .energised_end_id.value.$error,
                                  }"
                                >
                                  <v-select
                                    v-model="
                                      $v.ShortCircuitTests.$each[index]
                                        .energised_end_id.value.$model
                                    "
                                    class="app-vue-select"
                                    label="name"
                                    :filterable="false"
                                    :loading="isLoading || isLoadingEnergised"
                                    :options="EnergisedEnd"
                                    :reduce="(optionProp) => optionProp.id"
                                    :disabled="isLoading || isSaving || !editing"
                                  >
                                    <template slot="option" slot-scope="option">
                                      <div>
                                        {{ option.name }}
                                      </div>
                                    </template>
                                    <template
                                      slot="selected-option"
                                      slot-scope="option"
                                    >
                                      <div>
                                        {{ option.name }}
                                      </div>
                                    </template>
                                  </v-select>
                                </div>
                                <div
                                  v-if="
                                    $v.ShortCircuitTests.$each[index]
                                      .energised_end_id.value.$error
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
                              <div class="col-md-6 mb-10">
                                <div
                                  class="form-input-label position-initial mb-3"
                                >
                                  Обмотки закороченные
                                </div>
                                <div
                                  class="rounded-5"
                                  :class="{
                                    'is-invalid-borders':
                                      $v.ShortCircuitTests.$each[index]
                                        .GroundedEnds.value.$error,
                                  }"
                                >
                                  <multiselect
                                    v-model="
                                      $v.ShortCircuitTests.$each[index]
                                        .GroundedEnds.value.$model
                                    "
                                    :preserve-search="true"
                                    placeholder="введите для поиска"
                                    select-label="Выбрать"
                                    selected-label="Выбрано"
                                    deselect-label="Удалить"
                                    label="name"
                                    track-by="id"
                                    :multiple="true"
                                    :options="EnergisedEnd"
                                    :disabled="isLoading || isSaving || !editing"
                                  >
                                    <span slot="noResult"
                                      >Никаких элементов не найдено.</span
                                    >

                                    <span slot="noOptions"> Список пуст </span>
                                  </multiselect>
                                </div>
                                <div
                                  v-if="
                                    $v.ShortCircuitTests.$each[index]
                                      .GroundedEnds.value.$error
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
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Потери КЗ (Pкз)
                                </div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="Ioss"
                                    v-model.number="
                                      ShortCircuitTests[index].Ioss.value
                                    "
                                    placeholder="Потери КЗ (Pкз)"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <span
                                    class="
                                      input-group-text
                                      app-form-input-temperature-degree-append
                                    "
                                    >кВт</span
                                  >
                                </div>
                              </div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Напряжение КЗ (Uк%)
                                </div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="voltage"
                                    v-model.number="
                                      ShortCircuitTests[index].Voltage.value
                                    "
                                    placeholder="Напряжение КЗ (Uк%)"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                </div>
                              </div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Температура
                                </div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="temperature"
                                    v-model.number="
                                      ShortCircuitTests[index].TransformerTest
                                        .temperature.value
                                    "
                                    placeholder="Температура"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <span
                                    class="
                                      input-group-text
                                      app-form-input-temperature-degree-append
                                    "
                                    >грдЦ</span
                                  >
                                </div>
                              </div>
                              <div class="col-md-12 mb-15">
                                <div
                                  class="
                                    text-right
                                    d-flex
                                    align-items-center
                                    justify-content-end
                                  "
                                >
                                  <button
                                    type="button"
                                    class="button bordered"
                                    style="min-width: 140px"
                                    @click="isSaving ? null : saveModelData()"
                                    :disabled="!editing"
                                  >
                                    <div
                                      class="
                                        d-flex
                                        align-items-center
                                        justify-content-center
                                        text-center
                                      "
                                    >
                                      <button-loading-spinner v-if="isSaving" />
                                      <span v-else>Сохранить</span>
                                    </div>
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
                              @click="createTest"
                              :disabled="!editing"
                            >
                              Добавить
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="tabNoTest" role="tabpanel">
                      <div
                        class="row"
                        v-if="
                          (getModelId > 0 || saveId>0) && EnergisedEnd.length > 0
                        "
                      >
                        <div class="col-md-10">
                          <div
                            class=""
                            v-for="(property, index) in NoLoadTests"
                            :key="index + '-ntest'"
                          >
                            <div class="border-bottom row form-field-2-column">
                              <div class="col-md-12 mt-15">
                                <button
                                  type="button"
                                  class="
                                    m-0s
                                    btn btn-outline-danger btn-sm
                                    d-flex
                                    ml-auto
                                    border-0
                                  "
                                  @click="deleteTestProperty(index)"
                                  :disabled="!editing"
                                >
                                  <span class="icon icon-close"></span>
                                </button>
                              </div>
                              <!-- тесты -->
                              <div class="col-md-6 mb-10">
                                <div
                                  class="form-input-label position-initial mb-3"
                                >
                                  Обмотка, на которую подано напряжение
                                </div>
                                <div
                                  class="rounded-5"
                                  :class="{
                                    'is-invalid-borders':
                                      $v.NoLoadTests.$each[index]
                                        .energised_end_id.value.$error,
                                  }"
                                >
                                  <v-select
                                    v-model="
                                      $v.NoLoadTests.$each[index]
                                        .energised_end_id.value.$model
                                    "
                                    class="app-vue-select"
                                    label="name"
                                    :filterable="false"
                                    :loading="isLoading || isLoadingEnergised"
                                    :options="EnergisedEnd"
                                    :reduce="(optionProp) => optionProp.id"
                                    :disabled="isLoading || isSaving || !editing"
                                  >
                                    <template slot="option" slot-scope="option">
                                      <div>
                                        {{ option.name }}
                                      </div>
                                    </template>
                                    <template
                                      slot="selected-option"
                                      slot-scope="option"
                                    >
                                      <div>
                                        {{ option.name }}
                                      </div>
                                    </template>
                                  </v-select>
                                </div>
                                  <div
                                    v-if="
                                      $v.NoLoadTests.$each[index]
                                        .energised_end_id.value.$error
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
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">
                                  Потери ХХ
                                </div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="Loss"
                                    v-model.number="
                                      NoLoadTests[index].Loss.value
                                    "
                                    placeholder="Потери ХХ"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <span
                                    class="
                                      input-group-text
                                      app-form-input-temperature-degree-append
                                    "
                                    >кВm</span>
                                </div>
                              </div>
                              <div class="col-md-6 mb-10">
                                <div class="form-input-label mb-3">Ток ХХ</div>
                                <div class="input-group">
                                  <input
                                    type="number"
                                    class="
                                      form-control
                                      text-field
                                      app-old-transformer-tank-info__input
                                    "
                                    name="excitingCurrent"
                                    v-model.number="
                                      NoLoadTests[index].excitingCurrent.value
                                    "
                                    placeholder="Ток ХХ"
                                    :readonly="isLoading || isSaving || !editing"
                                  />
                                  <span
                                    class="
                                      input-group-text
                                      app-form-input-temperature-degree-append
                                    "
                                    >%</span>
                                </div>
                              </div>
                              <div class="col-md-12 mb-15">
                                <div
                                  class="
                                    text-right
                                    d-flex
                                    align-items-center
                                    justify-content-end
                                  "
                                >
                                  <button
                                    type="button"
                                    class="button bordered"
                                    style="min-width: 140px"
                                    @click="isSaving ? null : saveModelData()"
                                    :disabled="!editing"
                                  >
                                    <div
                                      class="
                                        d-flex
                                        align-items-center
                                        justify-content-center
                                        text-center
                                      "
                                    >
                                      <button-loading-spinner v-if="isSaving" />
                                      <span v-else>Сохранить</span>
                                    </div>
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
                              @click="createNoLoadTest"
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

    <sweet-modal
      class="my-custom-sweet-modal"
      ref="addAnotherKindModalRefer"
      overlay-theme="dark"
      enable-mobile-fullscreen
    >
      <div class="row">
        <div
          v-if="modelModalContentSelected === 'constructionKind'"
          class="col-12"
        >
          <construction-kind-modal-add-form
            @onNewCreated="loadConstructionKindOptions"
          ></construction-kind-modal-add-form>
        </div>

        <div v-if="modelModalContentSelected === 'coreKind'" class="col-12">
          <core-kind-modal-add-form
            @onNewCreated="loadCoreKindOptions"
          ></core-kind-modal-add-form>
        </div>

        <div v-if="modelModalContentSelected === 'function'" class="col-12">
          <function-kind-modal-add-form
            @onNewCreated="loadFunctionKindOptions"
          ></function-kind-modal-add-form>
        </div>

        <div v-if="modelModalContentSelected === 'coolingKind'" class="col-12">
          <cooling-type-modal-add-form
            @onNewCreated="loadCoolingKindOptions"
          ></cooling-type-modal-add-form>
        </div>

        <div v-if="modelModalContentSelected === 'epGOST'" class="col-12">
          <ep-gost-modal-add-form
            @onNewCreated="loadEpGOSTOptions"
          ></ep-gost-modal-add-form>
        </div>
      </div>
    </sweet-modal>
  </section>
</template>

<script>
import axios from "axios";
import Multiselect from "vue-multiselect";
import constructionKindModalAddForm from "../model_modal_forms/constructionKindModalAddForm";
import coreKindModalAddForm from "../model_modal_forms/coreKindModalAddForm";
import functionKindModalAddForm from "../model_modal_forms/functionKindModalAddForm";
import coolingTypeModalAddForm from "../model_modal_forms/coolingTypeModalAddForm";
import epGostModalAddForm from "../model_modal_forms/epGostModalAddForm";

import { required } from "vuelidate/lib/validators";


export default {
  name: "oldtransformertankinfo_edit",
  props: {
    getModelId: {
      type: Number,
      default: null,
    }, // id линии,
    titleOne: String,
    getModelName: {
      type: String,
    }
  },
  components: {
    Multiselect,
    constructionKindModalAddForm,
    coreKindModalAddForm,
    functionKindModalAddForm,
    coolingTypeModalAddForm,
    epGostModalAddForm,
  },
  data() {
    return {
      saveId:null,
      value: null,
      saveTransformerEndInf: [],
      modelModalContentSelected: "",
      loading: false,
      isLoading: false,
      isSaving: false,
      errored: false,
      isLoadingConstructionKind: false,
      isLoadingEnergised: false,
      constructionKindOptions: [],
      isLoadingCoreKind: false,
      coreKindOptions: [],
      isLoadingCoolingKind: false,
      coolingKindOptions: [],
      isLoadingFunction: false,
      functionOptions: [],
      isLoadingEpGOST: false,
      epGOSTOptions: [],
      editingId: null,
      EnergisedEnd: [],
      GroundedEnds: [],
      ShortCircuitTests: [],
      NoLoadTests: [],
      modelData: {
        TransformerTankInfo: {
          AssetInfo: {
            CatalogAssetType: {
              IdentifiedObject: {
                name: "",
                names: [{ name: "" }],
              },
            },
          },
          TransformerEndInfo: [],
        },
        constructionKind: {
          id: null,
        },
        coreKind: {
          id: null,
        },
        coreCoilsWeight: {
          value: null,
        },
        function: {
          id: null,
        },
        coolingKind: {
          id: null,
        },
        enproFullWeight: {
          value: null,
        },
        enproOilWeight: {
          value: null,
        },
        enproTemperatureRange: {
          minTemperature: {
            value: -100.0,
          },
          maxTemperature: {
            value: 100.0,
          },
        },
        enproGost: {
          id: null,
        },
        TransformerEndInfo: [],
        ShortCircuitTests: [
        ],
      },
      sliderEpTempRange: [-100.0, 100.0],

      requestErrors: {
        TransformerTankInfo: {
          AssetInfo: {
            CatalogAssetType: {
              IdentifiedObject: {
                name: "",
              },
            },
          },
        },
        coreCoilsWeight: "",
        enproFullWeight: "",
        enproOilWeight: "",
        enproTemperatureRange: {
          minTemperature: "",
          maxTemperature: "",
        },
      },

      searchTypingTimeout: 0,
      searchTimeoutInterval: 800,
      editing: false
    };
  },
  validations: {
    modelData: {
      TransformerTankInfo: {
        TransformerEndInfo: {
          $each: {
            AssetInfo: {
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
    },
    ShortCircuitTests: {
      $each: {
        GroundedEnds: {
          value: {
            required,
          },
        },
        energised_end_id: {
          value: {
            required,
          },
        },
      },
    },
    NoLoadTests: {
      $each: {
        energised_end_id: {
          value: {
            required,
          },
        },
      },
    },
  },

  // watch: {
  //   sliderEpTempRange(rangeArr){
  //     this.modelData.enproTemperatureRange.minTemperature.value = rangeArr[0];
  //     this.modelData.enproTemperatureRange.maxTemperature.value = rangeArr[1];
  //   },
  // },
  computed: {
    recordTitle() {
      let recordName =
        this.modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name;
      if (this.getModelId > 0) {
        return recordName ? " - " + recordName : "Редактирование";
      } else {
        return recordName ? "Создание - " + recordName : "Создание";
      }
    },
  },
  created() {
    if (this.getModelId > 0) {
      this.setUpPage(this.getModelId);
    } else {
      this.editing = true
      this.loadAllData();
    }
  },
  methods: {
    createNoLoadTest(
      id = 0,
      energised_end_id = { id: null },
      Loss = { value: null, id: null },
      excitingCurrent = { value: null, id: null }
    ) {
      this.NoLoadTests.push({
        id: id,
        energised_end_id: {
          value: _.get(energised_end_id, ["id"], null),
          type: "select",
        },
        Loss: {
          value: _.get(Loss, ["value"], null),
          id: _.get(Loss, ["id"], null),
        },
        excitingCurrent: {
          value: _.get(excitingCurrent, ["value"], null),
          id: _.get(excitingCurrent, ["id"], null),
        },
      });
    },
    deleteTransformerWindingProperty(index) {
      if (confirm("Удалить данный запись ?")) {
        this.modelData.TransformerTankInfo.TransformerEndInfo.splice(index, 1);
        this.saveModelData();
      }
    },
    createTest(
      id = 0,
      energised_end_id = { id: null },
      GroundedEnds = [],
      Ioss = { value: null, id: null },
      Voltage = { value: null, id: null },
      TransformerTest = { id: null, temperature: { id: null, value: null } }
    ) {
      let GroundedEndsItems = [];
      if (GroundedEnds.length > 0) {
        GroundedEnds.map((item) => {
          GroundedEndsItems.push({
            id: _.get(item, ["id"], null),
            name: _.get(item, ["AssetInfo", "IdentifiedObject", "name"], null),
          });
        });
      }
      this.ShortCircuitTests.push({
        id: id,
        energised_end_id: {
          value: _.get(energised_end_id, ["id"], null),
          type: "select",
        },
        GroundedEnds: { value: GroundedEndsItems },
        Ioss: {
          value: _.get(Ioss, ["value"], null),
          id: _.get(Ioss, ["id"], null),
        },
        Voltage: {
          value: _.get(Voltage, ["value"], null),
          id: _.get(Voltage, ["id"], null),
        },
        TransformerTest: {
          id: _.get(TransformerTest, ["id"], null),
          temperature: {
            id: _.get(TransformerTest, ["temperature", "id"], null),
            value: _.get(TransformerTest, ["temperature", "value"], null),
          },
        },
      });
    },
    createWindingProperty(
      id = 0,
      AssetInfo = {
        IdentifiedObject: {
          name: null,
          names: [{ name: null }],
        },
      },
      endNumber = null,
      ratedS = { id: null, value: null },
      ratedU = { id: null, value: null },
      phaseAngleClock = null,
      r = { id: null, value: null },
      connectionKind = { id: null },
      OldTransformerEndInfo = {}
    ) {
      this.modelData.TransformerTankInfo.TransformerEndInfo.push({
        id: id,
        AssetInfo: AssetInfo,
        endNumber: {
          value: endNumber,
        },

        ratedS: {
          value: _.get(ratedS, ["value"], null),
          id: _.get(ratedS, ["id"], null),
        },
        ratedU: {
          value: _.get(ratedU, ["value"], null),
          id: _.get(ratedU, ["id"], null),
        },
        connection_kind_id: {
          value: connectionKind ? connectionKind : { id: null },
          type: "select",
        },
        phaseAngleClock: {
          value: phaseAngleClock,
        },
        r: { value: _.get(r, ["value"], null), id: _.get(r, ["id"], null) },
        OldTransformerEndInfo: {
          id: _.get(OldTransformerEndInfo, ["id"], null),
          winding_insulation_kind_id: {
            type: "select",
            value: _.get(
              OldTransformerEndInfo,
              ["windingInsulationKind"],
              false
            )
              ? OldTransformerEndInfo.windingInsulationKind
              : {
                  id: null,
                },
          },
        },
      });
    },
    copyWireProperty(index = null) {
      if (index !== null) {
        const data =
          this.modelData.TransformerTankInfo.TransformerEndInfo[index];

        this.createWindingProperty(
          null,
          undefined,
          data.endNumber,
          data.ratedS,
          data.ratedU,
          data.phaseAngleClock.value,
          data.r,
          data.connection_kind_id.value,
          {
            windingInsulationKind:
              data.OldTransformerEndInfo.winding_insulation_kind_id.value,
          }
        );
      }
    },
    filterValue(data) {
      const res = {};
      for (const key in data) {
        console.log(key);
        if (key === "AssetInfo" || key === "id") {
          continue;
        } else if (key === "OldTransformerEndInfo") {
          res[key] = {
            winding_insulation_kind_id:
              data[key].winding_insulation_kind_id.value.id,
          };
        } else if (key === "GroundedEnds" && data[key].length > 0) {
          res[key].map((item) => {
            return { id: item.id };
          });
          continue;
        } else if (key === "connection_kind_id") {
          if (data[key].value !== null) {
            res[key] = data[key].value.id;
          } else {
            res[key] = null;
          }
          continue;
        } else if (
          key === "endNumber" ||
          key === "phaseAngleClock" ||
          key === "energised_end_id"
        ) {
          console.log(key);
          if (data[key].value !== null) {
            res[key] = data[key].value;
          } else {
            res[key] = null;
          }
        } else {
          res[key] = {};
          if (
            _.get(data[key], ["type"], false) &&
            data[key].type === "select"
          ) {
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
      return res;
    },
    loadStatus(val) {
      this.loading = val;
    },
    debounceSearchType(cbFunction, cbValue) {
      if (this.searchTypingTimeout) {
        clearTimeout(this.searchTypingTimeout);
      }

      if (`${cbValue ? cbValue : ""}`.length) {
        this.searchTypingTimeout = setTimeout(
          () => cbFunction(cbValue),
          this.searchTimeoutInterval
        );
      }
    },
    //загрузка
    setUpPage(loadId) {
      this.loading = true;
      this.errored = false;

      this.isLoading = true;

      axios
        .get(`/api/oldTransformerTankInfo/${loadId}`)
        .then((resp) => {
          console.log("Editing id => ", resp?.data?.data);

          let {
            TransformerTankInfo,
            coreCoilsWeight,
            enproOilWeight,
            enproFullWeight,
            constructionKind,
            coolingKind,
            coreKind,
            function: respFunc,
            enproGost,
            enproTemperatureRange,
          } = resp?.data?.data;

          this.modelData = {
            TransformerTankInfo: {
              id: TransformerTankInfo?.id || null,
              AssetInfo: {
                id: TransformerTankInfo?.AssetInfo?.id || null,
                CatalogAssetType: {
                  id:
                    TransformerTankInfo?.AssetInfo?.CatalogAssetType?.id ||
                    null,
                  IdentifiedObject: {
                    id:
                      TransformerTankInfo?.AssetInfo?.CatalogAssetType
                        ?.IdentifiedObject?.id || null,
                    name:
                      TransformerTankInfo?.AssetInfo?.CatalogAssetType
                        ?.IdentifiedObject?.name || null,
                    names: TransformerTankInfo?.AssetInfo?.CatalogAssetType
                      ?.IdentifiedObject?.names || [{ name: null }],
                  },
                },
              },
              TransformerEndInfo: [],
            },
            constructionKind: {
              id: constructionKind?.id || null,
              description: constructionKind?.description || null,
              enpro_code: constructionKind?.enpro_code || null,
              ru_value: constructionKind?.ru_value || null,
              value: constructionKind?.value || null,
            },
            coreKind: {
              id: coreKind?.id || null,
              description: coreKind?.description || null,
              enpro_code: coreKind?.enpro_code || null,
              ru_value: coreKind?.ru_value || null,
              value: coreKind?.value || null,
            },
            coreCoilsWeight: {
              id: coreCoilsWeight?.id || null,
              value:
                coreCoilsWeight?.value === 0
                  ? 0
                  : coreCoilsWeight?.value || null,
            },
            function: {
              id: respFunc?.id || null,
              description: respFunc?.description || null,
              enpro_code: respFunc?.enpro_code || null,
              ru_value: respFunc?.ru_value || null,
              value: respFunc?.value || null,
            },
            coolingKind: {
              id: coolingKind?.id || null,
              description: coolingKind?.description || null,
              enpro_code: coolingKind?.enpro_code || null,
              ru_value: coolingKind?.ru_value || null,
              value: coolingKind?.value || null,
            },
            enproFullWeight: {
              id: enproFullWeight?.id || null,
              value:
                enproFullWeight?.value === 0
                  ? 0
                  : enproFullWeight?.value || null,
            },
            enproOilWeight: {
              id: enproOilWeight?.id || null,
              value:
                enproOilWeight?.value === 0 ? 0 : enproOilWeight?.value || null,
            },
            enproTemperatureRange: {
              id: enproTemperatureRange?.id || null,
              minTemperature: {
                id: enproTemperatureRange?.minTemperature?.id || null,
                value:
                  enproTemperatureRange?.minTemperature?.value === 0
                    ? 0
                    : enproTemperatureRange?.minTemperature?.value || null,
              },
              maxTemperature: {
                id: enproTemperatureRange?.maxTemperature?.id || null,
                value:
                  enproTemperatureRange?.maxTemperature?.value === 0
                    ? 0
                    : enproTemperatureRange?.maxTemperature?.value || null,
              },
            },
            enproGost: {
              id: enproGost?.id || null,
              keylink: enproGost?.keylink || null,
              name: enproGost?.name || null,
            },
          };
          console.log(1);
          const ctx = this;
          this.ShortCircuitTests=[]
          this.NoLoadTests=[]
          TransformerTankInfo.TransformerEndInfo.map((item) => {
            ctx.EnergisedEnd.push({
              id: item.id,
              name: item.AssetInfo.IdentifiedObject.name,
            });
            ctx.GroundedEnds.push(item.AssetInfo.IdentifiedObject.names[0]);
            console.log(1);
            ctx.createWindingProperty(
              item.id,
              item.AssetInfo,
              item.endNumber,
              item.ratedS,
              item.ratedU,
              item.phaseAngleClock,
              item.r,
              item.connectionKind,
              item.OldTransformerEndInfo
            );
           
            item.ShortCircuitTests.map((sTestItem) => {
              console.log("test create");
              ctx.createTest(
                sTestItem.id,
                sTestItem.EnergisedEnd,
                sTestItem.GroundedEnds,
                sTestItem.loss,
                sTestItem.voltage,
                sTestItem.TransformerTest
              );
            });
            item.NoLoadTests.map((nTestItem) => {
              console.log("noloadtest create");
              ctx.createNoLoadTest(
                nTestItem.id,
                nTestItem.EnergisedEnd,
                nTestItem.loss,
                nTestItem.excitingCurrent
              );
            });
          });
          console.log(2);
          if (constructionKind !== null) {
            this.constructionKindOptions = [{ ...constructionKind }];
          }
          if (constructionKind === null) {
            this.loadConstructionKindOptions();
          }
          if (coolingKind !== null) {
            this.coolingKindOptions = [{ ...coolingKind }];
          }
          if (coolingKind === null) {
            this.loadCoolingKindOptions();
          }
          if (coreKind !== null) {
            this.coreKindOptions = [{ ...coreKind }];
          }
          if (coreKind === null) {
            this.loadCoreKindOptions();
          }
          if (respFunc !== null) {
            this.functionOptions = [{ ...respFunc }];
          }
          if (respFunc === null) {
            this.loadFunctionKindOptions();
          }
          if (enproGost !== null) {
            this.epGOSTOptions = [{ ...enproGost }];
          }
          if (enproGost === null) {
            this.loadEpGOSTOptions();
          }

          // let { minTemperature, maxTemperatureVal } = resp?.data?.data?.enproTemperatureRange;
          // let sliderEpTempArr = [];
          // sliderEpTempArr.push(minTemperature?.value);
          // sliderEpTempArr.push(maxTemperatureVal?.value);

          // this.sliderEpTempRange = sliderEpTempArr;
        })
        .catch((err) => {
          toastr.error(
            `Ошибка при обработке данных... ${
              err?.response?.data?.message || ""
            }`
          );
        })
        .finally(() => {
          this.isLoading = false;
          this.loading = false;
        });
    },

    searchConstructionKind(val) {
      this.debounceSearchType(this.loadConstructionKindOptions, val);
    },
    searchEnergisedEnd(val) {
      this.debounceSearchType(this.loadSearchEnergisedEnd, val);
    },
    loadSearchEnergisedEnd(search = "") {
      this.isLoadingEnergised = true;

      return axios
        .get(
          `/api/all_kind/model/TransformerConstructionKind?perPage=100&${
            search !== "" ? `search=${search}` : ""
          }`
        )
        .then((resp) => {
          this.EnergisedEnd = resp?.data?.data || [];
        })
        .catch((err) => {
          toastr.error(
            `Ошибка при обработке данных... ${
              err?.response?.data?.message || ""
            }`
          );
        })
        .finally(() => {
          this.isLoadingEnergised = false;
        });
    },
    loadConstructionKindOptions(search = "") {
      this.isLoadingConstructionKind = true;

      return axios
        .get(
          `/api/all_kind/model/TransformerConstructionKind?perPage=100&${
            search !== "" ? `search=${search}` : ""
          }`
        )
        .then((resp) => {
          this.constructionKindOptions = resp?.data?.data || [];
        })
        .catch((err) => {
          toastr.error(
            `Ошибка при обработке данных... ${
              err?.response?.data?.message || ""
            }`
          );
        })
        .finally(() => {
          this.isLoadingConstructionKind = false;
        });
    },

    searchCoreKind(val) {
      this.debounceSearchType(this.loadCoreKindOptions, val);
    },

    loadCoreKindOptions(search = "") {
      this.isLoadingCoreKind = true;

      return axios
        .get(
          `/api/all_kind/model/TransformerCoreKind?perPage=100&${
            search !== "" ? `search=${search}` : ""
          }`
        )
        .then((resp) => {
          this.coreKindOptions = resp?.data?.data || [];
        })
        .catch((err) => {
          toastr.error(
            `Ошибка при обработке данных... ${
              err?.response?.data?.message || ""
            }`
          );
        })
        .finally(() => {
          this.isLoadingCoreKind = false;
        });
    },

    searchCoolingKind(val) {
      this.debounceSearchType(this.loadCoolingKindOptions, val);
    },

    loadCoolingKindOptions(search = "") {
      this.isLoadingCoolingKind = true;

      return axios
        .get(
          `/api/all_kind/model/TransformerCoolingKind?perPage=100&${
            search !== "" ? `search=${search}` : ""
          }`
        )
        .then((resp) => {
          this.coolingKindOptions = resp?.data?.data || [];
        })
        .catch((err) => {
          toastr.error(
            `Ошибка при обработке данных... ${
              err?.response?.data?.message || ""
            }`
          );
        })
        .finally(() => {
          this.isLoadingCoolingKind = false;
        });
    },

    searchFunctionKind(val) {
      this.debounceSearchType(this.loadFunctionKindOptions, val);
    },

    loadFunctionKindOptions(search = "") {
      this.isLoadingFunction = true;

      return axios
        .get(
          `/api/all_kind/model/TransformerFunctionKind?perPage=100&${
            search !== "" ? `search=${search}` : ""
          }`
        )
        .then((resp) => {
          this.functionOptions = resp?.data?.data || [];
        })
        .catch((err) => {
          toastr.error(
            `Ошибка при обработке данных... ${
              err?.response?.data?.message || ""
            }`
          );
        })
        .finally(() => {
          this.isLoadingFunction = false;
        });
    },

    searchEpGOST(val) {
      this.debounceSearchType(this.loadEpGOSTOptions, val);
    },

    loadEpGOSTOptions(search = "") {
      this.isLoadingEpGOST = true;

      return axios
        .get(`/api/gost?perPage=100&${search !== "" ? `search=${search}` : ""}`)
        .then((resp) => {
          this.epGOSTOptions = resp?.data?.data || [];
        })
        .catch((err) => {
          toastr.error(
            `Ошибка при обработке данных... ${
              err?.response?.data?.message || ""
            }`
          );
        })
        .finally(() => {
          this.isLoadingEpGOST = false;
        });
    },

    async loadAllData() {
      this.isLoading = true;

      let p1 = await this.loadConstructionKindOptions();
      let p2 = await this.loadCoreKindOptions();
      let p3 = await this.loadCoolingKindOptions();
      let p4 = await this.loadFunctionKindOptions();
      let p5 = await this.loadEpGOSTOptions();

      Promise.all([p1, p2, p3, p4, p5]).finally(() => {
        this.isLoading = false;
      });
    },

    showAddAnotherModalReference(modelModalContent = "") {
      if (modelModalContent !== "" && this.$refs?.addAnotherKindModalRefer) {
        this.modelModalContentSelected = modelModalContent || "";

        this.$refs?.addAnotherKindModalRefer?.open();
      }
    },

    saveModelData() {
      this.$v.$touch();

      // stop here if form is invalid
      if (this.$v.$invalid) {
        toastr.error("Ошибка при заполнений формы...");
        return;
      }
      this.isSaving = true;

      this.requestErrors.TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name =
        "";
      this.requestErrors.coreCoilsWeight = "";
      this.requestErrors.enproFullWeight = "";
      this.requestErrors.enproOilWeight = "";
      this.requestErrors.enproTemperatureRange.minTemperature = "";
      this.requestErrors.enproTemperatureRange.maxTemperature = "";

      if (this.getModelId > 0 || this.saveId > 0) {
        console.log("put");
        let SAVEID = null
        const TransformerEndInfo = [];
        const inputPutModelData = {
          // id: this.modelData?.id || this?.saveId || null,
          TransformerTankInfo: {
            id: this.modelData?.TransformerTankInfo?.id || null,
            AssetInfo: {
              id: this.modelData?.TransformerTankInfo?.AssetInfo?.id || null,
              CatalogAssetType: {
                id:
                  this.modelData?.TransformerTankInfo?.AssetInfo
                    ?.CatalogAssetType?.id || null,
                IdentifiedObject: {
                  id:
                    this.modelData?.TransformerTankInfo?.AssetInfo
                      ?.CatalogAssetType?.IdentifiedObject?.id || null,
                  name:
                    this.modelData?.TransformerTankInfo?.AssetInfo
                      ?.CatalogAssetType?.IdentifiedObject?.name || "",
                  names: [
                    {
                      id: _.get(
                        this.modelData.TransformerTankInfo.AssetInfo
                          .CatalogAssetType.IdentifiedObject.names[0],
                        ["id"],
                        null
                      ),
                      name: _.get(
                        this.modelData.TransformerTankInfo.AssetInfo
                          .CatalogAssetType.IdentifiedObject.names[0],
                        ["name"],
                        null
                      ),
                    },
                  ],
                },
              },
            },
          },
          construction_kind_id: this.modelData?.constructionKind?.id || null,
          core_kind_id: this.modelData?.coreKind?.id || null,
          coreCoilsWeight: {
            id: this.modelData?.coreCoilsWeight?.id || null,
            value:
              this.modelData?.coreCoilsWeight?.value === 0
                ? 0
                : this.modelData?.coreCoilsWeight?.value || null,
          },
          function_id: this.modelData?.function?.id || null,
          cooling_kind_id: this.modelData?.coolingKind?.id || null,
          enproFullWeight: {
            id: this.modelData?.enproFullWeight?.id || null,
            value:
              this.modelData?.enproFullWeight?.value === 0
                ? 0
                : this.modelData?.enproFullWeight?.value || null,
          },
          enproOilWeight: {
            id: this.modelData?.enproOilWeight?.id || null,
            value:
              this.modelData?.enproOilWeight?.value === 0
                ? 0
                : this.modelData?.enproOilWeight?.value || null,
          },
          enproTemperatureRange: {
            id: this.modelData?.enproTemperatureRange?.id || null,
            minTemperature: {
              id:
                this.modelData?.enproTemperatureRange?.minTemperature?.id ||
                null,
              value:
                this.modelData?.enproTemperatureRange?.minTemperature?.value ===
                0
                  ? 0
                  : this.modelData?.enproTemperatureRange?.minTemperature
                      ?.value || null,
            },
            maxTemperature: {
              id:
                this.modelData?.enproTemperatureRange?.maxTemperature?.id ||
                null,
              value:
                this.modelData?.enproTemperatureRange?.maxTemperature?.value ===
                0
                  ? 0
                  : this.modelData?.enproTemperatureRange?.maxTemperature
                      ?.value || null,
            },
          },
          gost_id: this.modelData?.enproGost?.id || null,
        };
        if(this.getModelId > 0){
          inputPutModelData.id=this.getModelId
          SAVEID = this.getModelId
          console.log('11111111')
        }else if(this.saveId>0){
          console.log('asdasdasdasdasd')
          inputPutModelData.id=this.saveId
          SAVEID = this.saveId
        }
        console.log(inputPutModelData);
        const ctx = this;
        this.modelData.TransformerTankInfo.TransformerEndInfo.map((item) => {
          let test = ctx.ShortCircuitTests.filter((testItem) => {
            if (testItem.energised_end_id.value === item.id) {
              return true;
            }
          });
          let NoLoadTests = ctx.NoLoadTests.filter((testItem) => {
            if (testItem.energised_end_id.value === item.id) {
              return true;
            }
          });
          console.log("test", test);
          let GroundedEnds = [];
          test.map((item) => {
            let test = {
              energised_end_id: _.get(
                item,
                ["energised_end_id", "value"],
                null
              ),
              voltage: { value: _.get(item, ["Voltage", "value"], null) },
              TransformerTest: {
                temperature: {
                  value: _.get(
                    item,
                    ["TransformerTest", "temperature", "value"],
                    null
                  ),
                },
              },

              loss: { value: _.get(item, ["Ioss", "value"], null) },

              GroundedEnds: item.GroundedEnds.value.filter((ge) => {
                return ge.id;
              }),
            };
            if (_.get(item, ["Ioss", "id"], null) !== null) {
              test.loss.id = item.Ioss.id;
            }
            if (_.get(item, ["Voltage", "id"], null) !== null) {
              test.voltage.id = item.Voltage.id;
            }
            if (_.get(item, ["TransformerTest", "id"], null) !== null) {
              test.TransformerTest.id = item.TransformerTest.id;
              if (_.get(item, ["TransformerTest", "temperature", "id"], null)) {
                test.TransformerTest.temperature.id =
                  item.TransformerTest.temperature.id;
              }
            }
            GroundedEnds.push(test);
          });
          let NoLoadTestsToSave = [];
          NoLoadTests.map((item) => {
            let NoLoadTest = {
              energised_end_id: _.get(
                item,
                ["energised_end_id", "value"],
                null
              ),
              loss: { value: _.get(item, ["Loss", "value"], null) },

              excitingCurrent: {
                value: _.get(item, ["excitingCurrent", "value"], null),
              },
            };
            if (_.get(item, ["Loss", "id"], null) !== null) {
              NoLoadTest.loss.id = item.Loss.id;
            }
            if (_.get(item, ["excitingCurrent", "id"], null) !== null) {
              NoLoadTest.excitingCurrent.id = item.excitingCurrent.id;
            }
            NoLoadTestsToSave.push(NoLoadTest);
          });
          const VAL = {
            AssetInfo: {
              IdentifiedObject: {
                name: item.AssetInfo.IdentifiedObject.name,
                names: [
                  { name: item.AssetInfo.IdentifiedObject.names[0].name },
                ],
              },
            },
            ...this.filterValue(item),
            ShortCircuitTests: GroundedEnds,
            NoLoadTests: NoLoadTestsToSave,
          };
          if (ctx.getModelId > 0) {
            VAL.AssetInfo = item.AssetInfo;
          }
          console.log("id-", item.id);
          if (item.id > 0) {
            VAL.id = item.id;
          }
          TransformerEndInfo.push(VAL);
        });
        inputPutModelData.TransformerTankInfo.TransformerEndInfo =
          TransformerEndInfo;
        console.log(inputPutModelData);
        console.log('saveid',SAVEID)
        axios
          .put(
            `/api/oldTransformerTankInfo/${SAVEID}`,
            inputPutModelData
          )
          // axios.put(`/api/oldTransformerTankInfo/${this.getModelId}`, this.modelData)
          .then((res) => {
            toastr.success("Данные успешно были обновлены!");
            console.log(res);
            this.setUpPage(SAVEID)
            // setTimeout(() => {
            //   window.location.href = "/admin/old_transformer_tank_info";
            // }, 400);
          })
          .catch((err) => {
            toastr.error(
              `Ошибка при обработке данных... ${
                err?.response?.data?.message || ""
              }`
            );

            this.requestErrors.TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name =
              err?.response?.data?.errors[
                "TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name"
              ]?.[0] || "";
            this.requestErrors.coreCoilsWeight =
              err?.response?.data?.errors["coreCoilsWeight.value"]?.[0] || "";
            this.requestErrors.enproFullWeight =
              err?.response?.data?.errors["enproFullWeight.value"]?.[0] || "";
            this.requestErrors.enproOilWeight =
              err?.response?.data?.errors["enproOilWeight.value"]?.[0] || "";
            this.requestErrors.enproTemperatureRange.minTemperature =
              err?.response?.data?.errors[
                "enproTemperatureRange.minTemperature.value"
              ]?.[0] || "";
            this.requestErrors.enproTemperatureRange.maxTemperature =
              err?.response?.data?.errors[
                "enproTemperatureRange.maxTemperature.value"
              ]?.[0] || "";
          })
          .finally(() => {
            this.isSaving = false;
          });
      } else {
        console.log("post");
        let inputPostModelData = {
          TransformerTankInfo: {
            AssetInfo: {
              CatalogAssetType: {
                IdentifiedObject: {
                  name:
                    this.modelData?.TransformerTankInfo?.AssetInfo
                      ?.CatalogAssetType?.IdentifiedObject?.name || "",
                  names: [
                    {
                      name:
                        this.modelData?.TransformerTankInfo?.AssetInfo
                          ?.CatalogAssetType?.IdentifiedObject?.names[0].name ||
                        "",
                    },
                  ],
                },
              },
            },
            TransformerEndInfo: [],
          },
          construction_kind_id: this.modelData?.constructionKind?.id || null,
          core_kind_id: this.modelData?.coreKind?.id || null,
          coreCoilsWeight: {
            value:
              this.modelData?.coreCoilsWeight?.value === 0
                ? 0
                : this.modelData?.coreCoilsWeight?.value || null,
          },
          function_id: this.modelData?.function?.id || null,
          cooling_kind_id: this.modelData?.coolingKind?.id || null,
          enproFullWeight: {
            value:
              this.modelData?.enproFullWeight?.value === 0
                ? 0
                : this.modelData?.enproFullWeight?.value || null,
          },
          enproOilWeight: {
            value:
              this.modelData?.enproOilWeight?.value === 0
                ? 0
                : this.modelData?.enproOilWeight?.value || null,
          },
          enproTemperatureRange: {
            minTemperature: {
              value:
                this.modelData?.enproTemperatureRange?.minTemperature?.value ===
                0
                  ? 0
                  : this.modelData?.enproTemperatureRange?.minTemperature
                      ?.value || null,
            },
            maxTemperature: {
              value:
                this.modelData?.enproTemperatureRange?.maxTemperature?.value ===
                0
                  ? 0
                  : this.modelData?.enproTemperatureRange?.maxTemperature
                      ?.value || null,
            },
          },
          gost_id: this.modelData?.enproGost?.id || null,
        };
        const ctx = this;
        this.modelData.TransformerTankInfo.TransformerEndInfo.map((item) => {
          const VAL = {
            AssetInfo: {
              IdentifiedObject: {
                name: item.AssetInfo.IdentifiedObject.name,
                names: [
                  { name: item.AssetInfo.IdentifiedObject.names[0].name },
                ],
              },
            },
            ...this.filterValue(item),
          };
          if (ctx.getModelId > 0) {
            VAL.AssetInfo = item.AssetInfo;
          }
          // if (ctx.fromId > 0) {
          //   let test = ctx.ShortCircuitTests.filter((testItem) => {
          //     if (testItem.energised_end_id.value === item.id) {
          //       return true;
          //     }
          //   });
          //   let NoLoadTests = ctx.NoLoadTests.filter((testItem) => {
          //     if (testItem.energised_end_id.value === item.id) {
          //       return true;
          //     }
          //   });

          //   let GroundedEnds = [];
          //   let NoLoadTestsToSave = [];
          //   test.map((item) => {
          //     let test = {
          //       energised_end_id: _.get(
          //         item,
          //         ["energised_end_id", "value"],
          //         null
          //       ),
          //       voltage: { value: _.get(item, ["Voltage", "value"], null) },

          //       TransformerTest: {
          //         temperature: {
          //           value: _.get(
          //             item,
          //             ["TransformerTest", "temperature", "value"],
          //             null
          //           ),
          //         },
          //       },

          //       loss: { value: _.get(item, ["Ioss", "value"], null) },
          //       GroundedEnds: item.GroundedEnds.value.filter((ge) => {
          //         return ge.id;
          //       }),
          //     };
          //     GroundedEnds.push(test);
          //   });
          //   NoLoadTests.map((item) => {
          //     let NoLoadTest = {
          //       energised_end_id: _.get(
          //         item,
          //         ["energised_end_id", "value"],
          //         null
          //       ),
          //       loss: { value: _.get(item, ["Loss", "value"], null) },

          //       excitingCurrent: {
          //         value: _.get(item, ["excitingCurrent", "value"], null),
          //       },
          //     };
          //     NoLoadTestsToSave.push(NoLoadTest);
          //   });
          //   VAL.ShortCircuitTests = GroundedEnds;
          //   VAL.NoLoadTests = NoLoadTestsToSave;
          // }

          inputPostModelData.TransformerTankInfo.TransformerEndInfo.push(VAL);
        });

        // if (this.fromId > 0) {
        //   inputPostModelData = this.filterCopiedRecord(inputPostModelData);
        // }
        console.log("copy", inputPostModelData);
        axios
          .post(`/api/oldTransformerTankInfo`, inputPostModelData)
          // axios.post(`/api/oldTransformerTankInfo`, this.modelData)
          .then((res) => {
            console.log(res);
            this.saveId = res?.data?.data?.id
            // this.resetModelData();
            this.setUpPage(this.saveId)
            toastr.success("Данные успешно были сохранены!");

            // setTimeout(() => {
            //   window.location.href = "/admin/old_transformer_tank_info";
            // }, 400);
          })
          .catch((err) => {
            toastr.error(
              `Ошибка при обработке данных... ${
                err?.response?.data?.message || ""
              }`
            );

            this.requestErrors.TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name =
              err?.response?.data?.errors[
                "TransformerTankInfo.AssetInfo.CatalogAssetType.IdentifiedObject.name"
              ]?.[0] || "";
            this.requestErrors.coreCoilsWeight =
              err?.response?.data?.errors["coreCoilsWeight.value"]?.[0] || "";
            this.requestErrors.enproFullWeight =
              err?.response?.data?.errors["enproFullWeight.value"]?.[0] || "";
            this.requestErrors.enproOilWeight =
              err?.response?.data?.errors["enproOilWeight.value"]?.[0] || "";
            this.requestErrors.enproTemperatureRange.minTemperature =
              err?.response?.data?.errors[
                "enproTemperatureRange.minTemperature.value"
              ]?.[0] || "";
            this.requestErrors.enproTemperatureRange.maxTemperature =
              err?.response?.data?.errors[
                "enproTemperatureRange.maxTemperature.value"
              ]?.[0] || "";
          })
          .finally(() => {
            this.isSaving = false;
          });
      }
    },

    // resetModelData() {
    //   this.modelData = {
    //     TransformerTankInfo: {
    //       AssetInfo: {
    //         CatalogAssetType: {
    //           IdentifiedObject: {
    //             name: "",
    //           },
    //         },
    //       },
    //     },
    //     constructionKind: {
    //       id: null,
    //     },
    //     coreKind: {
    //       id: null,
    //     },
    //     coreCoilsWeight: {
    //       value: null,
    //     },
    //     function: {
    //       id: null,
    //     },
    //     coolingKind: {
    //       id: null,
    //     },
    //     enproFullWeight: {
    //       value: null,
    //     },
    //     enproOilWeight: {
    //       value: null,
    //     },
    //     enproTemperatureRange: {
    //       minTemperature: {
    //         value: -100.0,
    //       },
    //       maxTemperature: {
    //         value: 100.0,
    //       },
    //     },
    //     enproGost: {
    //       id: null,
    //     },
    //   };
    //   this.sliderEpTempRange = [-100.0, 100.0];
    // },
  },
};
</script>
<style lang="scss">
.rounded-5 {
  border-radius: 5px;
}
.app-text-color-accent-action {
  color: #00dbff;
  font-size: 12px;
}
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
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
  background-color: #273661 !important ;
}
.position-initial {
  position: initial !important;
}
.border-bottom {
  border-bottom: 2px solid #273661;
}

.app-old-transformer-tank-info {
  &__input {
    height: auto !important;
    padding: 13px 20px !important;
  }
}

.my-custom-sweet-modal {
  .sweet-modal {
    background: #202346 !important;
    min-width: 860px !important;
  }

  .sweet-box-actions .sweet-action-close {
    color: #fff;
  }
}

.app-oldtransformer-form-select {
  .multiselect__tags {
    .multiselect__spinner {
      background: #273661 !important;
    }
  }
}

.app-vue-select .vs__search::placeholder,
.app-vue-select .vs__dropdown-toggle,
.app-vue-select .vs__dropdown-menu {
  background: #273661;
  border: none;
  color: #fff;
}

.app-vue-select.vs--disabled .vs__clear,
.vs--disabled .vs__dropdown-toggle,
.vs--disabled .vs__open-indicator,
.vs--disabled .vs__search,
.vs--disabled .vs__selected {
  background: #273661;
  border: none;
  color: #fff;
}

.slider-target .slider-base .slider-connects .slider-connect {
  background: #35dcff;
}

.slider-target .slider-base .slider-origin .slider-handle .slider-tooltip {
  border: 1px solid var(--slider-tooltip-bg, #35dcff);
  background: var(--slider-tooltip-bg, #35dcff);
}

.slider-target .slider-tooltip {
  background: #35dcff;
}

.app-vue-select .vs__clear,
.app-vue-select .vs__open-indicator {
  fill: #fff;
}

.app-vue-select .vs__dropdown-toggle .vs__selected-options .vs__selected,
.app-vue-select .vs__dropdown-menu .vs__dropdown-option,
.app-vue-select .vs__dropdown-toggle .vs__selected-options .vs__search {
  color: #fff;
}

.app-vue-select .vs__actions .vs__spinner {
  border-left-color: #00dbff !important;
}

.form-control:disabled,
.form-control[readonly] {
  background-color: #273661;
  opacity: 1;
}

.app-form-input-temperature-degree-append {
  background-color: #243156 !important;
  color: #00dbff !important;
  border-top-right-radius: 6px;
  border-bottom-right-radius: 6px;
  padding: 4px 10px;
  display: flex;
  align-items: center;
  font-size: 17px;
  justify-content: center;
}
</style>
