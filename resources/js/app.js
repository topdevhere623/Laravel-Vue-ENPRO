require('./bootstrap');

window.Vue = require('vue');
Vue.prototype.$eventBus = new Vue();

import VueCompositionAPI from '@vue/composition-api';
import SweetModal from 'sweet-modal-vue/src/plugin.js'; // https://sweet-modal-vue.adepto.as/
import Vuelidate from 'vuelidate'; // https://vuelidate.js.org/
import Slider from '@vueform/slider/dist/slider.vue2.js'; // https://github.com/vueform/slider
import '@vueform/slider/themes/default.css';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import multiselect from "vue-multiselect";

import store from './store/index';
import Vue from 'vue';


// глобальная регистрация компонентов
Vue.use(VueCompositionAPI);
Vue.use(SweetModal);
Vue.use(Vuelidate);
Vue.component('multiselect', multiselect)
Vue.component('vue-slider-component', Slider);
Vue.component('v-select', vSelect);
Vue.component('button-loading-spinner', require('./vue_components/ui/button_loading_spinner').default);



// выбор из справочника
Vue.component('options-sprav-component', require('./vue_components/options_sprav_component').default);

// загрузка файлов изоброжений опор на карте
Vue.component('map-file-upload-component', require('./vue_components/map_file_upload_component').default);

// пагинация в списках
Vue.component('pagination', require('laravel-vue-pagination'));

// таблицы списком (поиск, фильтры, групповое удаление, пагинация и пр.)
Vue.component('model-spisok-aclinestatus-component', require('./vue_components/model_spisok/model_spisok_aclinestatus_component').default);
Vue.component('model-spisok-acline-component', require('./vue_components/model_spisok/model_spisok_acline_component').default);
Vue.component('model-spisok-acline-segment-component', require('./vue_components/model_spisok/model_spisok_acline_segment_component').default);
Vue.component('model-spisok-acline-span-component', require('./vue_components/model_spisok/model_spisok_acline_span_component').default);
Vue.component('model-spisok-acline-tower-component', require('./vue_components/model_spisok/model_spisok_acline_tower_component').default);
Vue.component('model-spisok-acline-customer-component', require('./vue_components/model_spisok/model_spisok_acline_customer_component').default);
Vue.component('model-spisok-acline-disconnector-component', require('./vue_components/model_spisok/model_spisok_acline_disconnector_component').default);
Vue.component('model-spisok-acline-discharger-component', require('./vue_components/model_spisok/model_spisok_acline_discharger_component').default);
Vue.component('model-spisok-acline-crossing-component', require('./vue_components/model_spisok/model_spisok_acline_crossing_component').default);
Vue.component('model-spisok-admin-log-component', require('./vue_components/model_spisok/model_spisok_admin_log_component').default);
Vue.component('model-spisok-customer-component', require('./vue_components/model_spisok/model_spisok_customer_component').default);
Vue.component('model-spisok-substation-component', require('./vue_components/model_spisok/model_spisok_substation_component').default);
Vue.component('model-spisok-towerinfo-component', require('./vue_components/model_spisok/model_spisok_towerinfo_component').default);
Vue.component('model-spisok-towermaterial-component', require('./vue_components/model_spisok/model_spisok_towermaterial_component').default);
Vue.component('model-spisok-towerkind-component', require('./vue_components/model_spisok/model_spisok_towerkind_component').default);
Vue.component('model-spisok-towerconstructionkind-component', require('./vue_components/model_spisok/model_spisok_towerconstructionkind_component').default);
Vue.component('model-spisok-towerconstructionaggregate-component', require('./vue_components/model_spisok/model_spisok_towerconstructionaggregate_component').default);
Vue.component('model-spisok-user-component', require('./vue_components/model_spisok/model_spisok_user_component').default);


// справочник списком (поиск, фильтры, групповое удаление, пагинация и пр.) (Есбол)
// общие тех.данные (IO)
Vue.component('model-spisok-identifiedobject-component', require('./vue_components/model_spisok/model_spisok_identifiedobject_component').default);
Vue.component('model-spisok-basevoltage-component', require('./vue_components/model_spisok/model_spisok_basevoltage_component').default);
Vue.component('model-spisok-aclinesegmentinfo-component', require('./vue_components/model_spisok/model_spisok_aclinesegmentinfo_component').default);
Vue.component('model-spisok-disconnectorinfo-component', require('./vue_components/model_spisok/model_spisok_disconnectorinfo_component').default);
Vue.component('model-spisok-dischargerinfo-component', require('./vue_components/model_spisok/model_spisok_dischargerinfo_component').default);
Vue.component('model-spisok-layingconditionkind-component', require('./vue_components/model_spisok/model_spisok_layingconditionkind_component').default);
Vue.component('model-spisok-crossing-component', require('./vue_components/model_spisok/model_spisok_crossing_component').default);
Vue.component('model-spisok-crossingtype-component', require('./vue_components/model_spisok/model_spisok_crossingtype_component').default);
Vue.component('model-spisok-materialkind-component', require('./vue_components/model_spisok/model_spisok_materialkind_component').default);
//Есбол
Vue.component('model-spisok-wireinfo-component', require('./vue_components/model_spisok/model_spisok_wireinfo_component').default);
Vue.component('model-spisok-allkind-component', require('./vue_components/model_spisok/model_spisok_allkind_componnet.vue').default);
Vue.component('model-spisok-allkind-wrapper', require('./vue_components/model_spisok/model_spisok_allkind_wrapper.vue').default);
//гост
Vue.component('model-add-gost-component', require('./vue_components/model_modal_forms/epGostModalAddForm.vue').default);

// редактирование моделей
Vue.component('model-edit-acline-component', require('./vue_components/model_edit/model_edit_acline_component').default);
Vue.component('model-edit-towerconstructionaggregate-component', require('./vue_components/model_edit/model_edit_towerconstructionaggregate_component').default);
Vue.component('model-edit-tower-component', require('./vue_components/model_edit/model_edit_tower_component').default);
Vue.component('model-edit-towerinfo-component', require('./vue_components/model_edit/model_edit_towerinfo_component').default);
Vue.component('model-edit-allkindform-component', require('./vue_components/model_edit/model_edit_allkindform_component').default);
// Есбол
Vue.component('model-edit-acline-segment-component', require('./vue_components/model_edit/model_edit_acline_segment_component').default);
Vue.component('model-edit-acline-discharger-component', require('./vue_components/model_edit/model_edit_acline_discharger_component').default);
Vue.component('model-edit-acline-disconnector-component', require('./vue_components/model_edit/model_edit_acline_disconnector_component').default);
Vue.component('model-asset-otherdata-component', require('./vue_components/model_asset/model_asset_otherdata_component').default);
Vue.component('model-asset-activityrecord-component', require('./vue_components/model_asset/model_asset_activityrecord_component').default);
Vue.component('model-edit-multigroupselect-component', require('./vue_components/model_edit/model_edit_multigroupselect_component').default);
Vue.component('model-edit-wireinfo-component', require('./vue_components/model_edit/model_edit_wireinfo_component').default);


Vue.component('model-edit-breaker-info-component', require('./vue_components/model_edit/model_edit_breaker_info_component').default);
Vue.component('model-spisok-breaker-info-component', require('./vue_components/model_spisok/model_spisok_breaker_info_component').default);

Vue.component('model-edit-disconnector-info-component', require('./vue_components/model_edit/model_edit_disconnector_info_component').default);
Vue.component('model-spisok-disconnector-info-component', require('./vue_components/model_spisok/model_spisok_disconnector_info_component').default);
//Есбол список групп
Vue.component('model-spisok-enproclassdefect-component', require('./vue_components/model_spisok/model_spisok_enproclassdefect_component').default);


Vue.component('model-edit-enproclassdefect-component', require('./vue_components/model_edit/model_edit_enproclassdefect_component').default);
Vue.component('model-spisok-enprodefect-component', require('./vue_components/model_spisok/model_spisok_enprodefect_component').default);
Vue.component('model-edit-enprodefect-component', require('./vue_components/model_edit/model_edit_enprodefect_component').default);
Vue.component('model-spisok-enprogroupdefect-component', require('./vue_components/model_spisok/model_spisok_enprogroupdefect_component').default);
Vue.component('model-edit-enprogroupdefect-component', require('./vue_components/model_edit/model_edit_enprogroupdefect_component').default);
//Есбол
// компонент селект с поиском
Vue.component('select-with-search', require('./vue_components/universal_components/select_with_search_component').default);

// компоненты опор
Vue.component('tower-construction-master-component', require('./vue_components/tower_construction/tower_construction_master_component').default);
Vue.component('tower-construction-master-pivots-component', require('./vue_components/tower_construction/tower_construction_master_pivots_component').default);
Vue.component('tower-construction-master-itogo-component', require('./vue_components/tower_construction/tower_construction_master_itogo_component').default);

// целостность данных
Vue.component('base-repair-component', require('./vue_components/base_repair_component').default);

// svg элементы (Михаил)
Vue.component('svg-cablebox-component', require('./vue_components/svg_cablebox_component').default);
Vue.component('svg-currenttransformer-component', require('./vue_components/svg_currenttransformer_component').default);
Vue.component('svg-disconnector-component', require('./vue_components/svg_disconnector_component').default);
Vue.component('svg_disconnectorfuse-component', require('./vue_components/svg_disconnectorfuse-component').default);
Vue.component('svg-loadbreakswitch-component', require('./vue_components/svg_loadbreakswitch_component').default);
Vue.component('equipment_for_substation_component', require('./vue_components/equipment_for_substation_component').default);

//Grid
Vue.component('grid-component', require('./vue_components/svg_editor/svg_grid').default);
Vue.component('svg_editor', require('./vue_components/svg_editor').default);

Vue.component('svg_area', require('./vue_components/svg_editor/svg_area.vue').default);
Vue.component('editor_toolbar', require('./vue_components/svg_editor/editor_toolbar.vue').default);

Vue.component('svg_component', require('./vue_components/svg_editor/components/svg_component').default);
Vue.component('scheme_controls', require('./vue_components/svg_editor/scheme_controls').default);

// карта редактор ЛЭП по новому через SVG (Сергей)
Vue.component('map-component', require('./vue_components/acline_map_svg/map_component').default);
Vue.component('map-control-component', require('./vue_components/acline_map_svg/mapControl_component').default);
Vue.component('open-acline-component', require('./vue_components/acline_map_svg/openAcline_component').default);
Vue.component('open-map-objects-component', require('./vue_components/acline_map_svg/openMapObjects_component').default);
Vue.component('open-current-objects-component', require('./vue_components/acline_map_svg/openCurrentObjects_component').default);
Vue.component('open-segments-component', require('./vue_components/acline_map_svg/openSegments_component').default);
Vue.component('svg-map-new-object-target-component', require('./vue_components/acline_map_svg/svg_map_newObjectTarget_component').default);
Vue.component('svg-map-substation-component', require('./vue_components/acline_map_svg/svg_map_substation_component').default);
Vue.component('svg-map-tower-component', require('./vue_components/acline_map_svg/svg_map_tower_component').default);
Vue.component('svg-map-customer-component', require('./vue_components/acline_map_svg/svg_map_customer_component').default);
Vue.component('svg-map-discharger-component', require('./vue_components/acline_map_svg/svg_map_discharger_component').default);
Vue.component('svg-map-opn-component', require('./vue_components/acline_map_svg/svg_map_opn_component').default);
Vue.component('svg-map-grounding-component', require('./vue_components/acline_map_svg/svg_map_grounding_component').default);
Vue.component('svg-map-lamp-component', require('./vue_components/acline_map_svg/svg_map_lamp_component').default);
Vue.component('svg-map-adapter-component', require('./vue_components/acline_map_svg/svg_map_adapter_component').default);
Vue.component('svg-map-commline-component', require('./vue_components/acline_map_svg/svg_map_commline_component').default);
Vue.component('svg-map-disconnector-component', require('./vue_components/acline_map_svg/svg_map_disconnector_component').default);
Vue.component('svg-map-reklouzer-component', require('./vue_components/acline_map_svg/svg_map_reklouzer_component').default);
Vue.component('svg-map-vna-component', require('./vue_components/acline_map_svg/svg_map_vna_component').default);
Vue.component('svg-map-line701-component', require('./vue_components/acline_map_svg/svg_map_line701_component').default);
Vue.component('svg-map-line702-component', require('./vue_components/acline_map_svg/svg_map_line702_component').default);
Vue.component('svg-map-text-component', require('./vue_components/acline_map_svg/svg_map_text_component').default);
Vue.component('get-position-component', require('./vue_components/acline_map_svg/getPosition_component').default); // положение 8 (дочерние обьекты и подписи)
//Vue.component('get-map-segment-component', require('./vue_components/acline_map_svg/svg_map_segment_component').default);
//Vue.component('svg-map-active-object-component', require('./vue_components/acline_map_svg/svg_map_activeObject_component').default);

// Трансформаторы силовые
Vue.component('model-spisok-oldtransformertankinfo-component', require('./vue_components/model_spisok/model_spisok_oldtransformertankinfo_component').default);
Vue.component('model-edit-oldtransformertankinfo-component', require('./vue_components/model_edit/model_edit_oldtransformertankinfo_component').default);

// Коммутационные аппараты => Предохранители 3 кВ и выше (ГОСТ 2213)
Vue.component('model-spisok-fuse-info-component', require('./vue_components/model_spisok/model_spisok_fuse_info_component').default);
Vue.component('model-edit-fuse-info-component', require('./vue_components/model_edit/model_edit_fuse_info_component').default);

// Коммутационные аппараты => Реклоузеры 6-35 кВ (СТО 34.01-3.2-004-2016)
Vue.component('model-spisok-recloser-info-component', require('./vue_components/model_spisok/model_spisok_recloser_info_component.vue').default);
Vue.component('model-edit-recloser-info-component', require('./vue_components/model_edit/model_edit_recloser_info_component').default);

// Коммутационные аппараты => Выключатели нагрузки 3-10 кВ (ГОСТ 17717)
Vue.component('model-spisok-load-break-switch-info-component', require('./vue_components/model_spisok/model_spisok_load_break_switch_info_component.vue').default);
Vue.component('model-edit-load-break-switch-info-component', require('./vue_components/model_edit/model_edit_load_break_switch_info_component').default);


Vue.component('vue-tree-component', require('./vue_components/vue_tree_component.vue').default);
Vue.component('model-spisok-projects-component', require('./vue_components/model_spisok/model_spisok_projects_component.vue').default);

//Есбол компоненты для фильтра
Vue.component('input-range-slider-component', require('./vue_components/input_range_slider_component.vue').default);

// Загрузка excel файла
Vue.component('excel-load-file-component', require('./vue_components/excel_load_file_component.vue').default);
// enum kind
Vue.component('model-edit-enum-allkindform-component', require('./vue_components/model_edit/model_edit_enum_allkindform_component.vue').default);
Vue.component('model-spisok-enum-allkind-component', require('./vue_components/model_spisok/model_spisok_enum_allkind_component.vue').default);

const app = new Vue({
  el: '#app',
  store: store
});


$(document).ready(function () {
  if (window.location.toString().includes("/substation/parse") && substationData) {
    currentSubstationData = jQuery.parseJSON(decodeURIComponent(substationData));
    if (!currentSubstationData.equipment || Array.isArray(currentSubstationData.equipment)) currentSubstationData.equipment = {};
    $('.custom-page').addClass('editor-page');

    substationApp = new Vue({
      el: '#substactionData',
      template: '<div id="substactionData"><svg_editor :data="data"></svg_editor></div>',
      store,
      data: {
        data: currentSubstationData.data
      }
    }
    );

    equipmentApp = new Vue({
      el: '#substaion_quipments',
      data: { data: currentSubstationData.equipment },
      template: '<equipment_for_substation_component v-bind:equipment="data"></equipment_for_substation_component>'
    })
  }
});
//
// let input = document.querySelector('#all_kind_input')
// let holder = document.querySelector('#all_kind_holder')
// let options = document.querySelectorAll('#all_kind_option')
// let pageTitle = document.querySelector('.all_kind.page-title')
// let addLink = document.querySelector('.all_kind.button')
// let pageBreadcrumb = document.querySelector('.all_kind.breadcrumb-item.active')
// function filterAllKind(val) {
//     if (val !== '') {
//         Array.prototype.map.call(options, option => {
//             if (!option.innerText.includes(val)) {
//                 option.classList.add('d-none')
//             } else {
//                 option.classList.contains('d-none') && option.classList.remove('d-none')
//             }
//         })
//     } else {
//         Array.prototype.map.call(options, option => {
//             option.classList.contains('d-none') && option.classList.remove('d-none')
//         })
//     }
// }
// input.addEventListener('input', () => {
//     filterAllKind(input.value)
// })
// input.addEventListener('focus', () => {
//     input.value = ''
//     filterAllKind(input.value)
//     $('#all_kind_list').collapse('show')
// })
// Array.prototype.map.call(options, option => {
//     option.addEventListener('click', (e) => {
//         let model = option.getAttribute('data-value')
//         let label = option.innerText
//         if (model !== '' && label !== '') {
//             input.value = label
//             holder.value = label
//             pageTitle.innerHTML = label
//             pageBreadcrumb.innerHTML = label
//             addLink.classList.remove('invisible')
//             addLink.setAttribute("href", `/admin/all_kind/${model}/edit/`)
//             store.dispatch('selectAllKindModel', model)
//         }
//         $('#all_kind_list').collapse('hide')
//     })
// })
// $(document).click((event) => {
//     if (!$(event.target).closest('#all_kind_list').length && !$(event.target).closest('#all_kind_input').length) {
//         $('#all_kind_list').collapse('hide')
//         input.value = holder.value
//     }
// });

let siteUrl = location.href

let sidebar = document.querySelector('.site-menu');
if (sidebar) {
  let dropdowns = sidebar.children;
  [...dropdowns].map(dropdown => {
    let links = dropdown.querySelector('.group-items.js-nav-content').children
    if (links && links.length > 0) {
      [...links].map(link => {
        if (link.tagName === 'A') {
          let linkHref = link.getAttribute('href')
          if (siteUrl === linkHref) {
            dropdown.classList.toggle('opened');
            let dropdownContent = dropdown.querySelector('.group-items.js-nav-content')

            $(dropdownContent).slideDown()
            // $('.js-nav-content', dropdown).stop(true).slideToggle();
          }
        } else if (link.tagName === 'DIV') {
          let childLinks = link.querySelector('.group-items.js-nav-content').children
          if (childLinks && childLinks.length > 0) {
            [...childLinks].map(childLink => {
              if (childLink.tagName === 'A') {
                let childHref = childLink.getAttribute('href')
                if (siteUrl === childHref) {
                  dropdown.classList.toggle('opened');
                  link.classList.toggle('opened');
                  let dropdownContent = dropdown.querySelector('.group-items.js-nav-content')
                  let childDropdownContent = link.querySelector('.group-items.js-nav-content')

                  $(dropdownContent).slideDown()
                  $(childDropdownContent).slideDown()
                }
              }
            })
          }
        }
      })
    }
  })
}

toastr.options.positionClass = 'toast-bottom-left'
