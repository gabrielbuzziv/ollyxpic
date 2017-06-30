<template>
    <label class="form-checkbox">
        <div @click="toggle" :class="{ 'margin-right-10': hasLabel }">
            <input type="checkbox" :name="name" :value="data" v-model="checked">
            <span></span>
        </div>

        <span v-if="hasLabel">{{ label }}</span>
    </label>
</template>

<script type="text/babel">
    import { isEmpty } from 'lodash'

    export default {
        props: ['name', 'data', 'value', 'label'],

        data () {
            return {
                active: false
            }
        },

        computed: {
            checked: {
                get () {
                    return this.value
                },

                set (value) {
                    this.active = value
                }
            },

            hasLabel () {
                return ! isEmpty(this.label)
            }
        },

        methods: {
            toggle () {
                this.$emit('input', this.active)
            }
        }
    }
</script>
