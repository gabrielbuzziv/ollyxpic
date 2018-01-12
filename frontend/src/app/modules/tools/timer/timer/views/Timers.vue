<template>
    <page-load id="timer">
        <page-title>
            <img :src="image_path_by_name('item', 'Ancient Watch')" alt="" class="margin-right-5">
            <div class="title">
                <h2>Task & Boss</h2>
                <span>Timer</span>
            </div>
        </page-title>

        <div class="alert alert-info">
            <p>We changes the timer structure and forced reset the timers, now you have more possibilites and is working better.</p>
        </div>

        <panel class="addPanel">
            <div class="row">
                <div class="col-md-5">
                    <el-cascader
                            :options="options"
                            v-model="option"
                            placeholder="Boss / Task"
                            filterable/>
                </div>

                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Character name" v-model="newTimer.character">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary btn-rounded btn-block" @click.prevent="add">
                        <i class="mdi mdi-check-circle margin-right-5"></i>
                        Add
                    </button>
                </div>
            </div>
        </panel>

        <div class="row timers" v-if="timers">
            <div class="col-md-4" v-for="timer in timers">
                <timer :timer="timer" :timers="timers"/>
            </div>
        </div>
    </page-load>
</template>

<script type="text/babel">
    import Timer from './Timer'
    import Options from './Options'

    export default {
        components: { Timer },

        data () {
            return {
                newTimer: { character: null },
                timers: [],
                option: [],
            }
        },

        computed: {
            options () {
                return [{
                    label: 'Bosses',
                    value: 'bosses',
                    children: [
                        { label: 'First Dragon', value: 1, children: this.getOption(1, 5) },
                        { label: 'Ferumbras\' Ascencion', value: 2, children: this.getOption(6, 13) },
                        { label: 'Hearth of Destruction', value: 3, children: this.getOption(14, 19) },
                        { label: 'Forgotten Knowledge', value: 4, children: this.getOption(20, 26) },
                        { label: 'Feyrist', value: 5, children: this.getOption(27, 27) },
                        { label: 'Cults of Tibia', value: 6, children: this.getOption(28, 34) },
                        { label: 'The Curse Spreads', value: 7, children: this.getOption(35, 39) },
                        { label: 'Warzone', value: 8, children: this.getOption(40, 45) },
                    ],
                },{
                    label: 'Tasks',
                    value: 'tasks',
                    children: [
                        { label: 'Bigfoot Burden', value: 9, children: this.getOption(46, 53) },
                        { label: 'Rottin Wood', value: 10, children: this.getOption(54, 54) },
                    ],
                }]
            },
        },

        methods: {
            add () {
                if (! this.isValid())
                    return false

                const timer = { id: this.option[2], character: this.newTimer.character, last_time: null, type: this.option[0] }
                let timers = JSON.parse(localStorage.getItem('boss_task_timers')) || []

                timers.length
                    ? timers.push(timer)
                    : timers = [timer]

                localStorage.setItem('boss_task_timers', JSON.stringify(timers))
                this.timers = timers

                this.newTimer = { character: null }
                this.option = []
            },

            isValid () {
                if (this.option == null || this.option == '') {
                    this.$message.error('Your need to select a boss or a task.')
                    return false
                }

                if (this.newTimer.character == null || this.newTimer.character == '') {
                    this.$message.error('Your need to fill the character name.')
                    return false
                }

                return true
            },

            getOption (start, end) {
                return Options.filter(option => option.id >= start && option.id <= end).map(option => ({
                    label: option.name,
                    value: option.id
                }))
            }
        },

        mounted () {
            this.timers = JSON.parse(localStorage.getItem('boss_task_timers'))
        }
    }
</script>
