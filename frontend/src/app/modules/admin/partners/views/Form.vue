<template>
    <panel>
        <form action="" ref="form" @submit.prevent="onSubmit">
            <input type="hidden" name="_method" :value="method">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" placeholder="Partner name" required v-model="partner.name">
            </div>

            <div class="form-group">
                <label>Logo</label>
                <input type="text" name="logo" class="form-control" placeholder="Partner logo" v-model="partner.logo">
                <small class="helper-block">
                    e.g. /src/assets/images/partners/tibiawikia.png
                </small>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Site</label>
                    <input type="text" name="site" class="form-control" placeholder="Partner's site" v-model="partner.site">
                </div>

                <div class="form-group col-md-4">
                    <label>Twitch</label>
                    <input type="text" name="twitch" class="form-control" placeholder="Twitch.tv Channel" v-model="partner.twitch">
                </div>

                <div class="form-group col-md-4">
                    <label>Site</label>
                    <input type="text" name="youtube" class="form-control" placeholder="Youtube Channel" v-model="partner.youtube">
                </div>
            </div>

            <div class="margin-top-20">
                <button class="btn btn-success btn-rounded">
                    <i class="mdi mdi-check-circle margin-right-10"></i>
                    Save
                </button>

                <router-link :to="{ name: 'admin.partners' }" class="btn btn-blank">
                    Cancel
                </router-link>
            </div>
        </form>
    </panel>
</template>

<script>
    import services from '../services'

    export default {
        props: ['action', 'method', 'partner'],

        methods: {
            onSubmit () {
                const form = this.$refs.form

                services.save(this.action, new FormData(form))
                    .then(response => {
                        this.loading = false
                        this.$message.success('Partner saved.')
                        this.$router.push({ name: 'admin.partners' })
                    })
            }
        }
    }
</script>