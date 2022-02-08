<template>
    <div class="switcher">
        <div
            @click="onInput(option)"
            class="item"
            :title="option.label"
            :class="option.name === value ? 'active' : ''"
            v-for="option in options">
            <svg class="icon">
                <use :xlink:href="`/public/assets/backend/icons/sprite.svg#${option.icon}`"></use>
            </svg>
        </div>
    </div>
</template>
<script>

export default {
    name: 'switcher',
    data() {
        return ({
            currentValue: this.value,
        });
    },
    watch: {},
    props: ['value', 'label', 'id', 'options', 'name'],
    computed: {},
    methods: {
        onInput(option) {
            this.$emit('change', this.name, option.name);
        },
    },
    created() {

    },
    mounted() {

    },
};
</script>
