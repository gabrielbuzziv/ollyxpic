<template>
    <page-load id="expshare">
        <page-title>
            <img :src="image_path_by_name('item', 'purple tome')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Exp</h2>
                <span>Share</span>
            </div>
        </page-title>

        <panel>
            <div class="col-md-2">
                <div class="level-range">
                    <b>Min Level</b>
                    <span>{{ minShare | fixed }}</span>
                </div>
            </div>

            <div class="col-md-2 col-md-offset-3">
                <div class="level-range">
                    <b>Your Level</b>
                    <form-input placeholder="Your Level" v-model="level"/>
                </div>
            </div>

            <div class="col-md-2 col-md-offset-3">
                <div class="level-range">
                    <b>Max Level</b>
                    <span>{{ maxShare | fixed }}</span>
                </div>
            </div>
        </panel>
    </page-load>
</template>

<script type="text/babel">

    export default {
        data () {
            return {
                level: ''
            }
        },

        computed: {
            minShare () {
                return this.level.length ? parseInt(this.level) / 3 * 2 : null;
            },

            maxShare () {
                return this.level.length ? parseInt(this.level) * 3 / 2 : null;
            },
        },

        filters: {
            fixed (value) {
                return value != null && value != '' && typeof value == 'number' ? value.toFixed() : '-'
            }
        }
    }


</script>













