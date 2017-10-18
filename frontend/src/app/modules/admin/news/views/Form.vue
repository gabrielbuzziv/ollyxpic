<template>
    <form action="" method="POST" @submit.prevent="onSubmit" ref="form">
        <input type="hidden" name="_method" :value="method">

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" v-model="data.title" required>
        </div>

        <div class="form-group">
            <label>Body</label>
            <vue-summernote ref="editor" :height="300" placeholder=""></vue-summernote>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label>Schedule</label>
                <div class="input-group">
                    <input type="text" class="form-control" :disabled="scheduleDisabled" v-model="data.created_at" v-mask="'##-##-#### ##:##'">
                    <div class="input-group-addon">
                        <div class="checkbox margin-top-0 margin-bottom-0">
                            <label>
                                <input type="checkbox" class="margin-top-5" v-model="scheduleDisabled"> Immediately
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label>Active</label>
                <select name="active" class="form-control" v-model="data.active">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>

        <div class="margin-top-40">
            <button type="submit" class="btn btn-success" :disabled="submiting">
                <i class="mdi mdi-check margin-right-5"></i>
                Save <span v-if="submiting">...</span>
            </button>

            <router-link :to="{ name: 'admin.news.list' }" class="btn btn-blank">
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
                scheduleDisabled: true,
                submiting: false
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
                            this.$message.success('The post has been added.')
                        } else if (response.status == 200) {
                            this.$message.success('The post has been updated.')
                        }
                        this.$router.push({ name: 'admin.news.list' })
                    })
                    .catch(() => {
                        this.validation()
                        this.submiting = false
                    })
            }
        },

        mounted () {
            setTimeout(() => {
                const editor = this.$refs.editor
                editor.$on('onChange', content => this.data.body = content)
                editor.run('code', this.data.body)
            }, 100)
        }
    }
</script>