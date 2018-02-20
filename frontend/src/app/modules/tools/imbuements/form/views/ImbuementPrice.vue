<template>
    <div>
        <div class="price">
            <div class="input-group">
                <div class="input-group-addon">
                    <el-tooltip :content="`${item.amount} ${item.item.title}`" placement="top">
                        <img :src="image_path('item', item.item.id)" />
                    </el-tooltip>
                </div>

                <input type="text"
                       class="form-control"
                       placeholder="Price"
                       @input="$emit('updated')"
                       v-model="imbuement[tier]">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['imbuement', 'item', 'tier']
    }
</script>