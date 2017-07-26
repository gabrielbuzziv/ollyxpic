<template>
    <div :class="classes">
        <label class="form-label" v-if="hasLabel">{{ label }}</label>

        <slot></slot>
    </div>
</template>

<script type="text/babel">
    import { isEmpty } from 'lodash'

    export default {
        props: {
            label: {
                type: String,
                default: ''
            },

            columns: Number
        },

        computed: {
            classes () {
                const columns = this.columns ? `col-md-${this.columns}` : null

                return ['form-group', columns]
            },

            hasLabel () {
                return ! isEmpty(this.label)
            }
        }
    }
</script>
