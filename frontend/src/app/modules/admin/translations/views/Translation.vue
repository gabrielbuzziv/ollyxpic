<template>
    <tr>
        <td>
            <span class="label label-success" v-if="translation.fixed">Fixed</span>
            <span class="label label-warning" v-else>Pending</span>
        </td>
        <td>
            <span v-if="editing">
                <input type="text" class="form-control" v-model="translation.from">
            </span>

            <span v-else>{{ translation.from }}</span>
        </td>
        <td>
            <span v-if="editing">
                <input type="text" class="form-control" v-model="translation.to">
            </span>

            <span v-else>{{ translation.to }}</span>
        </td>
        <td class="text-right">
            <button class="btn btn-sm btn-success" @click.prevent="save()" v-if="editing">
                <i class="mdi mdi-check margin-right-5"></i>
                Update
            </button>

            <button class="btn btn-sm" @click.prevent="editing = true" v-else>
                <i class="mdi mdi-pencil margin-right-5"></i>
                Edit
            </button>
        </td>
    </tr>
</template>

<script>
    import services from '../services'

    export default {
        props: ['translation'],

        data () {
            return {
                editing: false
            }
        },

        methods: {
            save () {
                const data = {
                    _method: 'patch',
                    from: this.translation.from,
                    to: this.translation.to
                }

                services.save(this.translation.id, data)
                    .then(response => {
                        this.$message.success('Translation updated.')
                        this.editing = false

                        this.$emit('updated')
                    })
            }
        }
    }
</script>