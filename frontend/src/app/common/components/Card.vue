<template>
    <div :class="`col-md-${columns}`">

        <div class="card">
            <div class="header">
                <img :src="image">
                <span class="title">{{ title }}</span>
            </div>

            <div class="content">
                <p class="description">
                    <slot></slot>
                </p>

                <router-link :to="to" class="btn btn-primary" tag="button">
                    Calculate
                    <i class="mdi mdi-chevron-right"></i>
                </router-link>
            </div>
        </div>

    </div>
</template>

<script type="text/babel">
    export default {
        props: {
            columns: {
                type: Number,
                default: 4
            },
            to: {
                type: Object,
                required: true
            },
            title: {
                type: String,
                required: true
            },
            image: {
                type: String,
                required: true
            },
        }
    }
</script>
