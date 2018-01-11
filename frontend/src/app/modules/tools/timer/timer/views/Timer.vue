<template>
    <panel class="timer">
        <div class="thumb">
            <!--<img :src="image_path('creature', boss.id)" alt="">-->
        </div>

        <div class="data">
            <span class="name">{{ boss.name }}</span>
            <span class="respawn">
                <b>Respawn Time:</b>
                {{ boss.respawn / 60 }} hours
            </span>

            <span class="character">{{ timer.character }}</span>

            <div class="countdown">
                <span class="available margin-right-10" v-if="! countdown">Available</span>
                <span class="margin-right-10" v-else>{{ countdown }}</span>

                <button class="btn btn-xs btn-reset" @click.prevent="reset">
                    <span v-if="countdown">
                        <i class="mdi mdi-refresh"></i>
                    </span>

                    <span v-else>
                        <i class="mdi mdi-timer margin-right-5"></i>
                        Start
                    </span>
                </button>

                <button class="btn btn-xs btn-reset" @click.prevent="cancel" v-if="countdown">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
        </div>
    </panel>
</template>

<script>
    export default {
        props: ['timer', 'boss', 'timers'],

        data () {
            return {
                countdown: 0,
                interval: null
            }
        },

        methods: {
            startTimer () {
                const respawnTime = this.boss.respawn
                const lastTime = this.timer.last_time
                const nextRespawn = moment(lastTime).add(respawnTime, 'minutes')

                this.interval = setInterval(() => {
                    const diffTime = moment(nextRespawn).diff(moment())
                    const duration = moment.duration(diffTime)
                    this.countdown = this.getHours(duration.asHours()) + moment.utc(duration.asMilliseconds()).format(":mm:ss")
                }, 1000)

                if (this.timer.last_time == null) {
                    clearInterval(this.interval)
                }
            },

            getHours (hours) {
                hours = Math.floor(hours)

                return hours >= 10 ? hours : `0${hours}`
            },

            reset () {
                clearInterval(this.interval)
                this.timer.last_time = moment()

                const index = this.timers.indexOf(this.timer)
                this.timers[index].last_time = moment()
                localStorage.setItem('timers', JSON.stringify(this.timers))

                this.startTimer()
            },

            cancel () {
                clearInterval(this.interval)
                this.timer.last_time = null
                this.countdown = 0

                const index = this.timers.indexOf(this.timer)
                this.timers[index].last_time = null
                localStorage.setItem('timers', JSON.stringify(this.timers))

                this.startTimer()
            },

            getTimer (timers) {
                const storage = timers.map(timer => {
                    return { boss: timer.boss }
                })

                const timer = { boss: this.timer.boss }

                console.log(storage)
                console.log(timer)

                console.log(storage.indexOf(timer))

                return storage.indexOf(timer)
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