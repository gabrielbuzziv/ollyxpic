<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'letter')" class="margin-right-5">
            Huntingspots
            <span class="margin-left-80">Submit</span>
        </page-title>

        <panel>
            <form>
                <div class="row">
                    <form-group label="Title" :columns="6">
                        <form-input name="title" placeholder="huntingspot title" required />
                    </form-group>

                    <form-group label="Vocation" :columns="6">
                        <form-input name="vocation" placeholder="vocation for this spot" required />
                    </form-group>
                </div>

                <div class="row">
                    <form-group label="Exp" :columns="6">
                        <form-input name="exp" placeholder="average exp per hour" required />
                    </form-group>

                    <form-group label="Profit" :columns="6">
                        <form-input name="profit" placeholder="average profit per hour" required />
                    </form-group>
                </div>

                <div class="row">
                    <form-group label="Level" :columns="6">
                        <form-input name="level" placeholder="recomended level" required />
                    </form-group>

                    <form-group label="Supplies" :columns="6">
                        <form-input name="supplies" placeholder="average supplies used" required />
                    </form-group>
                </div>


                <form-group label="Creatures (add multiple)">
                    <form-input name="creatures" placeholder="creatures in this spot" required />
                </form-group>


                <form-group label="Description">
                    <textarea name="description" class="form-control" rows="5" required placeholder="descripe the huntingspot as good as possible"></textarea>
                </form-group>

                <div class="row">
                    <form-group label="Submitter" :columns="6">
                        <form-input name="submitter" placeholder="your name" required />
                    </form-group>
                </div>

                    <button class="btn btn-success" type="submit">
                        <span>Send</span>
                    </button>
            </form>
        </panel>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'

    export default {
        data () {
            return {
                sending: false
            }
        },

        methods: {

        }
    }