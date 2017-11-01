<template>
    <form action="" method="POST" class="worlds-form" @submit.prevent="onSubmit" ref="form">
        <input type="hidden" name="_method" :value="method">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" v-model="data.name">
        </div>

        <div class="form-group">
            <label>Type</label>
            <el-select class="block" v-model="data.type" placeholder="Select type">
                <el-option v-for="type in types" :value="type.name" :key="type.name"></el-option>
            </el-select>
        </div>

        <div class="margin-top-40">
            <button type="submit" class="btn btn-success" :disabled="submiting">
                <i class="mdi mdi-check margin-right-5"></i>
                Save <span v-if="submiting">...</span>
            </button>

            <router-link :to="{ name: 'admin.worlds' }" class="btn btn-blank">
                Cancel
            </router-link>
        </div>
    </form>
</template>

<script>
    import services from '../services'

    export default {
        props: ['action', 'method', 'data'],

        data () {
            return {
                submiting: false,
                types: [
                    { name: 'Optional PvP' },
                    { name: 'Open PvP' },
                    { name: 'Hardcore PvP' },
                    { name: 'Retro Open PvP' },
                    { name: 'Retro Hardcore PvP' },
                ]
            }
        },

        methods: {
            onSubmit () {
                const data = {
                    _method: this.method,
                    ...this.data
                }

                this.submiting = true
                services.save(this.action, data)
                    .then(response => {
                        this.submiting = false

                        if (response.status == 201) {
                            this.$message.success('The world has been added.')
                        } else if (response.status == 200) {
                            this.$message.success('The world has been updated.')
                        }
                        this.$router.push({ name: 'admin.worlds' })
                    })
                    .catch(() => {
                        this.validation()
                        this.submiting = false
                    })
            },
        },
    }
</script>