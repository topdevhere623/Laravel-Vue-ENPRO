<template>
    <section>
        <div class="m-30">
            <h4>Выделенные обьекты карты:</h4>

            <div class="border p-20 ">

                <!-- крестик -->
                <div class="text-right">
                    <span class="wb-close"
                          @click="funMapOpenObjects(null)">
                    </span>
                </div>

                <!-- область в два столбца с прокруткой -->
                <div
                    v-if="(typeof(mapObjects) !== 'undefined' && mapObjects.length > 0)"
                    v-for="item in serviceFilterObjectsOnCurrent()"
                    :key="item.mapID"
                    class="mapObjectsColumn dark-scroll"
                    @click="funMapOpenObjects(item.mapID)"
                >

                    <p v-if="item.current"
                       class="pAclineName"
                    >
                        {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                    </p>

                </div>
                <div v-else>
                    <p>
                        К сожалению, выделенных красным обьектов на карте еще нет!
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

// подключение сервиса с общими функциями
import {
    serviceFilterObjectsOnCurrent,
} from './services/serviceGetFilterMapObjects';

export default {
    props: {
        mapObjects: Array,
    },
    computed: {},
    mounted() {
    },
    methods: {

        // ------------------------------------------------------------------
        // фильтр текущий или нетекущий из всех обьектов карты (по полю current)
        serviceFilterObjectsOnCurrent() {

            // обратиться к сервису (потому что c template не видит напрямую)
            return serviceFilterObjectsOnCurrent(this.mapObjects);
        },

        // ------------------------------------------------------------------
        // выбор указанного обьекта
        funMapOpenObjects(getMapObjectMapID = null) {

            // сообщить шине
            this.$eventBus.$emit("fromOpenMapObjects", getMapObjectMapID);
        }
    }
};
</script>

<style scoped>
.pAclineName {
    text-decoration: none;
    cursor: pointer;
}

.pAclineName:hover {
    text-decoration: underline;
}

.mapObjectsColumn {
    max-height: calc(100vh - 159px);
    height: 100%;
    overflow-y: scroll;
    display: grid;
    grid-template-columns: 1fr 1fr;
}

.dark-scroll::-webkit-scrollbar {
    width: 13px;
    height: 13px;
    background-color: #2f3136;
}

.dark-scroll::-webkit-scrollbar-thumb {
    background-color: #202225;
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iNzY3IiB2aWV3Qm94PSIwIDAgMTQgNzY3IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPg0KICAgIDxsaW5lIHgxPSIyLjg2NDI2IiB5MT0iMC41IiB4Mj0iMTAuMTM3IiB5Mj0iMC41IiBzdHJva2U9IndoaXRlIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4NCiAgICA8bGluZSB4MT0iMi44NjQyNiIgeTE9IjUuNSIgeDI9IjEwLjEzNyIgeTI9IjUuNSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+DQogICAgPGxpbmUgeDE9IjE0IiB5MT0iNzY2LjUiIHkyPSI3NjYuNSIgc3Ryb2tlPSIjMTYxNzE5Ii8+DQo8L3N2Zz4NCg==);
    background-position: center;
    background-repeat: no-repeat;
    background-size: 10px 10px;
}
</style>
