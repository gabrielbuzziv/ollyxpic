<template>
    <div v-if="item.id">
        <img :src="image_path('item', item.id)" />
    </div>
</template>

<script>
    export default {
        props: ['item']
    }
</script>