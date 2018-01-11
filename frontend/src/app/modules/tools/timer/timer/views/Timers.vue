<template>
    <page-load id="timer">
        <page-title>
            <div class="pull-right">
                <div class="add" v-if="adding">
                    <select name="boss" class="form-control" v-model="boss">
                        <option :value="boss.id" v-for="boss in bosses">{{ boss.name }}</option>
                    </select>

                    <input type="text" class="form-control" placeholder="Character" v-model="character">

                    <button class="btn btn-success" @click.prevent="add">Add</button>
                </div>

                <button class="btn btn-success" @click.prevent="adding = true" v-else>
                    <i class="mdi mdi-plus margin-right-5"></i>
                    Add Timer
                </button>
            </div>

            <img :src="image_path_by_name('item', 'Ancient Watch')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Task & Boss</h2>
                <span>Timer</span>
            </div>
        </page-title>

        <div class="row timers" v-if="timers">
            <div class="col-md-4" v-for="timer in timers">
                <timer :timer="timer" :boss="getBoss(timer.boss)" :timers="timers"/>
            </div>
        </div>

        <panel v-else>
            The <b>Task & Boss Timer</b> will help you to organize you boss timers for every character you have.<br>
            You can manage multiple timers for multiple bosses and characters, all data will be stored in you browser,
            so you just need to create the timers one time and they will be there for you.<br><br>

            To start using, click on the button
            <span class="label label-success">
                <i class="mdi mdi-plus margin-right-5"></i>
                Add Timer
            </span>
        </panel>
    </page-load>
</template>

<script type="text/babel">
    import Timer from './Timer'

    export default {
        components: { Timer },

        data () {
            return {
                adding: false,
                boss: 2349,
                character: '',
                timers: [],
                bosses: [
                    { id: 2349, name: 'Kroazur', respawn: 120 },
                    { id: 2400, name: 'Bloodback', respawn: 1200 },
                    { id: 2401, name: 'Darkfang', respawn: 1200 },
                    { id: 2402, name: 'Sharpclaw', respawn: 1200 },
                    { id: 2403, name: 'Black Vixen', respawn: 1200 },
                    { id: 2404, name: 'Shadowpelt', respawn: 1200 },
                    { id: 1940, name: 'Warzone I', respawn: 1200 },
                    { id: 1939, name: 'Warzone II', respawn: 1200 },
                    { id: 1941, name: 'Warzone III', respawn: 1200 },
                    { id: 2405, name: 'Warzone IV', respawn: 1200 },
                    { id: 2407, name: 'Warzone V', respawn: 1200 },
                    { id: 2408, name: 'Warzone VI', respawn: 1200 },
                    { id: 2240, name: 'Lady Tenebris', respawn: 1200 },
                    { id: 2262, name: 'Lloyd', respawn: 1200 },
                    { id: 2276, name: 'Thorn Knight', respawn: 1200 },
                    { id: 2235, name: 'Dragonking Zyrtarch', respawn: 1200 },
                    { id: 2275, name: 'Melting Frozen Horror', respawn: 1200 },
                    { id: 2239, name: 'The Time Guardian', respawn: 1200 },
                    { id: 2189, name: 'Anomaly', respawn: 1200 },
                    { id: 2212, name: 'Rupture', respawn: 1200 },
                    { id: 2209, name: 'Realityquake', respawn: 1200 },
                    { id: 2208, name: 'Eradicator', respawn: 1200 },
                    { id: 2206, name: 'Outburst', respawn: 1200 },
                ]
            }
        },

        methods: {
            add () {
                if (this.character == null || this.character == '') {
                    this.$message.error('You need to fill the character field.')
                    return false
                }

                const timer = { boss: this.boss, character: this.character, last_time: null }
                this.timers && this.timers.length
                    ? this.timers.push(timer)
                    : this.timers = [timer]

                let timers = JSON.parse(localStorage.getItem('timers')) || []

                timers.length
                    ? timers.push(timer)
                    : timers = [timer]

                localStorage.setItem('timers', JSON.stringify(timers))

                this.adding = false
            },

            getBoss (id) {
                const index = this.bosses.map(boss => boss.id).indexOf(id)
                return this.bosses[index]
            },
        },

        mounted () {
            this.timers = JSON.parse(localStorage.getItem('timers'))
        }
    }
</script>
