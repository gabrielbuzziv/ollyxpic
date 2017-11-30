<template>
    <page-load id="blessings">
        <page-title>
            <img :src="image_path_by_name('item', 'medal of honour')" alt="">
            Warzone
            <span>MVP's</span>
        </page-title>

	<div class="row">
	    <div class="col-md-12">
		<panel>
	<center><b>This calculator MVP's during warzones, it is recomended to clear server log before starting on a new boss,<br> As the spam can cause the accuracy of the result.</b></center>
		</panel>
	    </div>
	</div>

        <form action="" ref="form" @submit.prevent="onSubmit">
            <div class="row">
                <div class="col-md-3">
                    <panel>
                        <el-radio name="warzone" v-model="warzone" :label="1" class="block">
                            <img :src="image_path('creature', 747)" class="margin-right-10 margin-left-20">
                            <span>Warzone 1</span>
                        </el-radio>
                    </panel>

                    <panel>
                        <el-radio name="warzone" v-model="warzone" :label="2" class="block">
                            <img :src="image_path('creature', 746)" class="margin-right-10 margin-left-20">
                            <span>Warzone 2</span>
                        </el-radio>
                    </panel>

                    <panel>
                        <el-radio name="warzone" v-model="warzone" :label="3" class="block">
                            <img :src="image_path('creature', 748)" class="margin-right-10 margin-left-20">
                            <span>Warzone 3</span>
                        </el-radio>
                    </panel>
                </div>
                <div class="col-md-9">
                    <panel title="Server Log" icon="view-list">
                        <form-group class="margin-bottom-0">
                            <form-textarea name="log" rows="12" placeholder="Paste your serve log here"/>
                        </form-group>
                    </panel>

                    <button class="btn btn-danger btn-block btn-lg" type="submit" :disabled="calculating">
                        <span v-if="calculating">
                            <i class="mdi mdi-loading margin-right-10"></i>
                            Searching the best player of Warzone {{ warzone }}
                        </span>

                        <span v-else>
                            Let me know the MVP's
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                calculating: false,
                warzone: 1
            }
        },

        methods: {
            onSubmit () {
                const form = this.$refs.form

                this.calculating = true

                services.calculate(new FormData(form))
                        .then(response => {
                            this.calculating = false
                            this.$router.push({ name: 'tools.mvp.result', params: { id: response.data.id } })
                        })
                        .catch(error => {
                            if (error.type && error.type == 'validation') {
                                let validateMessage = error.data.log[0]

                                this.$notify.error({
                                    title: 'Validation',
                                    message: validateMessage
                                })
                            } else {
                                this.$notify.error({
                                    title: '= (',
                                    message: error.data.message
                                })
                            }
//                            this.$message.error('Ops, something went wrong (Right log? Right Boss? Filled fields?).')

                            this.calculating = false
                        })
            }
        }
    }
</script>
