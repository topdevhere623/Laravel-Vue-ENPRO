<template>
    <div v-if="fields.length" class="form-field element-rotate">
        <div class="form-label">{{label}}</div>
        <template v-for="(field, index) in fields">
            <template v-if="field.fieldParams">
                <switcher
                    @change="change"
                    :options="field.fieldParams.options"
                    :id="field.key"
                    :value="field.value"
                    :name="field.fieldParams.name"
                />
            </template>
        </template>
    </div>
</template>
<script>

import switcher from './switcher';

export default {
    name: 'elementRotate',
    data() {
        return ({
            fieldValue: this.value,
            fieldsData: [
                {
                    name: 'horizontal',
                    fieldType: 'switcher',
                    options: [{
                        name: 0,
                        icon: 'reflect-left',
                        label: 'Слева',
                    }, {
                        name: 1,
                        icon: 'reflect-right',
                        label: 'Справа',
                    }],
                },
                {
                    name: 'vertical',
                    fieldType: 'switcher',
                    options: [{
                        name: 0,
                        icon: 'reflect-top',
                        label: 'сверху',
                    }, {
                        name: 1,
                        icon: 'reflect-bottom',
                        label: 'снизу',
                    }],
                },
            ],
        });
    },
    components: {
        switcher,
    },
    props: ['value', 'id', 'label'],
    computed: {
        fields() {
            return Object.entries(this.value).map(el => {
                return {
                    key: el[0],
                    value: el[1],
                    fieldParams: this.fieldsData.find(obj => {
                        return obj.name === el[0];
                    }),
                };
            });
        },
    },
    methods: {
        change(field, value) {
            this.fieldValue[field] = value;
            this.$emit('change', this.fieldValue, 'rotate');
        },
    },
};
</script>
