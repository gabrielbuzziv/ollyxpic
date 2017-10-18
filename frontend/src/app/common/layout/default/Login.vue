<template>
    <el-dialog title="Admin Panel" size="tiny" :visible.sync="visible">
        <form action="" @submit.prevent="onLogin">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="mdi mdi-email"></i>
                    </div>

                    <input type="text" class="form-control" placeholder="Not that secret e-mail" v-model="user.email" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="mdi mdi-key-variant"></i>
                    </div>

                    <input type="password" class="form-control" placeholder="Super secret password" v-model="user.password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-block margin-bottom-0" :disabled="submiting">
                Login
            </button>
        </form>
    </el-dialog>
</template>

<script>
    import { isEmpty, forEach } from 'lodash'

    export default {
        data () {
            return {
                visible: false,
                submiting: false,
                user: {
                    email: '',
                    password: ''
                }
            }
        },

        methods: {
            load () {
                this.visible = true
            },

            onLogin () {
                const user = this.user

                this.submiting = true
                this.$store.dispatch('global/ON_LOGIN', { ...user })
                    .then(() => {
                        this.$store.dispatch('global/FETCH_USER')
                            .then(response => {
                                this.submiting = false
                                this.visible = false
                            })
                            .catch(() => this.submiting = false)
                    })
                    .catch (error => {
                        this.validation()
                        this.submiting = false
                    })
            }
        },

        mounted () {
            this.$root.$on('show::login', () => this.load())
        },

        beforeDestroy () {
            this.$root.$off('show::login')
        }
    }
</script>