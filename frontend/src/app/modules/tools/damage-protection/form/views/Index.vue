<template>
    <page-load id="equipments">
        <page-title>
            <img :src="image_path_by_name('item', 'great shield')" alt="">
            <div class="title">
                <h2>Damage</h2>
                <span>Protection</span>
            </div>
        </page-title>

        <equipments :slots.sync="slots" />
        <status :slots.sync="slots" />
    </page-load>
</template>

<script>
    import Equipments from './Equipments'
    import Status from './Status'
    import services from '../services'

    export default {
        components: { Equipments, Status },

        data () {
            return {
                slots: {
                    amulet: [],
                    helmet: [],
                    backpack: [],
                    weapon: [],
                    armor: [],
                    shield: [],
                    ring: [],
                    legs: [],
                    ammunition: [],
                    boots: [],
                }
            }
        }
    }
</script>