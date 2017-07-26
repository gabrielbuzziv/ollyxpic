<template>
    <page-load id="damage">
        <page-title>
            <img :src="item_path('sudden-death-rune')" alt="">
            Damage
            <span>Something here</span>
        </page-title>

        <div class="row">
            <div class="col-md-12">
                <panel class="form">
                    <div class="row">
                        <form-group :columns="3">
                            <form-input v-model="level" placeholder="Level" />
                        </form-group>

                        <form-group :columns="3">
                            <form-input v-model="magiclevel" placeholder="Magic Level" />
                        </form-group>

                        <form-group :columns="3">
                            <form-input v-model="melee" placeholder="Melee Skill" />
                        </form-group>

                        <form-group :columns="3">
                            <form-input v-model="attack" placeholder="Weapon Damage" />
                        </form-group>
                    </div>

                    <div class="row">
                        <form-group label="Vocation" class="margin-bottom-0" :columns="5">
                            <el-radio v-model="vocation" :label="1">Knight</el-radio>
                            <el-radio v-model="vocation" :label="2">Paladin</el-radio>
                            <el-radio v-model="vocation" :label="3">Druid</el-radio>
                            <el-radio v-model="vocation" :label="4">Sorcerer</el-radio>
                        </form-group>

                        <form-group label="Critical Damage" class="margin-bottom-0" :columns="5">
                            <el-radio v-model="critical" :label="0">0%</el-radio>
                            <el-radio v-model="critical" :label="10">10%</el-radio>
                            <el-radio v-model="critical" :label="25">25%</el-radio>
                            <el-radio v-model="critical" :label="50">50%</el-radio>
                        </form-group>

                        <form-group label="Creature">
                            <el-checkbox v-model="creature" class="margin-bottom-0" :label="true">
                                <img :src="image_path('creature', 2)" alt="">
                            </el-checkbox>
                        </form-group>
                    </div>
                </panel>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <panel>
                    <table>

                    </table>
                </panel>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <panel>
                    <table>

                    </table>
                </panel>
            </div>

            <div class="col-md-8">
                <panel>
                    <table></table>
                </panel>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'
    import { debounce, isEmpty } from 'lodash'

    export default {
        data () {
            return {
                level: '',
                magiclevel: '',
                melee: '',
                vocation: 1,
                critical: 0,
                creature: false
            }
        }
    }
</script>