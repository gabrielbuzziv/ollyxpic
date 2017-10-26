<template>
    <page-load id="speedboost">
        <page-title>
            <img :src="image_path('item', 1)" class="margin-right-5">
            Exp
            <span class="margin-left-45">Share</span>
        </page-title>

        <div class="col-md-12">
            <panel class="form">
                <div class="row">
                    <div class="col-md-6">
                        <form-input placeholder="Level" v-model="level" />
                    </div>

                    <div class="col-md-6">

                    </div>
                </div>

            <panel class="shareexp" v-if="level">
                Minimum level to share with you: <b>{{ minShare }}</b> And maximum level to share with you: <b>{{ maxShare }}</b>
            </panel>
        </div>

    </page-load>
</template>

<script type="text/babel">
    import { debounce } from 'lodash'

    export default {

            minShare () {
                return parseInt(this.level) / 3 * 2;
            },

            maxShare () {
                return parseInt(this.level) * 3 / 2;
            },
        },
</script>