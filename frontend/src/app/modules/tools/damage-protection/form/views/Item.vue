<template>
    <el-popover
            placement="top-start"
            trigger="hover"
            :title="item.title">

        <span class=""></span>

        <div class="item" :class="{ 'active': active }" slot="reference">
            <img :src="image_path('item', item.id)">
        </div>
    </el-popover>
</template>

<script>
    export default {
        props: ['item', 'active']
    }
</script>