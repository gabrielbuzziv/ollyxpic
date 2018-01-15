<template>
    <panel class="timer" :class="[timer.type == 'bosses' ? 'boss' : 'task']">
        <div class="thumb" v-if="timer.type == 'bosses'">
            <img :src="image_path(option.type, option.image)" alt="">
        </div>

        <div class="data">
            <span class="name">{{ option.name }}</span>
            <span class="character">
                <i class="mdi mdi-account"></i>
                {{ timer.character }}
            </span>

            <div class="info">
                <div class="countdown" v-if="countdown">
                    <i class="mdi mdi-timer"></i>
                    {{ countdown }}
                </div>

                <div class="available" v-else>
                    <i class="mdi mdi-check-circle margin-right-5"></i>
                    {{ timer.type == 'bosses' ? 'Boss' : 'Task' }} Available
                </div>

                <div class="buttons">
                    <button class="btn btn-rounded" @click.prevent="reset" v-if="! countdown">
                        <i class="mdi mdi-timer margin-right-5"></i>
                        Start Timer
                    </button>

                    <button class="btn btn-rounded" @click.prevent="reset" v-if="countdown">
                        <i class="mdi mdi-refresh margin-right-5"></i>
                        Reset Timer
                    </button>

                    <button class="btn btn-rounded" @click.prevent="cancel" v-if="countdown">
                        <i class="mdi mdi-timer-off margin-right-5"></i>
                        End Timer
                    </button>

                    <button class="btn btn-rounded" title="Remove timer" @click.prevent="remove">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            </div>
        </div>
    </panel>
</template>

<script>
    import Options from './Options'

    export default {
        props: ['timer', 'timers'],

        data () {
            return {
                countdown: 0,
                interval: null
            }
        },

        computed: {
            option () {
                return Options[Options.map(option => option.id).indexOf(this.timer.id)]
            }
        },

        methods: {
            startTimer () {
                const waitTime = this.option.time
                const lastTime = this.timer.last_time
                const nextRespawn = moment(lastTime).add(waitTime, 'minutes')

                if (this.timer.last_time != null) {
                    const diffTime = moment(nextRespawn).diff(moment())
                    const duration = moment.duration(diffTime - 1)

                    if (duration.days() < 3) {
                        this.countdown = this.getHours(duration.asHours()) + moment.utc(duration.asMilliseconds()).format(":mm:ss")
                        this.interval = setInterval(() => {
                            const diffTime = moment(nextRespawn).diff(moment())
                            const duration = moment.duration(diffTime)

                            if (duration.asMilliseconds() <= 0) {
                                this.countdown = 0;
                                this.timer.last_time = null;
                            } else {
                                this.countdown = this.getHours(duration.asHours()) + moment.utc(duration.asMilliseconds()).format(":mm:ss")
                            }
                        }, 1000)
                    } else {
                        this.countdown = `${duration.days() + 1} days`
                    }
                } else {
                    clearInterval(this.interval)
                }
            },

            getHours (hours) {
                hours = Math.floor(Math.abs(hours))

                return hours >= 10 ? hours : `0${hours}`
            },

            reset () {
                clearInterval(this.interval)
                this.timer.last_time = moment()

                const index = this.timers.indexOf(this.timer)
                this.timers[index].last_time = moment()
                localStorage.setItem('boss_task_timers', JSON.stringify(this.timers))

                this.startTimer()
            },

            cancel () {
                clearInterval(this.interval)
                this.timer.last_time = null
                this.countdown = 0

                const index = this.timers.indexOf(this.timer)
                this.timers[index].last_time = null
                localStorage.setItem('boss_task_timers', JSON.stringify(this.timers))

                this.startTimer()
            },

            remove () {
                this.$confirm('Are you sure you want to remove this timer?', 'Remove timer', {
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel',
                    type: 'error'
                }).then(() => {
                    const index = this.timers.indexOf(this.timer)
                    this.timers.splice(index, 1)
                    localStorage.setItem('boss_task_timers', JSON.stringify(this.timers))
                })
            }
        },

        mounted () {
            this.startTimer()
        },

        beforeDestroy () {
            clearInterval(this.interval)
        }
    }
</script>