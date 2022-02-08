<template>
    <div class="grid checkbox-btn-group">

        <div class="grid-tr">

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[7]"
                            @click="funClick(7)"
                            type="checkbox">
                        <span>7</span>
                    </label>
                </div>
            </div>

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[8]"
                            @click="funClick(8)"
                            type="checkbox">
                        <span>8</span>
                    </label>
                </div>
            </div>

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[9]"
                            @click="funClick(9)"
                            type="checkbox">
                        <span>9</span>
                    </label>
                </div>
            </div>

        </div>

        <div class="grid-tr">

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[4]"
                            @click="funClick(4)"
                            type="checkbox">
                        <span>4</span>
                    </label>
                </div>
            </div>

            <div class="grid-td">
                <div class="item">
                    <label>

                    </label>
                </div>
            </div>

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[6]"
                            @click="funClick(6)"
                            type="checkbox">
                        <span>6</span>
                    </label>
                </div>
            </div>

        </div>
        <div class="grid-tr">

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[1]"
                            @click="funClick(1)"
                            type="checkbox">
                        <span>1</span>
                    </label>
                </div>
            </div>

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[2]"
                            @click="funClick(2)"
                            type="checkbox">
                        <span>2</span>
                    </label>
                </div>
            </div>

            <div class="grid-td">
                <div class="item">
                    <label>
                        <input
                            v-model="myPositions[3]"
                            @click="funClick(3)"
                            type="checkbox">
                        <span>3</span>
                    </label>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        getPosition: Number,
    },
    data() {
        return {
            myPositions: {
                7: false,
                8: false,
                9: false,
                4: false,
                6: false,
                1: false,
                2: false,
                3: false,
            },
        }
    },
    mounted() {
        // записать значение в переключатели
        this.funUpdate(this.getPosition);
    },
    created() {
        // подписаться на получение с шины
        this.$eventBus.$on('fromMap', this.funFromMap);
    },
    beforeDestroy() {
        // отписаться на получение с шины
        this.$eventBus.$off('fromMap');
    },
    watch: {},
    methods: {

        // записать значение в переключатели
        funUpdate(getPosition = null) {

            for (let key in this.myPositions) {
                this.myPositions[key] = (Number(key) === Number(getPosition)) ? true : false;
            }
        },

        // ------------------------------------------------------------------
        // события клика
        funClick(getPosition) {

            // записать значение в переключатели
            this.funUpdate(getPosition);

            // передать выбранное значение родителю
            this.$emit('positionChange', getPosition);
        },

        // ------------------------------------------------------------------
        // получение события с карты
        funFromMap(mapAttributes) {

            // найти текущий обьект
            let myCurrentObject = null;
            let myObjects = mapAttributes.objects.filter(function (item) {
                return (item.current);
            });
            if (myObjects.length > 0) {
                myCurrentObject = myObjects[0];
            }

            if (myCurrentObject !== null) {

                // записать значение в переключатель
                if (myCurrentObject.parentPlacemarkMapID === null && myCurrentObject.parentPolylineMapID === null) {
                    // это родитель
                    this.funUpdate(myCurrentObject.positionText);
                } else {
                    // это дочерний обьект
                    this.funUpdate(myCurrentObject.position);
                }
            }
        },
    }
}
</script>

<style scoped>

/* -------------------------------------- */
/* таблица */

.grid {
    display: table;
    width: 100px;
    border-collapse: separate;
    border-spacing: 3px;
    border: none;
}

.grid-tr {
    display: table-row;
}

.grid-td {
    display: table-cell;
    width: 12px;
    text-align: center;
    vertical-align: top;
    padding: 0;
}

.item {
    font-size: 8px;
}

/* -------------------------------------- */
/* перключатели ввиде кнопок */

.checkbox-btn-group {
    display: inline-block;
}

.checkbox-btn-group:after {
    content: "";
    clear: both;
    display: block;
}

.checkbox-btn-group label {
    display: inline-block;
    float: left;
    margin: 0;
    user-select: none;
    position: relative;
}

.checkbox-btn-group input[type=checkbox] {
    z-index: -1;
    opacity: 0;
    display: block;
    width: 0;
    height: 0;
}

.checkbox-btn-group span {
    display: inline-block;
    cursor: pointer;
    padding: 0 10px;
    line-height: 13px;
    border: 1px solid #999;
    border-right: none;
    transition: background 0.2s ease;
}

.checkbox-btn-group label:last-child span {
    border-right: 1px solid #999;
}

/* checked */
.checkbox-btn-group input[type=checkbox]:checked + span {
    background: #00dbff;
    color: #202346;
}

/* focus */
.checkbox-btn-group .focused span {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
}

/* hover */
.checkbox-btn-group label:hover {
    color: #666;
}

/* active */
.checkbox-btn-group input[type=checkbox]:active:not(:disabled) + span {
    background: #d2c5ac;
    color: #000;
}

/* disabled */
.checkbox-btn-group input[type=checkbox]:disabled + span {
    background: #efefef;
    color: #666;
    cursor: default;
}

.checkbox-btn-group input[type=checkbox]:checked:disabled + span {
    background: #00dbff;
    color: #202346;
}

</style>
