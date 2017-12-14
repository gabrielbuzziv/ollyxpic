<template>
    <panel>
        <div class="content">
            <h3>Creatures</h3>

            <div class="creatures">
                <div class="creature" v-for="creature in creatures">
                    <div class="thumb">
                        <img :src="image_path('creature', creature.id)"/>
                    </div>
                    <span class="title">{{ creature.title }}</span>
                </div>
            </div>

            <header>
                <h3 class="margin-top-30">Loot</h3>

                <el-radio-group v-model="lootPrice">
                    <el-radio-button label="All"></el-radio-button>
                    <el-radio-button label="above 1k"></el-radio-button>
                    <el-radio-button label="above 5k"></el-radio-button>
                    <el-radio-button label="above 10k"></el-radio-button>
                </el-radio-group>
            </header>

            <div class="loots">
                <div class="loot" v-for="loot in loots">
                    <div class="thumb">
                        <img :src="image_path('item', loot.id)"/>
                    </div>
                    <span class="title">{{ loot.title }}</span>
                    <span class="price">{{ loot.actual_value | format }} gp</span>
                </div>
            </div>
        </div>
    </panel>
</template>

<script>
    Number.prototype.format = function (n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~ ~ n)).replace(new RegExp(re, 'g'), '$&.');
    };

    export default {
        props: ['creatures'],

        data () {
            return {
                lootPrice: 'All'
            }
        },

        computed: {
            loots () {
                return this.creatures && this.creatures.length
                    ? this.creatures.map(creature => creature.drops)
                        .reduce((carry, drop) => carry.concat(drop))
                        .sort((a, b) => b.actual_value - a.actual_value)
                        .filter((value, index, self) => self.map(v => v.id).indexOf(value.id) === index)
                        .filter(loot => {
                            switch (this.lootPrice) {
                                case 'above 1k':
                                    return loot.actual_value >= 1000
                                case 'above 5k':
                                    return loot.actual_value >= 5000
                                case 'above 10k':
                                    return loot.actual_value >= 10000
                                default:
                                    return loot.actual_value >= 0
                            }
                        })
                    : []
            }
        },

        filters: {
            format (value) {
                return value.format()
            }
        },

        methods: {
            getPriceClass (price) {
                switch (true) {
                    case price >= 10000:
                        return 'high'
                    case price >= 5000:
                        return 'mid'
                    case price >= 1000:
                        return 'low'
                }
            }
        }
    }
</script>