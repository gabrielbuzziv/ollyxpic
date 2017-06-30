<template>
    <div class="npc-card">
        <div class="image">
            <img :src="image_path('npc', npc.id)" alt="">
        </div>

        <div class="info">
            <b>{{ npc.name }}</b>
            <p>{{ npc.city | capitalize }}</p>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: ['npc']
    }
</script>