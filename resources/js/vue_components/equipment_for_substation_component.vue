<template>
    <div>
        <h3>Оборудование</h3>
        <div v-for="(eq, index) in equipment">
            <div v-if="index=='Disconnector'"><h4>Разъединители</h4></div>
            <div v-else-if="index=='Breaker'"><h4>Выключатели</h4></div>
            <div v-else-if="index=='Fuse'"><h4>Предохранители</h4></div>
            <div v-else-if="index=='LoadBreakSwitch'"><h4>Выключатели нагрузки</h4></div>
            <div v-else-if="index=='DisconnectorFuse'"><h4>Разъединители-предохранители</h4></div>
            <div v-else-if="index=='CircuitBreaker'"><h4>Автоматические выключатели</h4></div>
            <div v-else-if="index=='PowerTransformer'"><h4>Силовые трансформаторы</h4></div>
            <div v-for="(object, row) in eq">
                <div>Дисп. имя: {{ object.IdentifiedObject.name }}</div>
                <div>Ключ привязки: {{ object.IdentifiedObject.keyLink }}</div>
                <div v-if="index == 'PowerTransformer'">
                    <div>Марка: {{ object.Asset.assetinfokey }}</div>
                    <div>Номинальное напряжение: {{ object.IdentifiedObject.description }} </div>
                </div>
                <div v-else>
                    <div>Номинальное напряжение: {{ object.IdentifiedObject.voltage }}</div>
                    <div>Тип: {{ object.IdentifiedObject.description }}</div>
                </div>
                <hr />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "equipment_for_substation_component",
    props: {
        equipment: Object,
    }
}
</script>

<style scoped>

</style>
