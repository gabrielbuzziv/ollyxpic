<template>
    <panel class="rating loyalty" v-if="loyalty">
        <page-load class="no-padding" :loading="loading">
            <h4>Loyalty</h4>

            <div class="rate">
                <div class="data">
                    <b>{{ loyalty.level }}</b>
                    <span>points</span>
                </div>

                <el-rate
                        v-model="bonusRate"
                        disabled
                        show-text
                        :text-template="`${bonus}% increase skills`">
                </el-rate>
            </div>
        </page-load>
    </panel>
</template>

<script>
    export default {
        props: ['character', 'skills', 'loading'],

        computed: {
            loyalty () {
                return this.skills ? this.skills.filter(skill => skill.skill == 'loyalty')[0] : false
            },

            bonus () {
                return this.loyalty
                    ? Math.floor((this.loyalty.level / 360)) * 5 <= 50
                        ? Math.floor((this.loyalty.level / 360)) * 5
                        : 50
                    : 0
            },

            bonusRate () {
                return this.bonus / 10
            }
        }
    }
</script>