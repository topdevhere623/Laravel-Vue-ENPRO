<template>
  <div>
    <div class="page-header">
      <h2 class="all_kind page-title">Выберите вид</h2>
      <div class="row">
        <div class="col-6">
          <multiselect
            v-model="selectedKind"
            :options="kinds"
            :multiple="false"
            group-values="items"
            group-label="group"
            :group-select="false"
            placeholder="Введите название"
            :showLabels="false"
            track-by="title"
            label="title"
          >
            <span slot="noResult">Видов и свойств не найдено</span>
          </multiselect>
        </div>
      </div>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
        <li class="all_kind breadcrumb-item active">
          {{ selectedKind ? selectedKind.title : "Выберите вид" }}
        </li>
      </ol>
      <div class="row mt-3"></div>
      <div class="page-header-actions">
        <a
          v-if="selectedKind"
          :href="`/admin/all_kind/${selectedKind.modelName}/edit/0/enum/${selectedKind.enumKind ? 1:0}`"
          class="all_kind button"
          data-original-title="Создать новую запись"
        >
          <span class="icon icon-add" aria-hidden="true"></span>
          <span> Создать новую </span>
        </a>
      </div>
    </div>
    <div class="page-content main-content">
      <div class="row row-lg">
        <div class="col-lg-12">
          <model-spisok-enum-allkind-component v-if="isEnum"
            :get-model-name="selectedKind ? selectedKind.modelName : ''"
            :get-user-role="user_role"
          ></model-spisok-enum-allkind-component>
          <model-spisok-allkind-component v-else
            :get-model-name="selectedKind ? selectedKind.modelName : ''"
            :get-user-role="user_role"
          ></model-spisok-allkind-component>
        </div>
        
      </div>
    </div>
  </div>
</template>

<script>
  import Multiselect from "vue-multiselect";

  export default {
    name: "modelSpisokAllkindWrapper",
    props: ["user_role", "kinds"],
    components: {
      Multiselect,
    },
    mounted() {
      if (sessionStorage.getItem("selectedModel")) {
        this.kinds.map((group) => {
          group.items.map((kind) => {
            if (kind.modelName === sessionStorage.getItem("selectedModel")) {
              this.selectedKind = kind;
            }
          });
        });
        sessionStorage.removeItem("selectedModel");
      }
    },
    computed:{
      isEnum: function() {
        return this.selectedKind?.enumKind
      }
    },
    data() {
      return {
        selectedKind: null,
      };
    },
  };
</script>

<style scoped>
</style>
