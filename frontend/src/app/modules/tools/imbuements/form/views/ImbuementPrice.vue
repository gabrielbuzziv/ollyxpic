<template>
    <div>
        <div class="price">
            <div class="input-group">
                <div class="input-group-addon" :class="{ 'disabled': imbuement[tier].token }">
                    <el-tooltip :content="`${item.amount} ${item.item.title}`" placement="top">
                        <img :src="image_path('item', item.item.id)" />
                    </el-tooltip>
                </div>

                <input type="text"
                       class="form-control"
                       :disabled="imbuement[tier].token"
                       v-model="imbuement[tier].price">
            </div>
        </div>

        <button class="gold-token"
                :class="{ 'active': imbuement[tier].token }"
                @click="toggleToken(imbuement[tier])"
                v-if="goldToken">
            <el-tooltip :content="`Use gold token to buy ${item.amount} ${item.item.title}`" placement="top">
                <img :src="image_path_by_name('item', 'gold token')">
            </el-tooltip>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['imbuement', 'item', 'tier', 'goldToken'],

        methods: {
            toggleToken (imbuement) {
                if (this.tier == 'powerful') {
                    this.imbuement['powerful'].token = ! this.imbuement['powerful'].token
                    this.imbuement['intricate'].token = this.imbuement['powerful'].token
                    this.imbuement['basic'].token = this.imbuement['powerful'].token
                }

                if (this.tier == 'intricate') {
                    this.imbuement['powerful'].token = false
                    this.imbuement['intricate'].token = ! this.imbuement['intricate'].token
                    this.imbuement['basic'].token = this.imbuement['intricate'].token
                }

                if (this.tier == 'basic') {
                    this.imbuement['powerful'].token = false
                    this.imbuement['intricate'].token = false
                    this.imbuement['basic'].token = ! this.imbuement['basic'].token
                }
            }
        }
    }
</script>