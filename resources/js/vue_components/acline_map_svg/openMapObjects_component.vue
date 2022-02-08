<template>
    <section>
        <div class="m-30">
            <h4>Все обьекты карты:</h4>

            <div class="border p-20 ">

                <!-- крестик -->
                <div class="text-right">
                    <span class="wb-close"
                          @click="funMapOpenObjects(null)">
                    </span>
                </div>

                <!-- область в два столбца с прокруткой -->
                <div v-if="(typeof(mapObjects) !== 'undefined' && mapObjects.length > 0)"
                     class="mapObjectsColumn dark-scroll">

                    <!-- ТП -->
                    <div v-if="(serviceFilterObjectsOnType('substation').length > 0)">
                        <h4>ТП</h4>
                        <!-- вывод в цикле -->
                        <p v-for="item in serviceFilterObjectsOnType('substation')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.name }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- опоры -->
                    <div v-if="(serviceFilterObjectsOnType('tower').length > 0)">
                        <h4>Опоры</h4>
                        <p v-for="item in serviceFilterObjectsOnType('tower')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.localName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- разрядники -->
                    <div v-if="(serviceFilterObjectsOnType('discharger').length > 0)">
                        <h4>Разрядники</h4>
                        <p v-for="item in serviceFilterObjectsOnType('discharger')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- ОПН -->
                    <div v-if="(serviceFilterObjectsOnType('opn').length > 0)">
                        <h4>ОПН</h4>
                        <p v-for="item in serviceFilterObjectsOnType('opn')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- заземление -->
                    <div v-if="(serviceFilterObjectsOnType('grounding').length > 0)">
                        <h4>Заземление</h4>
                        <p v-for="item in serviceFilterObjectsOnType('grounding')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- фонарь -->
                    <div v-if="(serviceFilterObjectsOnType('lamp').length > 0)">
                        <h4>Фонарь</h4>
                        <p v-for="item in serviceFilterObjectsOnType('lamp')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- адаптер -->
                    <div v-if="(serviceFilterObjectsOnType('adapter').length > 0)">
                        <h4>Адаптер</h4>
                        <p v-for="item in serviceFilterObjectsOnType('adapter')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- линия связи -->
                    <div v-if="(serviceFilterObjectsOnType('commline').length > 0)">
                        <h4>Линия связи</h4>
                        <p v-for="item in serviceFilterObjectsOnType('commline')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- потребители -->
                    <div v-if="(serviceFilterObjectsOnType('customer').length > 0)">
                        <h4>Потребители</h4>
                        <p v-for="item in serviceFilterObjectsOnType('customer')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- линии воздушные 701 -->
                    <div v-if="(serviceFilterObjectsOnType('701').length > 0)">
                        <h4>Линии воздушные 701</h4>
                        <p v-for="item in serviceFilterObjectsOnType('701')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- линии кабельные 702 -->
                    <div v-if="(serviceFilterObjectsOnType('702').length > 0)">
                        <h4>Линии кабельные 702</h4>
                        <p v-for="item in serviceFilterObjectsOnType('702')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- разьединители -->
                    <div v-if="(serviceFilterObjectsOnType('disconnector').length > 0)">
                        <h4>Разьединители</h4>
                        <p v-for="item in serviceFilterObjectsOnType('disconnector')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- реклоузеры -->
                    <div v-if="(serviceFilterObjectsOnType('reklouzer').length > 0)">
                        <h4>Реклоузеры</h4>
                        <p v-for="item in serviceFilterObjectsOnType('reklouzer')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- выключатели нагрузки -->
                    <div v-if="(serviceFilterObjectsOnType('vna').length > 0)">
                        <h4>Выключатели нагрузки</h4>
                        <p v-for="item in serviceFilterObjectsOnType('vna')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                    <!-- текстовые надписи -->
                    <div v-if="(serviceFilterObjectsOnType('text').length > 0)">
                        <h4>Текстовые надписи</h4>
                        <p v-for="item in serviceFilterObjectsOnType('text')"
                           :key="item.mapID"
                           class="pAclineName"
                           @click="funMapOpenObjects(item.mapID)"
                        >
                            {{ item.viewName }} <small>(mapID = {{ item.mapID }})</small>
                        </p>
                    </div>

                </div>
                <div v-else>
                    <p>
                        К сожалению, обьектов на карте еще нет!
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

// подключение сервиса с общими функциями
import {
    serviceFilterObjectsOnType
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
        // фильтр по типу из всех обьектов карты (по полю type: substation, tower, text и прочее)
        serviceFilterObjectsOnType(getType) {

            // обратиться к сервису (потому что c template не видит напрямую)
            return serviceFilterObjectsOnType(this.mapObjects, getType);
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
