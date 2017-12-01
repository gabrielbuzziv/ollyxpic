<template>
    <page-load id="capacity">
        <page-title>
            <img :src="image_path_by_name('item', 'blossom bag')" class="margin-right-5">
            <div class="title">
                <h2>Capacity</h2>
                <span>Count</span>
            </div>
        </page-title>

        <div class="row">
            <div class="col-md-6">
                <panel>
                    <el-radio-group v-model="voc">
                        <el-radio-button label="Knight"></el-radio-button>
                        <el-radio-button label="Paladin"></el-radio-button>
                        <el-radio-button label="Druid"></el-radio-button>
                        <el-radio-button label="Sorcerer"></el-radio-button>
                    </el-radio-group>
                </panel>
            </div>

            <div class="col-md-6">
                <panel>
                    <form-input placeholder="Your Level" v-model="level"/>
                </panel>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4" v-for="tier in tiers">
                <panel class="bonus">
                    <h3><span>Tier {{ tier.tier }}</span> {{ tier.percentage }} increase bonus</h3>

                    <span class="data">
                        <span>Capacity</span>
                        {{ capacity.toFixed() }} oz.
                    </span>

                    <i class="mdi mdi-plus"></i>

                    <span class="data">
                        <span>Cap. Bonus</span>
                        {{ calculateCapacity(tier.bonus).toFixed() }} oz.
                    </span>

                    <i class="mdi mdi-arrow-right"></i>

                    <span class="data">
                        <span>Result</span>
                        {{ calculateCapacity(tier.total).toFixed() }} oz.
                    </span>
                </panel>
            </div>
        </div>


    </page-load>
</template>

<script type="text/babel">

    export default {
        data () {
            return {
                total: '',
                capperc: '',
                level: '',
                voc: '',
                tiers: [
                    { tier: 1, percentage: '3%', bonus: 0.03, total: 1.03 },
                    { tier: 2, percentage: '8%', bonus: 0.08, total: 1.08 },
                    { tier: 3, percentage: '15%', bonus: 0.15, total: 1.15 },
                ]
            }
        },

        computed: {
            capacity () {
                switch (this.voc) {
                    case 'Knight':
                        return ((this.level - 8) * 25 + 470)
                    case 'Paladin':
                        return ((this.level - 8) * 20 + 470)
                    case 'Druid':
                        return ((this.level - 8) * 10 + 470)
                    case 'Sorcerer':
                        return ((this.level - 8) * 10 + 470)
                    default:
                        return (this.level * 0)
                }
            },
        },

        methods: {
            calculateCapacity (percentage) {
                return this.capacity * percentage;
            },
        }
    }
</script>













