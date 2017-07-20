<template>
    <page-load id="blessings">
        <page-title>
            <img :src="image_path('item', 862)" alt="">
            Boss
            <span>MVP</span>
        </page-title>

        <panel>
            <form action="" ref="form" @submit.prevent="onSubmit">
                <form-group label="Title">
                    <form-input name="title" placeholder="Title"/>
                </form-group>

                <form-group label="Server Log">
                    <form-textarea name="log" rows="12" placeholder="Paste your serve log here"/>
                </form-group>

                <button class="btn btn-success" type="submit" :disabled="calculating">
                    <span v-if="calculating">
                        <i class="mdi mdi-loading"></i>
                        Calculating
                    </span>
                    <span v-else>Find MVP</span>
                </button>
            </form>
        </panel>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                calculating: false
            }
        },

        methods: {
            onSubmit () {
                const form = this.$refs.form

                this.calculating = true

                services.calculate(new FormData(form))
                        .then(response => {
                            this.calculating = false
                            this.$router.push({ name: 'calculators.mvp.result', params: { id: response.data.id }})
                        })
                        .catch(error => {
                            this.calculating = false
                        })
            }
        }
    }
</script>