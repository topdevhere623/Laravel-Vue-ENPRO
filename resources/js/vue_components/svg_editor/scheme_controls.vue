<template>
    <div>
        <button class="button bordered" @click="clearData">
            Очистить
        </button>
        <button class="button" @click="saveData">
            Сохранить
        </button>
    </div>
</template>

<script>
import common from "../../mixins/common";
import {mapGetters} from 'vuex';

export default {
    name: 'scheme-controls',
    data() {
        return (
            {

            }
        )
    },
    mounted() {
      this.$eventBus.$on('saveScheme', this.saveData)
    },
    computed: {
        ...mapGetters(['allState', 'elementsList']),
    },
    mixins: [common],
    props: [

    ],
    methods: {
        saveData() {
          this.$eventBus.$emit('showLoading')
            console.log('SAVE DATA');
            console.log(this.allState)
            const data = this.stateToData(this.allState.elementsList);
            const state = JSON.parse(JSON.stringify(this.allState));

            console.log(data);

            state.selectedElement = {};
            state.modelsList = {};
            state.waitSelect = {};
            state.templatesList = [];

            //this.isLoading = true
            fetch(`/api/substation/scheme/${pageData.id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    state,
                    data
                })
            }).then(res => res.json()).then(() => {
                toastr.info('Данные успешно сохранены');
              this.$eventBus.$emit('hideLoading')
                //this.isLoading = false
            })
        },
        clearData() {
            fetch(`/api/substation/scheme/${pageData.id}`, {
                method: 'PUT',
                body: null
            }).then(res => {
                window.location.reload();
            })
        },
    }
}

</script>
