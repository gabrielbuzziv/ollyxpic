<template>
    <div class="select">
        <input type="hidden" :name="name">

        <a href="#" class="selected" @click.prevent="toggle">
            <span v-if="! blank">
                <img :src="selected.image" v-if="selected.image">
            </span>

            <span class="placeholder" v-else>{{ placeholder }}</span>
        </a>

        <transition name="fade">
            <div class="options" v-show="open">
                <input type="text" class="form-control" placeholder="Search" v-model="query" ref="search">

                <a href="#" @click.prevent="select(option)" v-for="option in list" class="option">
                    <img :src="option.image" v-if="option.image">
                </a>
            </div>
        </transition>
    </div>
</template>

<script type="text/babel">
    import { isEmpty } from 'lodash'

    export default {
        props: {
            name: String,

            options: {
                type: Array,
                required: true
            },

            label: {
                type: String,
                default: 'label'
            },

            trackBy: {
                type: String,
                default: 'value'
            },

            placeholder: String
        },

        data () {
            return {
                open: false,
                selected: {},
                query: '',
                list: []
            }
        },

        computed: {
            blank () {
                return !! isEmpty(this.selected)
            }
        },

        watch: {
            options () {
                this.list = this.options
            },

            selected () {
                this.$emit('input', this.selected.value)
            },

            query () {
                this.list = this.options

                if (! isEmpty(this.query)) {
                    this.list = this.list.filter(option => option.label.toLowerCase().match(this.query.toLowerCase()))
                } else {
                    this.list = this.options
                }
            }
        },

        methods: {
            toggle () {
                this.open = ! this.open

                if (this.open) {
                    this.query = ''
                    this.list = this.options
                    setTimeout(() => this.$refs.search.focus(), 1)
                }
            },

            select (value) {
                this.selected = value
                this.open = false
            }
        },

        mounted () {
            this.$refs.search.addEventListener('keydown', e => {
                if (e.keyCode == 13) {
                    this.select(this.list[0])
                }

                return false
            })
        }
    }
</script>