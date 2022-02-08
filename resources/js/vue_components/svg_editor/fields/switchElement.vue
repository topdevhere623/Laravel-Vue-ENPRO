<template>
    <div v-if="fields.fieldParams && fields.fieldParams.options.length" class="form-field element-switch">
        <div class="form-label">{{label}}</div>
        <switcher
            @change="change"
            :options="fields.fieldParams.options"
            :id="fields.key"
            :value="fields.value"
            :name="fields.fieldParams.name"
        />
    </div>
</template>

<script>
import switcher from './switcher.vue';

export default {
    name: 'switchElement',
    components: {
        switcher,
    },
    props: ['value', 'id', 'label', 'name'],
    data() {
        return ({
            fieldValue: this.value,
            fieldsData: {
                name: this.name,
                fieldType: 'switch-element',
                options: [{
                    name: true,
                    icon: 'power-on',
                    label: 'Вкл.',
                }, {
                    name: false,
                    icon: 'power-off',
                    label: 'Выкл.',
                }],
            },
        });
    },
    computed: {
        fields() {
            return {
                key: this.id,
                value: this.value,
                fieldParams: this.fieldsData,
            };
        },
    },
    methods: {
        change(field, value) {
            this.fieldValue = value;
            this.$emit('change', this.fieldValue, this.name);
        },
    },
};
</script>

<style scoped>

</style>
