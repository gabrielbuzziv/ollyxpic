<template>
    <div class="social">
        <panel class="twitch" v-if="twitch">
            <a :href="getTwitchUrl(twitch)" target="_modal">
                <img src="src/assets/images/twitch.png" alt="">
                {{ twitch }}
            </a>
        </panel>

        <panel class="youtube" v-if="youtube">
            <a :href="getYoutubeUrl(youtube)" target="_modal">
                <img src="src/assets/images/youtube.svg" alt="">
                {{ youtube }}
            </a>
        </panel>
    </div>
</template>

<script>
    export default {
        props: [ 'network'],

        computed: {
            player () {
                return this.$store.getters['player/GET_PLAYER']
            },

            description () {
                return this.player.description != null ? this.player.description : ''
            },

            twitch () {
                const lines = this.description.split('\r\n')
                const line = lines.filter(line => line.search('T: ') !== -1)
                return line.length ? line[0].substring(3) : false
            },

            youtube () {
                const lines = this.description.split('\r\n')
                const line = lines.filter(line => line.search('Y: ') !== -1)
                return line.length ? line[0].substring(3) : false
            },

            hasSocial () {
                return this.twitch !== false || this.youtube !== false
            }
        },

        watch: {
            hasSocial () {
                this.$emit('update:network', this.hasSocial)
            }
        },

        methods: {
            getTwitchUrl (twitch) {
                return `http://www.twitch.com/${twitch}`
            },

            getYoutubeUrl (youtube) {
                return `https://www.youtube.com/user/${youtube}`
            }
        }
    }
</script>