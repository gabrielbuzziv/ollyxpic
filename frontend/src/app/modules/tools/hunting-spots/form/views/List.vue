<template>
    <page-load id="hunting-spots">
        <page-title>
            <div class="pull-right">
                <router-link :to="{ name: 'tools.spots.form' }" class="btn btn-default">
                    <i class="mdi mdi-plus-circle margin-right-5"></i>
                    Share your spot
                </router-link>
            </div>

            <img :src="image_path_by_name('item', 'Map (Brown)')" alt="">
            Hunting
            <span>Spots</span>
        </page-title>

        <div class="vocations">
            <el-checkbox-group v-model="filterVocations">
                <el-checkbox-button v-for="vocation in vocations" :label="vocation.title" :key="vocation.id">
                    {{ vocation.title }}
                </el-checkbox-button>
            </el-checkbox-group>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="filters">
                    <panel>Filters here</panel>
                </div>
            </div>

            <div class="col-md-9">
                <div class="list">
                    <panel>
                        List here
                    </panel>
                </div>
            </div>
        </div>
    </page-load>
</template>

<script>
    export default {
        data () {
            return {
                filterVocations: []
            }
        },

        computed: {
            vocations () {
                return this.$store.getters['spots/GET_VOCATIONS']
            }
        },

        methods: {
        },

        mounted () {
            this.$store.dispatch('spots/FETCH_VOCATIONS')
        }
    }
</script>