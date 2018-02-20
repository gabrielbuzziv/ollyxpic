<template>
    <tr class="imbuement">
        <td>
            <span class="title">{{ imbuement.title }}</span>
            <span class="subtitle">
                {{ imbuement.name }}

                <el-tooltip :content="imbuement.description" placement="right">
                    <i class="mdi mdi-information"></i>
                </el-tooltip>
            </span>
        </td>
        <td>
            <select class="form-control" v-model="imbuement.amount">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </td>
        <td class="item">
            <imbuement-price :imbuement="imbuement" :item="basic" tier="basic" />
        </td>
        <td class="item">
            <imbuement-price :imbuement="imbuement" :item="intricate" tier="intricate" />
        </td>
        <td class="item">
            <imbuement-price :imbuement="imbuement" :item="powerful" tier="powerful" />
        </td>
        <td class="text-right" width="110">
            <el-switch off-text="" on-text="" v-model="imbuement.success" />
        </td>
    </tr>
</template>

<script>
    import ImbuementPrice from './ImbuementPrice'

    export default {
        props: ['imbuement'],

        components: { ImbuementPrice },

        computed: {
            basic () {
                return this.imbuement.items.filter(item => item.tier == 1)[0]
            },

            intricate () {
                return this.imbuement.items.filter(item => item.tier == 2)[0]
            },

            powerful () {
                return this.imbuement.items.filter(item => item.tier == 3)[0]
            },
        },
    }
</script>