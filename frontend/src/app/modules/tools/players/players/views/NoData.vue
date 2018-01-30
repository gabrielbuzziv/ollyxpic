<template>
    <div class="alert alert-warning no-data">
        <h4>{{ title }}</h4>
        <p>{{ message }}</p>
    </div>
</template>

<script>
    export default {
        props: ['title', 'message']
    }
</script>